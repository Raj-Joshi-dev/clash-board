<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="app-container min-h-screen bg-gray-100 dark:bg-gray-900">
    <header class="bg-clash-blue text-white p-4 shadow-md sticky top-0 z-10">
      <div class="container-fluid mx-auto px-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Clash Board</h1>
        <nav class="space-x-4">
          <RouterLink to="/" class="hover:text-gray-200 transition-colors nav-link"
            >Home</RouterLink
          >
          <RouterLink to="/clans" class="hover:text-gray-200 transition-colors nav-link"
            >Clans</RouterLink
          >
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

/* Fix the yellow tint on router links */
.nav-link {
  color: white;
  text-decoration: none;
}

.nav-link:hover,
.nav-link.router-link-active {
  color: rgba(219, 234, 254, 1); /* Light blue hover color */
}

a {
  color: var(--color-text);
  text-decoration: none;
}

/* Override the green link color */
a:not(.nav-link),
.green {
  color: #3b82f6; /* Tailwind blue-500 */
  transition: 0.2s;
}

@media (hover: hover) {
  a:not(.nav-link):hover {
    color: #2563eb; /* Tailwind blue-600 */
    background-color: transparent;
  }
}
</style>
