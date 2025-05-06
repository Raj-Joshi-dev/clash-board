<script setup lang="ts">
import ClanSearch from '../components/clan/ClanSearch.vue'
</script>

<template>
  <main>
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-clash-blue mb-4">Clash of Clans Clan Dashboard</h1>
      <p class="text-gray-600 dark:text-gray-300 mb-6">
        Search for a Clash of Clans clan using their tag to view clan information.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div class="lg:col-span-1">
        <ClanSearch />
      </div>
      <div class="lg:col-span-3">
        <div v-if="false" class="bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
          <!-- Placeholder for clan details component -->
          <p class="text-center text-gray-500">Clan details will be displayed here</p>
        </div>
      </div>
    </div>
  </main>
</template>
