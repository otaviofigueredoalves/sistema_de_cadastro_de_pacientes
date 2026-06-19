<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Endereços</h1>
      <router-link to="/enderecos/novo" class="bg-[#006CE5] text-white px-4 py-2 rounded shadow hover:bg-blue-700">
        Novo Endereço
      </router-link>
    </div>

    <!-- Filtros -->
    <div class="flex gap-4">
      <input 
        type="text" 
        v-model="filters.search" 
        @input="onSearch"
        placeholder="Buscar por rua, bairro, cidade..."
        class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/3"
      >
      
      <select v-model="filters.state" @change="fetchData" class="border border-gray-300 rounded px-3 py-2">
        <option value="">Todos os Estados</option>
        <option v-for="uf in ufs" :key="uf" :value="uf">{{ uf }}</option>
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
        @edit="$router.push(`/enderecos/${$event.id}`)"
        @delete="confirmDelete($event.id)"
      />
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
      title="Excluir Endereço"
      message="Tem certeza que deseja excluir este endereço? Esta ação não poderá ser desfeita."
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
  name: 'EnderecosIndex',
  components: { Pagination, ConfirmModal, BaseTable },
  data() {
    return {
      tableColumns: [
        { key: 'id', label: 'ID', sortable: true },
        { key: 'street', label: 'Logradouro', sortable: true },
        { key: 'neighborhood', label: 'Bairro', sortable: true },
        { key: 'city', label: 'Cidade', sortable: true },
        { key: 'state', label: 'UF', sortable: true }
      ],
      filters: {
        search: '',
        state: '',
        sort_by: 'id',
        sort_dir: 'desc',
        page: 1
      },
      searchTimeout: null,
      ufs: ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'],
      showDeleteModal: false,
      deleteTargetId: null
    }
  },
  computed: {
    ...mapState('addresses', ['items', 'pagination', 'loading'])
  },
  created() {
    this.fetchData()
  },
  methods: {
    ...mapActions('addresses', ['fetchAddresses', 'deleteAddress']),
    
    fetchData() {
      this.fetchAddresses(this.filters)
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
        await this.deleteAddress(this.deleteTargetId)
        this.$store.dispatch('notifications/show', { message: 'Endereço excluído com sucesso!' })
        this.fetchData()
      } catch (error) {
        const msg = error.response?.data?.message || 'Erro ao excluir'
        this.$store.dispatch('notifications/show', { message: msg, type: 'error' })
      } finally {
        this.showDeleteModal = false
        this.deleteTargetId = null
      }
    }
  }
}
</script>
