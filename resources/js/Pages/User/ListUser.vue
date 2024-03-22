<script setup>
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
defineProps({ users: Array })

const form = useForm({
  id: null,
})

function handleClick(id) {
  form.id = id
  form.post('/user-management', form)
}
</script>

<template>
  <DashboardTemplate :active="3">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-2">
      <div class="col-span-1 lg:col-span-12">
        <h1 class="text-4xl mb-6 text-slate-600 font-semibold">List User</h1>
      </div>
      <div
        class="col-span-1 lg:col-span-12 flex justify-center"
        v-if="$page.props.flash.success">
        <SuccessMessage :message="$page.props.flash.success" />
      </div>
      <div
        class="col-span-4 rounded-md shadow-md p-4 relative"
        :class="user.banned && 'bg-slate-300/40'"
        v-for="(user, index) in users"
        :key="index">
        <span
          v-if="!user.banned"
          class="absolute top-4 right-4 rounded-full w-4 h-4"
          :class="user.approved ? 'bg-teal-600' : 'bg-red-600'"></span>
        <h1 class="text-center text-2xl text-slate-600 font-semibold mb-2">
          {{ user.username }}
        </h1>
        <p class="text-base mb-4">{{ user.email }}</p>
        <button
          v-if="!user.banned"
          :disabled="form.processing"
          @click="handleClick(user.id)"
          class="w-full text-center py-2 bg-primary rounded-md text-base font-semibold text-white">
          banned
        </button>
        <h1
          v-if="user.banned"
          class="uppercase text-4xl font-semibold border-2 max-w-max p-1 border-red-600 bg-transparent rounded-sm text-red-600 absolute top-10 left-28 rotate-6">
          banned
        </h1>
      </div>
    </div>
  </DashboardTemplate>
</template>
