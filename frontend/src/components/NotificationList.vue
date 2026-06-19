<template>
  <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
    <transition-group name="fade" tag="div" class="flex flex-col gap-2">
      <div 
        v-for="notification in items" 
        :key="notification.id"
        class="min-w-[300px] p-4 rounded-lg shadow-lg flex justify-between items-center text-white"
        :class="{
          'bg-green-600': notification.type === 'success',
          'bg-red-600': notification.type === 'error',
          'bg-blue-600': notification.type === 'info',
          'bg-yellow-600': notification.type === 'warning'
        }"
      >
        <span class="font-medium">{{ notification.message }}</span>
        <button @click="remove(notification.id)" class="ml-4 text-white hover:text-gray-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'NotificationList',
  computed: {
    ...mapState('notifications', ['items'])
  },
  methods: {
    ...mapActions('notifications', ['remove'])
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
  transform: translateX(20px);
}
</style>
