<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="app-container min-h-screen bg-gray-100 dark:bg-gray-900">
    <header class="bg-clash-blue text-white p-4 shadow-md">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Clash Board</h1>
        <nav class="space-x-4">
          <RouterLink to="/" class="hover:text-gray-200 transition-colors">Home</RouterLink>
          <RouterLink to="/clans" class="hover:text-gray-200 transition-colors">Clans</RouterLink>
        </nav>
      </div>
    </header>

    <main class="container mx-auto p-4">
      <RouterView />
    </main>

    <footer class="bg-gray-800 text-white p-4 mt-8">
      <div class="container mx-auto text-center">
        <p>Clash Board © 2025</p>
      </div>
    </footer>
  </div>
</template>

<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 1200px;
}
</style>
