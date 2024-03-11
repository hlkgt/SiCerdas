<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'

const page = usePage()
const user = computed(() => page.props.user)
</script>
<template>
  <DashboardTemplate title="Home | SiCerdas" :active="1">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      <h1 class="col-span-1 lg:col-span-12 text-4xl text-slate-600">
        Hai <span class="font-bold">{{ user.username }}</span>
      </h1>
    </div>
  </DashboardTemplate>
</template>
