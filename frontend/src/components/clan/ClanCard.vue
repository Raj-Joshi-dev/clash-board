<script setup lang="ts">
import { computed, ref } from 'vue'
import { useClanStore, type Clan } from '@/stores/clan'

const clanStore = useClanStore()
const isRefreshing = ref(false)
const showAllMembers = ref(false)

const clan = computed(() => clanStore.clan)
const lastFetched = computed(() => clanStore.formattedLastFetched)
const warWinRate = computed(() => clanStore.warWinRate)

// Function to refresh clan data
const refreshClanData = async () => {
  isRefreshing.value = true
  try {
    await clanStore.refreshClanData()
  } finally {
    isRefreshing.value = false
  }
}

// Function to format clan type to be more user-friendly
const formatClanType = (type: string) => {
  return type.replace(/([A-Z])/g, ' $1').replace(/^./, (str) => str.toUpperCase())
}

// Function to toggle showing all members
const toggleMembersList = () => {
  showAllMembers.value = !showAllMembers.value
}

// Function to format role for display
const formatRole = (role: string) => {
  if (role === 'coLeader') return 'Co-Leader'
  if (role === 'admin') return 'Elder'
  return role.charAt(0).toUpperCase() + role.slice(1)
}
</script>

<template>
  <div v-if="clan" class="clan-card bg-clash-light dark:bg-clash-dark p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-start mb-6">
      <div>
        <h2 class="text-2xl font-bold text-clash-blue">Clan Details</h2>
        <p v-if="lastFetched" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Last updated: {{ lastFetched }}
        </p>
      </div>
      <button
        @click="refreshClanData"
        class="bg-clash-blue hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center transition-colors"
        :disabled="isRefreshing || clanStore.isLoading"
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
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.001 8.001 0 01-15.357-2m15.357 2H15"
            />
          </svg>
          Refresh
        </span>
      </button>
    </div>

    <div class="flex flex-col lg:flex-row items-start gap-6">
      <!-- Clan Badge -->
      <div class="clan-badge flex-shrink-0">
        <img
          v-if="clan.badge_urls?.medium"
          :src="clan.badge_urls.medium"
          :alt="`${clan.name} Badge`"
          class="w-24 h-24 object-contain"
        />
        <div
          v-else
          class="bg-clash-blue rounded-full w-24 h-24 flex items-center justify-center text-white text-4xl font-bold"
        >
          {{ clan.name.charAt(0).toUpperCase() }}
        </div>
      </div>

      <!-- Clan Info -->
      <div class="flex-grow w-full">
        <h2 class="text-2xl font-bold text-clash-blue">{{ clan.name }}</h2>
        <p class="text-gray-600 dark:text-gray-300">#{{ clan.tag }}</p>

        <!-- Clan type, location, chat language & family friendly status -->
        <div class="mt-2 flex flex-wrap gap-2">
          <span
            class="px-2 py-0.5 bg-clash-blue bg-opacity-10 text-clash-blue rounded-full text-xs"
          >
            {{ formatClanType(clan.type) }}
          </span>
          <span
            v-if="clan.location_name"
            class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs"
          >
            {{ clan.location_name }}
          </span>
          <span
            v-if="clan.chat_language"
            class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs"
          >
            {{ clan.chat_language }}
          </span>
          <span
            v-if="clan.is_family_friendly === false"
            class="px-2 py-0.5 bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-600 dark:text-red-400 rounded-full text-xs"
          >
            18+
          </span>
        </div>

        <!-- Clan description -->
        <div
          v-if="clan.description"
          class="mt-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-md text-sm"
        >
          {{ clan.description }}
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Main Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">Clan Info</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>Clan Level</span>
                <span class="font-semibold">{{ clan.clan_level }}</span>
              </div>
              <div class="flex justify-between">
                <span>Members</span>
                <span class="font-semibold">{{ clan.members }}/50</span>
              </div>
              <div class="flex justify-between">
                <span>Required Trophies</span>
                <span class="font-semibold">{{ clan.required_trophies }}</span>
              </div>
              <div class="flex justify-between">
                <span>Clan Points</span>
                <span class="font-semibold">{{ clan.clan_points }}</span>
              </div>
              <div class="flex justify-between">
                <span>Builder Base Points</span>
                <span class="font-semibold">{{ clan.clan_builder_base_points }}</span>
              </div>
              <div class="flex justify-between">
                <span>Capital Points</span>
                <span class="font-semibold">{{ clan.clan_capital_points }}</span>
              </div>
            </div>
          </div>

          <!-- War Stats -->
          <div>
            <h3 class="text-lg font-semibold mb-2 border-b border-gray-200">War Stats</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span>War League</span>
                <span class="font-semibold">{{ clan.war_league_name || 'Not in league' }}</span>
              </div>
              <div class="flex justify-between">
                <span>Capital League</span>
                <span class="font-semibold">{{ clan.capital_league_name || 'Not in league' }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Frequency</span>
                <span class="font-semibold capitalize">{{ clan.war_frequency || 'Unknown' }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Win Streak</span>
                <span class="font-semibold">{{ clan.war_win_streak }}</span>
              </div>
              <div class="flex justify-between">
                <span>War Record</span>
                <span class="font-semibold"
                  >{{ clan.war_wins }}-{{ clan.war_losses }}-{{ clan.war_ties }}</span
                >
              </div>
              <div class="flex justify-between">
                <span>Win Rate</span>
                <span class="font-semibold">{{ warWinRate }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Members List Section -->
        <div class="mt-6">
          <div class="border-b border-gray-200 mb-3">
            <h3 class="text-lg font-semibold">Clan Members</h3>
          </div>
          
          <div v-if="clan.member_list && clan.member_list.length > 0">
            <!-- Preview of first 5 members when not showing all members -->
            <div v-if="!showAllMembers" class="mt-4">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rank</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trophies</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                  <tr v-for="(member, index) in clan.member_list.slice(0, 5)" :key="member.tag" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ index + 1 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ member.name }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">#{{ member.tag.replace('#', '') }}</div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">
                      <span
                        :class="{
                          'px-2 py-0.5 text-xs rounded-full': true,
                          'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': member.role === 'leader',
                          'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': member.role === 'coLeader',
                          'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': member.role === 'admin',
                          'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200': member.role === 'member',
                        }"
                      >
                        {{ formatRole(member.role) }}
                      </span>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ member.expLevel }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ member.trophies }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Full member list when showing all members -->
            <div v-if="showAllMembers" class="mt-4 overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rank</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trophies</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donations</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Received</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                  <tr v-for="(member, index) in clan.member_list" :key="member.tag" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ index + 1 }}</td>
                    <td class="px-3 py-2 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ member.name }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">#{{ member.tag.replace('#', '') }}</div>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">
                      <span
                        :class="{
                          'px-2 py-0.5 text-xs rounded-full': true,
                          'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': member.role === 'leader',
                          'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': member.role === 'coLeader',
                          'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': member.role === 'admin',
                          'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200': member.role === 'member',
                        }"
                      >
                        {{ formatRole(member.role) }}
                      </span>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ member.expLevel }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ member.trophies }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">{{ member.donations }}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ member.donationsReceived }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <div class="mt-4 text-center">
              <button
                @click="toggleMembersList"
                class="bg-clash-blue hover:bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors"
              >
                {{ showAllMembers ? 'Hide Members' : 'Show All Members' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-10 text-gray-500">
    No clan selected. Search for a clan using its tag.
  </div>
</template>
