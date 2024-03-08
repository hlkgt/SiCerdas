<script setup>
import { defineProps } from 'vue'

defineProps({ message: { type: String, default: 'error message' } })
</script>

<template>
  <div class="flex items-center gap-2 mt-1 mb-4">
    <img src="../../image/icons/check-icon.svg" alt="icon" />
    <p class="text-teal-600/80 font-semibold capitalize">{{ message }}</p>
  </div>
</template>
