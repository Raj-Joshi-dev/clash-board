import { defineStore } from 'pinia'
import api from '../services/api'

export interface Player {
  id: number
  name: string
  tag: string
  town_hall_level: number
  xp: number
  trophies: number
  best_trophies: number
  war_stars: number
  clan_tag?: string
  clan_name?: string
  role?: string
  heroes?: Array<{
    name: string
    level: number
    maxLevel: number
  }>
  // Keep some convenience computed properties as getters
  townHallLevel: number
  bestTrophies: number
  warStars: number
  donations: number
  donationsReceived: number
}

interface PlayerState {
  player: Player | null
  isLoading: boolean
  error: string | null
  isRefreshing: boolean // New flag to track if we're refreshing data vs. initial load
}

export const usePlayerStore = defineStore('player', {
  state: (): PlayerState => ({
    player: null,
    isLoading: false,
    error: null,
    isRefreshing: false,
  }),

  actions: {
    async fetchPlayer(tag: string, forceRefresh = false) {
      this.isLoading = true
      this.isRefreshing = forceRefresh
      this.error = null

      try {
        // Clean the tag by removing the # if present
        const cleanTag = tag.startsWith('#') ? tag.substring(1) : tag

        if (forceRefresh) {
          // If force refresh, go straight to the API
          return await this.fetchPlayerFromApi(cleanTag)
        }

        // First try to get from database
        try {
          const response = await api.get(`/players/${cleanTag}`)

          if (response.data.success && response.data.data) {
            const playerData = response.data.data

            // Add computed properties to match the frontend expected structure
            playerData.townHallLevel = playerData.town_hall_level
            playerData.bestTrophies = playerData.best_trophies
            playerData.warStars = playerData.war_stars

            // Default to 0 for donations if not provided
            playerData.donations = playerData.donations || 0
            playerData.donationsReceived = playerData.donations_received || 0

            this.player = playerData
            return playerData
          }
        } catch (dbError: any) {
          console.log('Player not found in database, trying Clash API...')
          // If not in database (404) or other error, try the API
          if (dbError.response?.status === 404 || !dbError.response) {
            return await this.fetchPlayerFromApi(cleanTag)
          } else {
            throw dbError // Some other error occurred with the database request
          }
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch player data'
        console.error('Error fetching player:', error)
        return null
      } finally {
        this.isLoading = false
        this.isRefreshing = false
      }
    },

    async fetchPlayerFromApi(tag: string) {
      if (!this.isRefreshing) {
        this.isLoading = true // Only set loading if not already in a refresh operation
      }

      try {
        const response = await api.get(`/players/fetch-from-api/${tag}`)

        if (response.data.success && response.data.data) {
          const playerData = response.data.data

          // Add computed properties to match the frontend expected structure
          playerData.townHallLevel = playerData.town_hall_level
          playerData.bestTrophies = playerData.best_trophies
          playerData.warStars = playerData.war_stars

          // Default to 0 for donations if not provided
          playerData.donations = playerData.donations || 0
          playerData.donationsReceived = playerData.donations_received || 0

          this.player = playerData
          this.error = null
          return playerData
        } else {
          throw new Error(response.data.message || 'Failed to fetch player data from API')
        }
      } catch (error: any) {
        this.error =
          error.response?.data?.message ||
          error.message ||
          'Failed to fetch player data from Clash API'
        console.error('Error fetching player from API:', error)
        return null
      } finally {
        if (!this.isRefreshing) {
          this.isLoading = false // Only reset loading if not in a refresh operation
        }
      }
    },

    // Convenience method to refresh player data
    async refreshPlayerData() {
      if (!this.player?.tag) {
        this.error = 'No player loaded to refresh'
        return null
      }

      return await this.fetchPlayer(this.player.tag, true)
    },

    clearPlayer() {
      this.player = null
    },
  },

  getters: {
    isPlayerLoaded: (state) => !!state.player,
    donationRatio: (state) => {
      if (!state.player || state.player.donationsReceived === 0) return 0
      return state.player.donations / state.player.donationsReceived
    },
  },
})
