<template>
  <div class="form-group">
    <label v-if="label" class="form-label" :for="id">{{ label }}</label>
    <select
      :id="id"
      class="form-control"
      :class="{ 'has-error': error }"
      :value="value"
      @change="$emit('input', $event.target.value)"
      v-bind="$attrs"
    >
      <option value="" disabled>{{ placeholder }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <span v-if="error" class="error-text">{{ error }}</span>
  </div>
</template>

<script>
export default {
  name: 'BaseSelect',
  inheritAttrs: false,
  props: {
    id: { type: String, required: true },
    label: { type: String, default: '' },
    value: { type: [String, Number], default: '' },
    options: { type: Array, required: true },
    placeholder: { type: String, default: 'Selecione...' },
    error: { type: String, default: '' }
  }
}
</script>
