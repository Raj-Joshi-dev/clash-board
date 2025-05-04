import { defineStore } from 'pinia'
import axios from 'axios'

export interface ClanMember {
  tag: string
  name: string
  role: string
  expLevel: number
  league: {
    id: number
    name: string
    iconUrls: {
      small: string
      medium: string
      large: string
    }
  }
  trophies: number
  versusTrophies: number
  clanRank: number
  previousClanRank: number
  donations: number
  donationsReceived: number
}

export interface Clan {
  tag: string
  name: string
  type: string
  description: string
  location: {
    id: number
    name: string
    isCountry: boolean
    countryCode: string
  }
  badgeUrls: {
    small: string
    medium: string
    large: string
  }
  clanLevel: number
  clanPoints: number
  clanVersusPoints: number
  requiredTrophies: number
  warFrequency: string
  warWinStreak: number
  warWins: number
  warTies: number
  warLosses: number
  isWarLogPublic: boolean
  warLeague: {
    id: number
    name: string
  }
  members: number
  memberList: ClanMember[]
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

        const response = await axios.get(`/api/clans/${cleanTag}`)
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
