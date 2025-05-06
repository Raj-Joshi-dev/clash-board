<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="app-container min-h-screen bg-gray-100 dark:bg-gray-900">
    <header class="bg-clash-blue text-white p-4 shadow-md sticky top-0 z-10">
      <div class="container-fluid mx-auto px-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Clash Board</h1>
        <nav class="space-x-4">
          <RouterLink to="/" class="hover:text-gray-200 transition-colors">Home</RouterLink>
          <RouterLink to="/clans" class="hover:text-gray-200 transition-colors">Clans</RouterLink>
        </nav>
      </div>
    </header>

    <main class="container-fluid mx-auto px-4 py-6">
      <RouterView />
    </main>

    <footer class="bg-gray-800 text-white p-4 mt-8">
      <div class="container-fluid mx-auto px-4 text-center">
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

/* Responsive container system */
.container-fluid {
  width: 100%;
  max-width: 100%;
}

/* Apply different max-widths at different breakpoints */
@media (min-width: 1024px) {
  .container-fluid {
    width: 90%;
  }
}

@media (min-width: 1536px) {
  .container-fluid {
    width: 85%;
    max-width: 1800px; /* Still set a max to prevent excessive width on ultra-wide screens */
  }
}
</style>
