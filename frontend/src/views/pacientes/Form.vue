<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">{{ isEditing ? 'Editar Paciente' : 'Novo Paciente' }}</h1>
      <router-link to="/pacientes" class="text-blue-600 hover:underline">Voltar</router-link>
    </div>

    <ValidationObserver ref="form">
      <form @submit.prevent="onSubmit" class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
        
        <!-- Nome -->
        <BaseInput
          id="name"
          name="Nome"
          label="Nome Completo"
          v-model="form.name"
          rules="required"
        />

        <div class="grid grid-cols-2 gap-4">
          <!-- CPF -->
          <BaseInput
            id="cpf"
            name="CPF"
            label="CPF"
            v-model="form.cpf"
            rules="required|cpf"
            mask="###.###.###-##"
          />

          <!-- CNS -->
          <BaseInput
            id="cns"
            name="CNS"
            label="CNS"
            v-model="form.cns"
            rules="required|cns"
            mask="### #### #### ####"
          />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <!-- Data de Nascimento -->
          <BaseInput
            id="birth_date"
            name="Data de Nascimento"
            label="Data de Nascimento"
            type="date"
            v-model="form.birth_date"
            rules="required|past_date"
          />

          <!-- Gênero -->
          <ValidationProvider vid="gender" rules="required" name="Gênero" v-slot="{ errors }">
            <div class="flex flex-col">
              <label class="font-medium text-sm text-gray-500">Gênero</label>
              <select 
                v-model="form.gender" 
                class="border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-200 sm:text-sm"
                :class="{'border-red-500': errors[0]}"
              >
                <option value="" disabled>Selecione</option>
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
                <option value="O">Outro</option>
              </select>
              <span class="text-red-500 text-sm mt-1" v-if="errors[0]">{{ errors[0] }}</span>
            </div>
          </ValidationProvider>
        </div>

        <!-- Telefone -->
        <BaseInput
          id="phone"
          name="Telefone"
          label="Telefone"
          v-model="form.phone"
          :mask="['(##) ####-####', '(##) #####-####']"
        />

        <!-- Endereço -->
        <ValidationProvider vid="address_id" rules="required" name="Endereço" v-slot="{ errors }">
          <div class="flex flex-col">
            <label class="font-medium text-sm text-gray-500">Endereço de Vínculo</label>
            <select 
              v-model="form.address_id" 
              class="border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-200 sm:text-sm"
              :class="{'border-red-500': errors[0]}"
            >
              <option value="" disabled>Selecione um Endereço</option>
              <option v-for="addr in availableAddresses" :key="addr.id" :value="addr.id">
                {{ addr.street }} - {{ addr.city }}/{{ addr.state }} ({{ addr.zip_code }})
              </option>
            </select>
            <span class="text-red-500 text-sm mt-1" v-if="errors[0]">{{ errors[0] }}</span>
          </div>
        </ValidationProvider>

        <div class="pt-4 flex justify-end">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition" :disabled="saving">
            {{ saving ? 'Salvando...' : 'Salvar' }}
          </button>
        </div>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import patientService from '../../services/patient.service'
import addressService from '../../services/address.service'
import BaseInput from '../../components/BaseInput.vue'

export default {
  name: 'PacientesForm',
  components: { BaseInput },
  data() {
    return {
      form: {
        name: '',
        cpf: '',
        cns: '',
        birth_date: '',
        gender: '',
        phone: '',
        address_id: ''
      },
      availableAddresses: [],
      isEditing: false,
      saving: false
    }
  },
  async created() {
    await this.loadAddresses()
    
    const id = this.$route.params.id
    if (id) {
      this.isEditing = true
      await this.loadPatient(id)
    }
  },
  methods: {
    async loadAddresses() {
      try {
        // Pega todos os endereços para o dropdown (max 100 por ex, ou busca dinâmica)
        const response = await addressService.getAll({ per_page: 100 })
        this.availableAddresses = response.data.data
      } catch (error) {
        console.error('Erro ao carregar endereços', error)
      }
    },
    async loadPatient(id) {
      try {
        const response = await patientService.getById(id)
        this.form = response.data
        // Format to mask manually if required, but vue-the-mask handles models well
      } catch (error) {
        alert('Erro ao carregar paciente.')
        this.$router.push('/pacientes')
      }
    },
    async onSubmit() {
      const isValid = await this.$refs.form.validate()
      if (!isValid) {
        this.$store.dispatch('notifications/show', { message: 'Por favor, corrija os campos destacados em vermelho.', type: 'warning' })
        return
      }

      this.saving = true
      
      const payload = { ...this.form }
      payload.cpf = payload.cpf ? payload.cpf.replace(/\D/g, '') : null
      payload.cns = payload.cns ? payload.cns.replace(/\D/g, '') : null
      payload.phone = payload.phone ? payload.phone.replace(/\D/g, '') : null

      try {
        if (this.isEditing) {
          await patientService.update(this.form.id, payload)
          this.$store.dispatch('notifications/show', { message: 'Paciente atualizado com sucesso!' })
        } else {
          await patientService.create(payload)
          this.$store.dispatch('notifications/show', { message: 'Paciente criado com sucesso!' })
        }
        this.$router.push('/pacientes')
      } catch (error) {
        if (error.response?.status === 422) {
          const errors = error.response.data.errors
          this.$refs.form.setErrors(errors)
          this.$store.dispatch('notifications/show', { message: 'Verifique os erros no formulário.', type: 'warning' })
        } else {
          this.$store.dispatch('notifications/show', { message: error.message || 'Ocorreu um erro inesperado ao salvar.', type: 'error' })
        }
      } finally {
        this.saving = false
      }
    }
  }
}
</script>
