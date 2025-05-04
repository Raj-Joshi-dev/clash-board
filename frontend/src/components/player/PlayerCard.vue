<script setup lang="ts">
import { computed } from 'vue'
import { usePlayerStore, type Player } from '@/stores/player'

const playerStore = usePlayerStore()

const player = computed(() => playerStore.player)

// Helper function to format donation ratio
const formatRatio = (value: number) => {
  return value.toFixed(2)
}
</script>

<template>
  <div v-if="player" class="player-card bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
    <div class="flex flex-col md:flex-row items-start gap-6">
      <!-- Player Avatar -->
      <div
        class="avatar bg-clash-blue rounded-full w-24 h-24 flex items-center justify-center text-white text-4xl font-bold"
      >
        {{ player.name.charAt(0).toUpperCase() }}
      </div>

      <!-- Player Info -->
      <div class="flex-grow">
        <h2 class="text-2xl font-bold text-clash-blue">{{ player.name }}</h2>
        <p class="text-gray-600 dark:text-gray-300">{{ player.id }}</p>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Main Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Main Village</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Town Hall Level</span>
                <span class="font-semibold">{{ player.townHallLevel }}</span>
              </div>
              <div class="flex justify-between">
                <span>Trophies</span>
                <span class="font-semibold">{{ player.trophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Best Trophies</span>
                <span class="font-semibold">{{ player.bestTrophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Stars</span>
                <span class="font-semibold">{{ player.warStars }}</span>
              </div>
            </div>
          </div>

          <!-- Builder Base Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Builder Base</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Builder Hall Level</span>
                <span class="font-semibold">{{ player.builderHallLevel }}</span>
              </div>
              <div class="flex justify-between">
                <span>Trophies</span>
                <span class="font-semibold">{{ player.versusTrophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Best Trophies</span>
                <span class="font-semibold">{{ player.bestVersusTrophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Battle Wins</span>
                <span class="font-semibold">{{ player.versusBattleWins }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Donations -->
        <div class="mt-4">
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
  <div v-else class="text-center py-10 text-gray-500">
    No player selected. Search for a player using their tag.
  </div>
</template>
