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
  <div class="clan-search bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4 text-clash-blue">Find Clan</h2>
    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
      Enter a clan tag to view detailed information about the clan.
    </p>

    <div class="flex flex-col sm:flex-row gap-3">
      <div class="flex-grow">
        <label
          for="clan-tag-input"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >Clan Tag</label
        >
        <div class="relative">
          <span
            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400"
            v-if="!clanTag"
          >
            #
          </span>
          <input
            id="clan-tag-input"
            v-model="clanTag"
            type="text"
            placeholder="ABCDEF12"
            class="w-full p-2.5 pl-5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-clash-blue focus:border-clash-blue outline-none transition-all duration-200"
            :class="{ 'border-clash-red focus:border-clash-red focus:ring-clash-red': !isValidTag }"
            @input="validateTag"
            @keyup.enter="searchClan"
          />
        </div>
        <p v-if="!isValidTag" class="text-clash-red text-sm mt-1">
          Please enter a valid clan tag (e.g. #ABCDEF12)
        </p>
      </div>

      <div class="self-end">
        <button
          @click="searchClan"
          class="bg-clash-blue hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm transition-all duration-200 flex items-center justify-center min-w-[100px]"
          :disabled="clanStore.isLoading"
          :class="{ 'opacity-75 cursor-not-allowed': clanStore.isLoading }"
        >
          <svg
            v-if="clanStore.isLoading"
            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
          <span v-if="clanStore.isLoading">Searching...</span>
          <span v-else>Search</span>
        </button>
      </div>
    </div>

    <div
      v-if="clanStore.error"
      class="mt-4 p-3 bg-clash-red bg-opacity-10 text-clash-red rounded-lg border border-clash-red border-opacity-20"
    >
      <div class="flex items-start">
        <svg
          class="h-5 w-5 mr-2 flex-shrink-0 mt-0.5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
            clip-rule="evenodd"
          ></path>
        </svg>
        <span>{{ clanStore.error }}</span>
      </div>
    </div>

    <div v-if="clanStore.isClanLoaded" class="mt-4">
      <div
        class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-sm"
      >
        <div class="flex items-center">
          <img
            v-if="clanStore.clan?.badge_urls?.small"
            :src="clanStore.clan.badge_urls.small"
            alt="Clan Badge"
            class="h-10 w-10 mr-3"
          />
          <div>
            <h3 class="text-lg font-bold text-clash-blue">{{ clanStore.clan?.name }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">#{{ clanStore.clan?.tag }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
