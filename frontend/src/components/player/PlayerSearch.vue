<script setup lang="ts">
import { ref } from 'vue'
import { usePlayerStore } from '@/stores/player'

const playerTag = ref('')
const isValidTag = ref(true)
const playerStore = usePlayerStore()

const validateTag = () => {
  // Basic validation, Clash of Clans tags start with # and contain letters and numbers
  const tagPattern = /^#?[0-9A-Z]+$/i
  isValidTag.value = tagPattern.test(playerTag.value)
  return isValidTag.value
}

const searchPlayer = async () => {
  if (!validateTag()) return
  await playerStore.fetchPlayer(playerTag.value)
}
</script>

<template>
  <div class="player-search bg-clash-light dark:bg-clash-dark p-4 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-clash-blue">Find Player</h2>

    <div class="flex flex-col sm:flex-row gap-2">
      <div class="flex-grow">
        <input
          v-model="playerTag"
          type="text"
          placeholder="Enter player tag (e.g. #ABCDEF12)"
          class="w-full p-2 border border-gray-300 rounded"
          :class="{ 'border-clash-red': !isValidTag }"
          @input="validateTag"
          @keyup.enter="searchPlayer"
        />
        <p v-if="!isValidTag" class="text-clash-red text-sm mt-1">
          Please enter a valid player tag
        </p>
      </div>

      <button
        @click="searchPlayer"
        class="bg-clash-blue hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors"
        :disabled="playerStore.isLoading"
      >
        <span v-if="playerStore.isLoading">Searching...</span>
        <span v-else>Search</span>
      </button>
    </div>

    <div
      v-if="playerStore.error"
      class="mt-4 p-3 bg-clash-red bg-opacity-10 text-clash-red rounded"
    >
      {{ playerStore.error }}
    </div>
  </div>
</template>
