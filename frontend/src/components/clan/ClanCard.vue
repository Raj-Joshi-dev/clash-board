<script setup lang="ts">
import { computed, ref } from 'vue'
import { useClanStore, type Clan } from '@/stores/clan'

const clanStore = useClanStore()
const isRefreshing = ref(false)

const clan = computed(() => clanStore.clan)
const lastFetched = computed(() => clanStore.formattedLastFetched)
const warWinRate = computed(() => clanStore.warWinRate)

// Function to refresh clan data
const refreshClanData = async () => {
  isRefreshing.value = true
  try {
    await clanStore.refreshClanData()
  } finally {
    isRefreshing.value = false
  }
}

// Function to format clan type to be more user-friendly
const formatClanType = (type: string) => {
  return type.replace(/([A-Z])/g, ' $1').replace(/^./, (str) => str.toUpperCase())
}

// Function to get top 5 donators
const topDonators = computed(() => clanStore.topDonators)
</script>

<template>
  <div v-if="clan" class="clan-card bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-start mb-6">
      <div>
        <h2 class="text-2xl font-bold text-clash-blue">Clan Details</h2>
        <p v-if="lastFetched" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Last updated: {{ lastFetched }}
        </p>
      </div>
      <button
        @click="refreshClanData"
        class="bg-clash-blue hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center transition-colors"
        :disabled="isRefreshing || clanStore.isLoading"
      >
        <span v-if="isRefreshing">Refreshing...</span>
        <span v-else>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 mr-1 inline-block"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
            />
          </svg>
          Refresh
        </span>
      </button>
    </div>

    <div class="flex flex-col lg:flex-row items-start gap-6">
      <!-- Clan Badge -->
      <div class="clan-badge flex-shrink-0">
        <img
          v-if="clan.badge_urls?.medium"
          :src="clan.badge_urls.medium"
          :alt="`${clan.name} Badge`"
          class="w-24 h-24 object-contain"
        />
        <div
          v-else
          class="bg-clash-blue rounded-full w-24 h-24 flex items-center justify-center text-white text-4xl font-bold"
        >
          {{ clan.name.charAt(0).toUpperCase() }}
        </div>
      </div>

      <!-- Clan Info -->
      <div class="flex-grow w-full">
        <h2 class="text-2xl font-bold text-clash-blue">{{ clan.name }}</h2>
        <p class="text-gray-600 dark:text-gray-300">#{{ clan.tag }}</p>

        <!-- Clan type & location -->
        <div class="mt-2 flex flex-wrap gap-2">
          <span
            class="px-2 py-0.5 bg-clash-blue bg-opacity-10 text-clash-blue rounded-full text-xs"
          >
            {{ formatClanType(clan.type) }}
          </span>
          <span
            v-if="clan.location_name"
            class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs"
          >
            {{ clan.location_name }}
          </span>
          <span
            v-if="clan.is_family_friendly === false"
            class="px-2 py-0.5 bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-600 dark:text-red-400 rounded-full text-xs"
          >
            18+
          </span>
        </div>

        <!-- Clan description -->
        <div
          v-if="clan.description"
          class="mt-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-md text-sm"
        >
          {{ clan.description }}
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
          <!-- Main Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Clan Info</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Clan Level</span>
                <span class="font-semibold">{{ clan.clan_level }}</span>
              </div>
              <div class="flex justify-between">
                <span>Members</span>
                <span class="font-semibold">{{ clan.members }}/50</span>
              </div>
              <div class="flex justify-between">
                <span>Required Trophies</span>
                <span class="font-semibold">{{ clan.required_trophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Clan Points</span>
                <span class="font-semibold">{{ clan.clan_points }}</span>
              </div>
              <div class="flex justify-between">
                <span>Builder Base Points</span>
                <span class="font-semibold">{{ clan.clan_builder_base_points }}</span>
              </div>
              <div class="flex justify-between">
                <span>Capital Points</span>
                <span class="font-semibold">{{ clan.clan_capital_points }}</span>
              </div>
            </div>
          </div>

          <!-- War Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">War Stats</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>War League</span>
                <span class="font-semibold">{{ clan.war_league_name || 'Not in league' }}</span>
              </div>
              <div class="flex justify-between">
                <span>Capital League</span>
                <span class="font-semibold">{{ clan.capital_league_name || 'Not in league' }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Frequency</span>
                <span class="font-semibold capitalize">{{ clan.war_frequency || 'Unknown' }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Win Streak</span>
                <span class="font-semibold">{{ clan.war_win_streak }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Record</span>
                <span class="font-semibold"
                  >{{ clan.war_wins }}-{{ clan.war_losses }}-{{ clan.war_ties }}</span
                >
              </div>
              <div class="flex justify-between">
                <span>Win Rate</span>
                <span class="font-semibold">{{ warWinRate }}%</span>
              </div>
            </div>
          </div>

          <!-- Top Donators section -->
          <div v-if="topDonators.length > 0">
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Top Donators</h3>
            <div class="space-y-2">
              <div
                v-for="(member, index) in topDonators"
                :key="member.tag"
                class="flex justify-between"
              >
                <span class="truncate max-w-[150px]">{{ index + 1 }}. {{ member.name }}</span>
                <span class="font-semibold">{{ member.donations }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-10 text-gray-500">
    No clan selected. Search for a clan using its tag.
  </div>
</template>
