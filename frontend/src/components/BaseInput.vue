<template>
  <div class="space-y-1">
    <label :for="id" class="block text-sm font-medium text-gray-500">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <ValidationProvider mode="eager" :vid="id" :name="name" :rules="rules" v-slot="{ errors }">
      <template v-if="mask">
        <input
          :id="id"
          :type="type"
          :value="value"
          @input="$emit('input', $event.target.value)"
          @blur="$emit('blur', $event)"
          v-mask="mask"
          :placeholder="placeholder"
          :class="[
            'border rounded px-3 py-2 mt-1 block w-full shadow-sm focus:outline-none focus:ring focus:ring-blue-200 sm:text-sm',
            errors[0] ? 'border-red-500' : 'border-gray-300'
          ]"
        >
      </template>
      <template v-else>
        <input
          :id="id"
          :type="type"
          :value="value"
          @input="$emit('input', $event.target.value)"
          @blur="$emit('blur', $event)"
          :placeholder="placeholder"
          :class="[
            'border rounded px-3 py-2 mt-1 block w-full shadow-sm focus:outline-none focus:ring focus:ring-blue-200 sm:text-sm',
            errors[0] ? 'border-red-500' : 'border-gray-300'
          ]"
        >
      </template>
      <span class="text-sm text-red-600 mt-1 block" v-if="errors[0]">{{ errors[0] }}</span>
    </ValidationProvider>
  </div>
</template>

<script>
import { ValidationProvider } from 'vee-validate'

export default {
  name: 'BaseInput',
  components: { ValidationProvider },
  props: {
    id: { type: String, required: true },
    label: { type: String, required: true },
    name: { type: String, required: true },
    value: { type: [String, Number], default: '' },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    rules: { type: [String, Object], default: '' },
    required: { type: Boolean, default: false },
    mask: { type: [String, Array], default: null }
  }
}
</script>
