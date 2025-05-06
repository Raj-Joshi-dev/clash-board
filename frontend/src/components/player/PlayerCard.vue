<script setup lang="ts">
import { computed, ref } from 'vue'
import { usePlayerStore, type Player } from '@/stores/player'

const playerStore = usePlayerStore()
const isRefreshing = ref(false)

const player = computed(() => playerStore.player)
const lastFetched = computed(() => playerStore.formattedLastFetched)

// Helper function to format donation ratio
const formatRatio = (value: number) => {
  return value.toFixed(2)
}

// Function to refresh player data
const refreshPlayerData = async () => {
  isRefreshing.value = true
  try {
    await playerStore.refreshPlayerData()
  } finally {
    isRefreshing.value = false
  }
}
</script>

<template>
  <div v-if="player" class="player-card bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-start mb-6">
      <div>
        <h2 class="text-2xl font-bold text-clash-blue">Player Details</h2>
        <p v-if="lastFetched" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Last updated: {{ lastFetched }}
        </p>
      </div>
      <button
        @click="refreshPlayerData"
        class="bg-clash-blue hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center transition-colors"
        :disabled="isRefreshing || playerStore.isLoading"
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
      <!-- Player Avatar -->
      <div
        class="avatar bg-clash-blue rounded-full w-24 h-24 flex items-center justify-center text-white text-4xl font-bold"
      >
        {{ player.name.charAt(0).toUpperCase() }}
      </div>

      <!-- Player Info -->
      <div class="flex-grow w-full">
        <h2 class="text-2xl font-bold text-clash-blue">{{ player.name }}</h2>
        <p class="text-gray-600 dark:text-gray-300">#{{ player.tag }}</p>

        <!-- Clan info if available -->
        <div v-if="player.clan_name" class="mt-2">
          <span class="text-gray-700 dark:text-gray-400">Clan: </span>
          <span class="font-semibold">{{ player.clan_name }}</span>
          <span
            v-if="player.role"
            class="ml-2 px-2 py-0.5 bg-clash-blue bg-opacity-10 text-clash-blue rounded-full text-xs"
          >
            {{ player.role }}
          </span>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
          <!-- Main Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Main Village</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Town Hall Level</span>
                <span class="font-semibold">{{ player.town_hall_level }}</span>
              </div>
              <div class="flex justify-between">
                <span>XP Level</span>
                <span class="font-semibold">{{ player.xp }}</span>
              </div>
              <div class="flex justify-between">
                <span>Trophies</span>
                <span class="font-semibold">{{ player.trophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Best Trophies</span>
                <span class="font-semibold">{{ player.best_trophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Stars</span>
                <span class="font-semibold">{{ player.war_stars }}</span>
              </div>
            </div>
          </div>

          <!-- Heroes if available -->
          <div v-if="player.heroes && player.heroes.length > 0">
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Heroes</h3>
            <div class="space-y-2">
              <div v-for="hero in player.heroes" :key="hero.name" class="flex justify-between">
                <span>{{ hero.name }}</span>
                <span class="font-semibold">{{ hero.level }}/{{ hero.maxLevel }}</span>
              </div>
            </div>
          </div>

          <!-- Donations section if available -->
          <div v-if="'donations' in player">
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Donations</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Donations</span>
                <span class="font-semibold">{{ player.donations }}</span>
              </div>
              <div class="flex justify-between">
                <span>Donations Received</span>
                <span class="font-semibold">{{ player.donationsReceived }}</span>
              </div>
              <div class="flex justify-between">
                <span>Ratio</span>
                <span class="font-semibold">{{ formatRatio(playerStore.donationRatio) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-10 text-gray-500">
    No player selected. Search for a player using their tag.
  </div>
</template>
