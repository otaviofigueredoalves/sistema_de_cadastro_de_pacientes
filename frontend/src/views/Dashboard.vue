<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    
    <div v-if="loading" class="text-gray-500">
      Carregando totais...
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Pacientes Totais -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white bg-blue-500 flex-shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div class="ml-4">
          <div class="text-3xl font-light text-gray-800">{{ totals.patients_count }}</div>
          <div class="text-sm font-medium text-gray-500">Pacientes cadastrados</div>
        </div>
      </div>

      <!-- Endereços Totais -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white bg-green-500 flex-shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div class="ml-4">
          <div class="text-3xl font-light text-gray-800">{{ totals.addresses_count }}</div>
          <div class="text-sm font-medium text-gray-500">Endereços cadastrados</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import dashboardService from '../services/dashboard.service'

export default {
  name: 'Dashboard',
  data() {
    return {
      totals: {
        patients_count: 0,
        addresses_count: 0
      },
      loading: true
    }
  },
  async created() {
    try {
      const response = await dashboardService.getTotals()
      this.totals = response.data
    } catch (error) {
      console.error('Erro ao carregar dashboard', error)
    } finally {
      this.loading = false
    }
  }
}
</script>
