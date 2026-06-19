import patientService from '../../services/patient.service'

export default {
  namespaced: true,
  state: {
    items: [],
    pagination: {
      currentPage: 1,
      lastPage: 1,
      total: 0,
      perPage: 15
    },
    loading: false,
    error: null
  },
  mutations: {
    SET_ITEMS(state, items) {
      state.items = items
    },
    SET_PAGINATION(state, pagination) {
      state.pagination = pagination
    },
    SET_LOADING(state, loading) {
      state.loading = loading
    },
    SET_ERROR(state, error) {
      state.error = error
    }
  },
  actions: {
    async fetchPatients({ commit }, params = {}) {
      commit('SET_LOADING', true)
      commit('SET_ERROR', null)
      try {
        const response = await patientService.getAll(params)
        commit('SET_ITEMS', response.data.data)
        commit('SET_PAGINATION', {
          currentPage: response.data.current_page,
          lastPage: response.data.last_page,
          total: response.data.total,
          perPage: response.data.per_page
        })
      } catch (error) {
        commit('SET_ERROR', error.response?.data?.message || 'Erro ao carregar pacientes.')
      } finally {
        commit('SET_LOADING', false)
      }
    },
    async deletePatient({ commit }, id) {
      try {
        await patientService.delete(id)
        return true
      } catch (error) {
        throw error
      }
    }
  }
}
