<script setup>
import { defineProps } from 'vue'
import ErrorMessage from './ErrorMessage.vue'
defineProps({
  options: Array,
  placeholder: {
    type: String,
    default: 'select',
  },
  message: {
    type: String,
  },
})
</script>

<template>
  <select
    :class="message ? 'text-red-600/80 border-red-600/80 mb-0' : 'mb-4'"
    class="w-full p-4 border-2 border-primary rounded-md bg-transparent text-slate-600/80 font-bold text-base capitalize"
    @change="(e) => $emit('selectValue', e.target.value)">
    <option hidden selected>{{ placeholder }}</option>
    <option v-for="(option, index) in options" :key="index" :value="option.id">
      {{ option.name }}
    </option>
  </select>
  <ErrorMessage v-if="message" :message="message" />
</template>
