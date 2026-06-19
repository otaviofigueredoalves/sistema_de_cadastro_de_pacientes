<template>
  <div class="min-h-screen bg-gray-100 flex flex-col md:flex-row font-sans relative">
    
    <!-- Mobile sidebar overlay -->
    <div 
      v-if="isSidebarOpen" 
      @click="isSidebarOpen = false" 
      class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
    ></div>

    <!-- Sidebar -->
    <nav 
      :class="[
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex flex-col transform transition duration-200 ease-in-out md:relative md:translate-x-0'
      ]"
    >
      <div class="h-16 flex items-center justify-start border-b border-gray-100 flex-shrink-0 px-6">
        <img src="../assets/imgs/logo-sus-color.png" alt="logo" class="h-8 object-contain">
      </div>
      <ul class="flex-1 overflow-y-auto py-4 space-y-1">
        <li>
          <router-link
            to="/"
            @click.native="isSidebarOpen = false"
            exact-active-class="bg-blue-50 text-blue-600 border-l-4 border-blue-600"
            class="flex items-center px-6 py-3 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent transition-colors"
          >
            <!-- Heroicon Home -->
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Início
          </router-link>
        </li>
        <li>
          <router-link
            to="/pacientes"
            @click.native="isSidebarOpen = false"
            active-class="bg-blue-50 text-blue-600 border-l-4 border-blue-600"
            class="flex items-center px-6 py-3 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent transition-colors"
          >
            <!-- Heroicon Users -->
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Pacientes
          </router-link>
        </li>
        <li>
          <router-link
            to="/enderecos"
            @click.native="isSidebarOpen = false"
            active-class="bg-blue-50 text-blue-600 border-l-4 border-blue-600"
            class="flex items-center px-6 py-3 text-sm text-gray-500 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent transition-colors"
          >
            <!-- Heroicon Map -->
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            Endereços
          </router-link>
        </li>
      </ul>
    </nav>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar -->
      <header class="h-16 bg-blue-600 text-white flex items-center justify-between px-6 shadow z-10 flex-shrink-0">
        <div class="flex items-center space-x-4">
          <!-- Hamburger icon (mobile toggle) -->
          <button @click="isSidebarOpen = true" class="md:hidden p-1 rounded-md hover:bg-blue-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
          <h1 class="text-lg font-semibold truncate">{{ pageTitle }}</h1>
        </div>
        <div class="flex items-center space-x-3">
          <div class="text-sm font-medium hidden md:block">Usuário Conectado</div>
          <div class="h-8 w-8 rounded-full bg-blue-400 flex items-center justify-center text-white font-bold">
            U
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto p-6 lg:p-8">
        <router-view></router-view>
      </main>
    </div>

    <NotificationList />
  </div>
</template>

<script>
import NotificationList from './NotificationList.vue'

export default {
  name: 'AppLayout',
  components: {
    NotificationList
  },
  data() {
    return {
      isSidebarOpen: false
    }
  },
  computed: {
    pageTitle() {
      // Basic logic to determine title based on route name
      switch(this.$route.name) {
        case 'dashboard': return 'Início'
        case 'enderecos': return 'Endereços'
        case 'pacientes': return 'Pacientes'
        default: return 'Consultório'
      }
    }
  }
}
</script>

<style scoped>
/* Tailwind classes replace all custom CSS. No scoped styles needed for layout. */
</style>
