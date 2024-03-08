<script setup>
import { defineProps } from 'vue'
import ErrorMessage from './ErrorMessage.vue'

defineProps({
  isFocus: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: 'placeholder',
  },
  type: {
    type: String,
    default: 'text',
  },
  name: {
    type: String,
    default: 'name',
  },
  value: {
    type: String,
    default: 'value',
  },
  message: {
    type: String,
  },
})
</script>

<template>
  <label
    :for="name"
    class="mb-2 capitalize text-xl text-primary font-bold"
    :class="message && 'text-red-600/80'"
    >{{ placeholder }}</label
  >
  <input
    :type="type"
    :value="value"
    @input="(e) => $emit('update:value', e.target.value)"
    :placeholder="placeholder"
    :autofocus="isFocus"
    class="text-base w-full px-1 py-2 outline-none font-bold focus:text-primary focus:border-primary border-b-2"
    :class="
      message
        ? 'text-red-600/80 border-red-600/80 focus:text-red-600/80 focus:border-red-600/80 mb-0'
        : 'border-slate-600/80 text-slate-600/80 mb-4'
    " />
  <ErrorMessage v-if="message" :message="message" />
</template>
