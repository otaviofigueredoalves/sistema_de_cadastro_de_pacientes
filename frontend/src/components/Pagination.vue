<template>
  <div v-if="totalPages > 1" class="pagination-container">
    <button 
      class="pagination-btn" 
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
    >
      Anterior
    </button>
    
    <div class="pagination-numbers">
      <span class="pagination-info">
        Página {{ currentPage }} de {{ totalPages }}
      </span>
    </div>

    <button 
      class="pagination-btn" 
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
    >
      Próxima
    </button>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    totalPages: {
      type: Number,
      required: true
    }
  },
  methods: {
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.$emit('page-changed', page);
      }
    }
  }
}
</script>

<style scoped>
.pagination-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 0;
  border-top: 1px solid var(--gray-medium);
  margin-top: 1rem;
}

.pagination-numbers {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.pagination-info {
  font-size: 0.875rem;
  color: var(--text-color);
  font-weight: 500;
}

.pagination-btn {
  padding: 0.5rem 1rem;
  border: 1px solid var(--gray-medium);
  background-color: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.875rem;
  font-family: inherit;
  transition: all 0.2s;
  color: var(--text-color);
}

.pagination-btn:hover:not(:disabled) {
  background-color: var(--bg-color);
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background-color: var(--bg-color);
}
</style>
