import { defineStore } from 'pinia'
import axios from 'axios'

export interface Player {
  id: string
  name: string
  townHallLevel: number
  trophies: number
  bestTrophies: number
  warStars: number
  attackWins: number
  defenseWins: number
  builderHallLevel: number
  versusTrophies: number
  bestVersusTrophies: number
  versusBattleWins: number
  role: string
  clanRank: number
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

        const response = await axios.get(`/api/players/${cleanTag}`)
        this.player = response.data.data
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
