<script setup>
import { router } from '@inertiajs/vue3'
</script>

<template>
  <h1 class="text-4xl">Hello World</h1>
  <form @submit.prevent="router.post('/logout')">
    <button type="submit">logout</button>
  </form>
</template>
