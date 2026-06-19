import api from './api'

export default {
  getAll(params = {}) {
    return api.get('/enderecos', { params })
  },
  getById(id) {
    return api.get(`/enderecos/${id}`)
  },
  create(data) {
    return api.post('/enderecos', data)
  },
  update(id, data) {
    return api.put(`/enderecos/${id}`, data)
  },
  delete(id) {
    return api.delete(`/enderecos/${id}`)
  }
}
