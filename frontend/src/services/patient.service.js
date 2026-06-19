import api from './api'

export default {
  getAll(params = {}) {
    return api.get('/pacientes', { params })
  },
  getById(id) {
    return api.get(`/pacientes/${id}`)
  },
  create(data) {
    return api.post('/pacientes', data)
  },
  update(id, data) {
    return api.put(`/pacientes/${id}`, data)
  },
  delete(id) {
    return api.delete(`/pacientes/${id}`)
  }
}
