<script setup>
import { defineProps, defineEmits } from 'vue'
import ErrorMessage from './ErrorMessage.vue'

defineProps({
  label: {
    type: String,
    default: 'label',
  },
  name: {
    type: String,
    default: 'name',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: 'name',
  },
  value: {
    type: String,
  },
  message: {
    type: String,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  selected: {
    type: String,
  },
})
const emit = defineEmits('update:value')
</script>

<template>
  <label
    :for="name"
    class="text-2xl font-bold capitalize mb-2 block"
    :class="message ? 'text-red-600/80' : 'text-slate-600'"
    >{{ label }}
  </label>
  <input
    :type="type"
    :name="name"
    :id="name"
    class="w-full p-2 border-2 rounded-md text-lg mb-4 outline-none font-semibold"
    :class="
      message
        ? 'border-red-600/80 text-red-600/80'
        : 'border-slate-600 text-slate-600'
    "
    :placeholder="placeholder"
    :value="value"
    @input="(e) => emit('update:value', e.target.value)"
    :readonly="readonly"
    autocomplete="off" />
  <ErrorMessage v-if="message" :message="message" />
</template>
