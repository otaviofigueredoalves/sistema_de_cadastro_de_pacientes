<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">{{ isEditing ? 'Editar Endereço' : 'Novo Endereço' }}</h1>
      <router-link to="/enderecos" class="text-blue-600 hover:underline">Voltar</router-link>
    </div>

    <ValidationObserver ref="form" v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(onSubmit)" class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
               <!-- CEP -->
        <BaseInput
          id="zip_code"
          name="CEP"
          label="CEP"
          v-model="form.zip_code"
          rules="required|length:9"
          mask="#####-###"
          @blur="searchCep"
        />

        <!-- Logradouro -->
        <BaseInput
          id="street"
          name="Logradouro"
          label="Logradouro / Rua"
          v-model="form.street"
          rules="required"
        />

        <!-- Bairro -->
        <BaseInput
          id="neighborhood"
          name="Bairro"
          label="Bairro"
          v-model="form.neighborhood"
          rules="required"
        />

        <div class="grid grid-cols-2 gap-4">
          <!-- Cidade -->
          <BaseInput
            id="city"
            name="Cidade"
            label="Cidade"
            v-model="form.city"
            rules="required"
          />

          <!-- UF -->
          <BaseInput
            id="state"
            name="Estado (UF)"
            label="Estado (UF)"
            v-model="form.state"
            rules="required|length:2"
            maxlength="2"
            class="uppercase"
          />
        </div>

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
import addressService from '../../services/address.service'
import axios from 'axios'
import BaseInput from '../../components/BaseInput.vue'

export default {
  name: 'EnderecosForm',
  components: { BaseInput },
  data() {
    return {
      form: {
        zip_code: '',
        street: '',
        neighborhood: '',
        city: '',
        state: ''
      },
      isEditing: false,
      saving: false
    }
  },
  async created() {
    const id = this.$route.params.id
    if (id) {
      this.isEditing = true
      await this.loadAddress(id)
    }
  },
  methods: {
    async loadAddress(id) {
      try {
        const response = await addressService.getById(id)
        this.form = response.data
      } catch (error) {
        alert('Erro ao carregar endereço.')
        this.$router.push('/enderecos')
      }
    },
    async searchCep() {
      const cep = this.form.zip_code.replace(/\D/g, '')
      if (cep.length === 8) {
        try {
          const res = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)
          if (!res.data.erro) {
            this.form.street = res.data.logradouro
            this.form.neighborhood = res.data.bairro
            this.form.city = res.data.localidade
            this.form.state = res.data.uf
          }
        } catch (error) {
          console.error("ViaCEP indisponível")
        }
      }
    },
    async onSubmit() {
      this.saving = true
      
      // Clean payload
      const payload = { ...this.form }
      payload.zip_code = payload.zip_code.replace(/\D/g, '')

      try {
        if (this.isEditing) {
          await addressService.update(this.form.id, payload)
          this.$store.dispatch('notifications/show', { message: 'Endereço atualizado com sucesso!' })
        } else {
          await addressService.create(payload)
          this.$store.dispatch('notifications/show', { message: 'Endereço criado com sucesso!' })
        }
        this.$router.push('/enderecos')
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
