<script setup lang="ts">
import PlayerSearch from '../components/player/PlayerSearch.vue'
import PlayerCard from '../components/player/PlayerCard.vue'
</script>

<template>
  <main>
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-clash-blue mb-4">Clash of Clans Player Dashboard</h1>
      <p class="text-gray-600 dark:text-gray-300 mb-6">
        Search for a Clash of Clans player using their tag to view their stats and information.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-1">
        <PlayerSearch />
      </div>
      <div class="lg:col-span-2">
        <PlayerCard />
      </div>
    </div>
  </main>
</template>
