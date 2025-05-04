import { defineStore } from 'pinia'
import api from '@/services/api'

export interface ClanMember {
  tag: string
  name: string
  role: string
  expLevel: number
  trophies: number
  donations: number
  donationsReceived: number
}

export interface Clan {
  id: number
  tag: string
  name: string
  type: string
  description?: string
  location_name?: string
  is_family_friendly: boolean
  badge_urls: Record<string, string>
  clan_level: number
  clan_points: number
  clan_builder_base_points: number
  clan_capital_points: number
  capital_league_name?: string
  required_trophies: number
  war_frequency?: string
  war_win_streak: number
  war_wins: number
  war_ties: number
  war_losses: number
  is_war_log_public: boolean
  war_league_name?: string
  members: number
  memberList?: ClanMember[]
}

interface ClanState {
  clan: Clan | null
  isLoading: boolean
  error: string | null
}

export const useClanStore = defineStore('clan', {
  state: (): ClanState => ({
    clan: null,
    isLoading: false,
    error: null,
  }),

  actions: {
    async fetchClan(tag: string) {
      this.isLoading = true
      this.error = null

      try {
        // Clean the tag by removing the # if present
        const cleanTag = tag.startsWith('#') ? tag.substring(1) : tag

        const response = await api.get(`/clans/${cleanTag}`)
        this.clan = response.data.data
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch clan data'
        console.error('Error fetching clan:', error)
      } finally {
        this.isLoading = false
      }
    },

    clearClan() {
      this.clan = null
    },
  },

  getters: {
    isClanLoaded: (state) => !!state.clan,
    memberCount: (state) => state.clan?.members || 0,
    topDonators: (state) => {
      if (!state.clan?.memberList) return []

      return [...state.clan.memberList].sort((a, b) => b.donations - a.donations).slice(0, 5)
    },
  },
})
