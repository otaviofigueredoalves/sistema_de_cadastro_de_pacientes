<template>
  <transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4">
          <p class="text-sm text-gray-600">{{ message }}</p>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-lg">
          <button 
            @click="onCancel" 
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
          >
            Cancelar
          </button>
          <button 
            @click="onConfirm" 
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
          >
            Excluir
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'ConfirmModal',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Confirmar Exclusão'
    },
    message: {
      type: String,
      default: 'Tem certeza que deseja excluir este item?'
    }
  },
  methods: {
    onConfirm() {
      this.$emit('confirm');
    },
    onCancel() {
      this.$emit('cancel');
    }
  }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}
.modal-enter, .modal-leave-to {
  opacity: 0;
}
.modal-enter-active .bg-white {
  transition: transform 0.3s ease;
}
.modal-enter .bg-white, .modal-leave-to .bg-white {
  transform: scale(0.95);
}
</style>
