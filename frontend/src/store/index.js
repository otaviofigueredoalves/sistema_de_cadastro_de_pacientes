import Vue from 'vue'
import Vuex from 'vuex'
import addresses from './modules/addresses'
import patients from './modules/patients'
import notifications from './modules/notifications'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    addresses,
    patients,
    notifications
  }
})
