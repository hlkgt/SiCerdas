<script setup>
import { defineProps } from 'vue'

defineProps({ message: { type: String, default: 'error message' } })
</script>

<template>
  <div class="flex items-center gap-2 mt-1 mb-4">
    <img src="../../image/icons/danger-icon.svg" alt="icon" />
    <p class="text-red-600/80 font-semibold">{{ message }}</p>
  </div>
</template>
