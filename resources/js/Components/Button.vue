<script setup>
import { defineProps } from 'vue'
defineProps({
  type: { type: String, default: 'button' },
  text: { type: String, default: 'button' },
  isDisabled: { type: Boolean, default: false },
})
</script>

<template>
  <button
    :type="type"
    :disabled="isDisabled"
    :class="isDisabled ? 'bg-primary/40 cursor-not-allowed' : 'bg-primary'"
    class="px-6 py-2 rounded-lg font-bold text-xl capitalize text-white w-full">
    {{ text }}
  </button>
</template>
