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
}

export const usePlayerStore = defineStore('player', {
  state: (): PlayerState => ({
    player: null,
    isLoading: false,
    error: null,
  }),

  actions: {
    async fetchPlayer(tag: string) {
      this.isLoading = true
      this.error = null

      try {
        // Clean the tag by removing the # if present
        const cleanTag = tag.startsWith('#') ? tag.substring(1) : tag

        const response = await api.get(`/players/${cleanTag}`)

        // Map backend fields to frontend model
        if (response.data.data) {
          const playerData = response.data.data

          // Add computed properties to match the frontend expected structure
          playerData.townHallLevel = playerData.town_hall_level
          playerData.bestTrophies = playerData.best_trophies
          playerData.warStars = playerData.war_stars

          // Default to 0 for donations if not provided
          playerData.donations = playerData.donations || 0
          playerData.donationsReceived = playerData.donations_received || 0

          this.player = playerData
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch player data'
        console.error('Error fetching player:', error)
      } finally {
        this.isLoading = false
      }
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
