<script setup lang="ts">
import { ref } from 'vue'
import { useClanStore } from '@/stores/clan'

const clanTag = ref('')
const isValidTag = ref(true)
const clanStore = useClanStore()

const validateTag = () => {
  // Basic validation, Clash of Clans tags start with # and contain letters and numbers
  const tagPattern = /^#?[0-9A-Z]+$/i
  isValidTag.value = tagPattern.test(clanTag.value)
  return isValidTag.value
}

const searchClan = async () => {
  if (!validateTag()) return
  await clanStore.fetchClan(clanTag.value)
}
</script>

<template>
  <div class="clan-search bg-clash-light dark:bg-clash-dark p-4 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-clash-blue">Find Clan</h2>
    
    <div class="flex flex-col sm:flex-row gap-2">
      <div class="flex-grow">
        <input
          v-model="clanTag"
          type="text"
          placeholder="Enter clan tag (e.g. #ABCDEF12)"
          class="w-full p-2 border border-gray-300 rounded"
          :class="{ 'border-clash-red': !isValidTag }"
          @input="validateTag"
          @keyup.enter="searchClan"
        />
        <p v-if="!isValidTag" class="text-clash-red text-sm mt-1">
          Please enter a valid clan tag
        </p>
      </div>
      
      <button
        @click="searchClan"
        class="bg-clash-blue hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors"
        :disabled="clanStore.isLoading"
      >
        <span v-if="clanStore.isLoading">Searching...</span>
        <span v-else>Search</span>
      </button>
    </div>
    
    <div v-if="clanStore.error" class="mt-4 p-3 bg-clash-red bg-opacity-10 text-clash-red rounded">
      {{ clanStore.error }}
    </div>
  </div>
</template>