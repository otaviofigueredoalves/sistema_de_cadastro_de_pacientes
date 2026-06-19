export default {
  namespaced: true,
  state: {
    items: []
  },
  mutations: {
    ADD_NOTIFICATION(state, notification) {
      state.items.push(notification)
    },
    REMOVE_NOTIFICATION(state, id) {
      state.items = state.items.filter(n => n.id !== id)
    }
  },
  actions: {
    show({ commit }, { message, type = 'success', duration = 3000 }) {
      const id = Date.now() + Math.random().toString(36).substr(2, 9)
      commit('ADD_NOTIFICATION', { id, message, type })

      if (duration > 0) {
        setTimeout(() => {
          commit('REMOVE_NOTIFICATION', id)
        }, duration)
      }
    },
    remove({ commit }, id) {
      commit('REMOVE_NOTIFICATION', id)
    }
  }
}
