<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-x-auto">
    <table class="min-w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100 text-sm font-semibold text-gray-800">
          <th 
            v-for="column in columns" 
            :key="column.key" 
            class="p-4 font-medium"
            :class="{ 'cursor-pointer hover:bg-gray-100': column.sortable }"
            @click="column.sortable ? $emit('sort', column.key) : null"
          >
            <div class="flex items-center gap-1">
              {{ column.label }}
              <span v-if="column.sortable && sortKey === column.key">
                <svg v-if="sortDir === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </span>
            </div>
          </th>
          <th v-if="hasActions" class="p-4 font-medium">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in data" :key="item.id" class="border-b border-gray-100 hover:bg-gray-50">
          <td v-for="column in columns" :key="column.key" class="p-4" :class="column.class">
            <slot :name="`cell-${column.key}`" :item="item">
              {{ format(item, column) }}
            </slot>
          </td>
          <td v-if="hasActions" class="p-4 flex gap-2">
            <slot name="actions" :item="item">
              <button class="text-blue-600 hover:underline" @click="$emit('edit', item)">Editar</button>
              <button class="text-red-600 hover:underline" @click="$emit('delete', item)">Excluir</button>
            </slot>
          </td>
        </tr>
        <tr v-if="data.length === 0">
          <td :colspan="columns.length + (hasActions ? 1 : 0)" class="p-4 text-center text-gray-500">
            Nenhum registro encontrado.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'BaseTable',
  props: {
    columns: { type: Array, required: true },
    data: { type: Array, required: true },
    hasActions: { type: Boolean, default: true },
    sortKey: { type: String, default: '' },
    sortDir: { type: String, default: 'asc' }
  },
  methods: {
    format(item, column) {
      if (column.format) {
        return column.format(item[column.key], item)
      }
      return item[column.key]
    }
  }
}
</script>
