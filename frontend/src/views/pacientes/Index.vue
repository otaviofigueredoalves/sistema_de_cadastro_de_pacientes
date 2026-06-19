<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Pacientes</h1>
      <router-link to="/pacientes/novo" class="bg-[#006CE5] text-white px-4 py-2 rounded shadow hover:bg-blue-700">
        Novo Paciente
      </router-link>
    </div>

    <!-- Filtros -->
    <div class="flex gap-4">
      <input 
        type="text" 
        v-model="filters.search" 
        @input="onSearch"
        placeholder="Buscar por nome, CPF ou CNS..."
        class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
      >
      
      <select v-model="filters.gender" @change="fetchData" class="border border-gray-300 rounded px-3 py-2">
        <option value="">Todos os Gêneros</option>
        <option value="M">Masculino</option>
        <option value="F">Feminino</option>
        <option value="O">Outro</option>
      </select>
    </div>

    <div v-if="loading" class="text-center py-4">Carregando...</div>

    <!-- Tabela -->
    <div v-else>
      <BaseTable 
        :columns="tableColumns"
        :data="items"
        :sortKey="filters.sort_by"
        :sortDir="filters.sort_dir"
        @sort="sortBy"
        @edit="$router.push(`/pacientes/${$event.id}`)"
        @delete="confirmDelete($event.id)"
      >
        <template #cell-name="{ item }">
          <span class="font-semibold">{{ item.name }}</span>
        </template>
        <template #cell-cpf="{ item }">
          {{ formatCpf(item.cpf) }}
        </template>
        <template #cell-cns="{ item }">
          {{ formatCns(item.cns) }}
        </template>
        <template #cell-gender="{ item }">
          {{ formatGender(item.gender) }}
        </template>
      </BaseTable>
    </div>

    <!-- Paginação -->
    <Pagination 
      v-if="pagination.lastPage > 1"
      :currentPage="pagination.currentPage" 
      :totalPages="pagination.lastPage" 
      @page-changed="onPageChanged" 
    />

    <ConfirmModal 
      :show="showDeleteModal"
      title="Excluir Paciente"
      message="Tem certeza que deseja excluir este paciente? Esta ação não poderá ser desfeita."
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import Pagination from '../../components/Pagination.vue'
import ConfirmModal from '../../components/ConfirmModal.vue'
import BaseTable from '../../components/BaseTable.vue'

export default {
  name: 'PacientesIndex',
  components: { Pagination, ConfirmModal, BaseTable },
  data() {
    return {
      tableColumns: [
        { key: 'id', label: 'ID', sortable: true },
        { key: 'name', label: 'Nome', sortable: true },
        { key: 'cpf', label: 'CPF', sortable: true },
        { key: 'cns', label: 'CNS', sortable: true },
        { key: 'gender', label: 'Gênero', sortable: true }
      ],
      filters: {
        search: '',
        gender: '',
        sort_by: 'id',
        sort_dir: 'desc',
        page: 1
      },
      searchTimeout: null,
      showDeleteModal: false,
      deleteTargetId: null
    }
  },
  computed: {
    ...mapState('patients', ['items', 'pagination', 'loading'])
  },
  created() {
    this.fetchData()
  },
  methods: {
    ...mapActions('patients', ['fetchPatients', 'deletePatient']),
    
    fetchData() {
      this.fetchPatients(this.filters)
    },
    
    onSearch() {
      if (this.searchTimeout) clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.filters.page = 1
        this.fetchData()
      }, 400)
    },
    
    sortBy(column) {
      if (this.filters.sort_by === column) {
        this.filters.sort_dir = this.filters.sort_dir === 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.sort_by = column
        this.filters.sort_dir = 'asc'
      }
      this.fetchData()
    },
    
    onPageChanged(page) {
      this.filters.page = page
      this.fetchData()
    },
    
    confirmDelete(id) {
      this.deleteTargetId = id
      this.showDeleteModal = true
    },
    
    async executeDelete() {
      if (!this.deleteTargetId) return

      try {
        await this.deletePatient(this.deleteTargetId)
        this.$store.dispatch('notifications/show', { message: 'Paciente excluído com sucesso!' })
        this.fetchData()
      } catch (error) {
        const msg = error.response?.data?.message || 'Erro ao excluir'
        this.$store.dispatch('notifications/show', { message: msg, type: 'error' })
      } finally {
        this.showDeleteModal = false
        this.deleteTargetId = null
      }
    },

    formatCpf(cpf) {
      if (!cpf) return ''
      return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4")
    },

    formatCns(cns) {
      if (!cns) return ''
      return cns.replace(/(\d{3})(\d{4})(\d{4})(\d{4})/, "$1 $2 $3 $4")
    },

    formatGender(gender) {
      const map = { 'M': 'Masculino', 'F': 'Feminino', 'O': 'Outro' }
      return map[gender] || gender
    }
  }
}
</script>
