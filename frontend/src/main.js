import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/index.css'

import VueTheMask from 'vue-the-mask'
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'
import { required, email, length, min, max, numeric } from 'vee-validate/dist/rules'
import { validateCpf, validateCns, validatePastDate } from './utils/validators'

// Instalar VueTheMask
Vue.use(VueTheMask)

// Configurar regras do VeeValidate
extend('required', { ...required, message: 'Este campo é obrigatório.' })
extend('email', { ...email, message: 'O e-mail deve ser válido.' })
extend('length', { ...length, message: 'Tamanho incorreto.' })
extend('min', { ...min, message: 'Valor muito curto.' })
extend('max', { ...max, message: 'Valor muito longo.' })
extend('numeric', { ...numeric, message: 'Deve conter apenas números.' })

extend('cpf', {
  validate: value => validateCpf(value),
  message: 'CPF inválido.'
})

extend('cns', {
  validate: value => validateCns(value),
  message: 'CNS inválido.'
})

extend('past_date', {
  validate: value => validatePastDate(value),
  message: 'A data não pode ser no futuro.'
})

// Registrar componentes globalmente
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
