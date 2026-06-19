import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})

// Add an interceptor to handle unprocessable entity specifically, if needed
api.interceptors.response.use(
  response => response,
  error => {
    return Promise.reject(error)
  }
)

export default api
