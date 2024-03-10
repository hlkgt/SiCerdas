<script setup>
import { defineProps, defineEmits } from 'vue'
import ErrorMessage from './ErrorMessage.vue'
defineProps({
  options: Array,
  label: { type: String, default: 'label' },
  name: { type: String, default: 'select' },
  placeholder: { type: String, default: 'choose your option' },
  message: String,
  selected: String,
  disabled: Boolean,
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
  <select
    :name="name"
    :id="name"
    @change="(e) => emit('update:value', e.target.value)"
    class="w-full py-4 px-2 rounded-md bg-transparent border-2 mb-4 font-semibold"
    :class="
      message
        ? 'border-red-600/80 text-red-600/80'
        : 'border-slate-600 text-slate-600'
    "
    :disabled="disabled">
    <option hidden selected>{{ placeholder }}</option>
    <option
      v-for="(list, index) in options"
      :key="index"
      :value="list.value"
      :selected="selected === list.value">
      {{ list.option }}
    </option>
  </select>
  <ErrorMessage v-if="message" :message="message" />
</template>
