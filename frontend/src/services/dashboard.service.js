import api from './api'

export default {
  getTotals() {
    return api.get('/dashboard')
  }
}
