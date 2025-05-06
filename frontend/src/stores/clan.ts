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
  clanRank?: number
  previousClanRank?: number
}

export interface Clan {
  id: number
  tag: string
  name: string
  type: string
  description?: string
  location_name?: string
  chat_language?: string
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
  updated_at?: string
}

interface ClanState {
  clan: Clan | null
  isLoading: boolean
  error: string | null
  lastFetched: string | null
  isRefreshing: boolean
}

export const useClanStore = defineStore('clan', {
  state: (): ClanState => ({
    clan: null,
    isLoading: false,
    error: null,
    lastFetched: null,
    isRefreshing: false,
  }),

  actions: {
    async fetchClan(tag: string, forceRefresh = false) {
      this.isLoading = true
      this.error = null
      this.isRefreshing = forceRefresh

      try {
        // Clean the tag by removing the # if present
        const cleanTag = tag.startsWith('#') ? tag.substring(1) : tag

        const response = await api.get(`/clans/${cleanTag}`)
        if (response.data.success && response.data.data) {
          this.clan = response.data.data

          // Store the updated_at timestamp - with null check
          if (this.clan && this.clan.updated_at) {
            this.lastFetched = this.clan.updated_at
          }
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch clan data'
        console.error('Error fetching clan:', error)
      } finally {
        this.isLoading = false
        this.isRefreshing = false
      }
    },

    async refreshClanData() {
      if (this.clan?.tag) {
        await this.fetchClan(this.clan.tag, true)
      }
    },

    clearClan() {
      this.clan = null
      this.lastFetched = null
    },
  },

  getters: {
    isClanLoaded: (state) => !!state.clan,
    memberCount: (state) => state.clan?.members || 0,
    topDonators: (state) => {
      if (!state.clan?.memberList) return []

      return [...state.clan.memberList].sort((a, b) => b.donations - a.donations).slice(0, 3)
    },
    // Calculate war win rate
    warWinRate: (state) => {
      if (!state.clan) return 0
      const totalWars = state.clan.war_wins + state.clan.war_losses + state.clan.war_ties
      return totalWars > 0 ? Math.round((state.clan.war_wins / totalWars) * 100) : 0
    },
    // Format the last fetched date in a user-friendly way
    formattedLastFetched: (state) => {
      if (!state.lastFetched) return null

      try {
        const date = new Date(state.lastFetched)
        return new Intl.DateTimeFormat('en-US', {
          dateStyle: 'medium',
          timeStyle: 'short',
        }).format(date)
      } catch (error) {
        console.error('Error formatting date:', error)
        return state.lastFetched
      }
    },
  },
})
