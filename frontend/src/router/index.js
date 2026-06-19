import Vue from 'vue'
import VueRouter from 'vue-router'

import AppLayout from '../components/AppLayout.vue'

// Views
import Dashboard from '../views/Dashboard.vue'
import AddressIndex from '../views/enderecos/Index.vue'
import AddressForm from '../views/enderecos/Form.vue'
import PatientIndex from '../views/pacientes/Index.vue'
import PatientForm from '../views/pacientes/Form.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: AppLayout,
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: Dashboard
      },
      {
        path: 'enderecos',
        name: 'AddressIndex',
        component: AddressIndex
      },
      {
        path: 'enderecos/novo',
        name: 'AddressCreate',
        component: AddressForm
      },
      {
        path: 'enderecos/:id',
        name: 'AddressEdit',
        component: AddressForm
      },
      {
        path: 'pacientes',
        name: 'PatientIndex',
        component: PatientIndex
      },
      {
        path: 'pacientes/novo',
        name: 'PatientCreate',
        component: PatientForm
      },
      {
        path: 'pacientes/:id',
        name: 'PatientEdit',
        component: PatientForm
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.BASE_URL,
  routes
})

export default router
