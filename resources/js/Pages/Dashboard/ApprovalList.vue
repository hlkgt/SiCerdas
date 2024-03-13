<script setup>
import { defineProps } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'

defineProps({
  listApproval: Array,
})

const form = useForm({
  userId: null,
  type: null,
})

const headers = ['ID', 'username', 'email', 'school', 'status', 'action']

function handleApprove(userId, type) {
  form.userId = userId
  form.type = type
  form.post('/list-approval')
}
</script>

<template>
  <DashboardTemplate title="Absen | SiCerdas" :active="5">
    <div class="grid grid-cols-12 gap-4">
      <div class="col-span-12">
        <h1 class="text-4xl mb-6 text-slate-600 font-semibold">
          List Student Need Approval
        </h1>
        <div class="mb-4 flex justify-center">
          <SuccessMessage
            v-if="$page.props.flash.success"
            :message="$page.props.flash.success" />
          <ErrorMessage
            v-if="$page.props.flash.message"
            :message="$page.props.flash.message" />
        </div>
      </div>
      <div
        class="col-span-12 md:col-span-6 lg:col-span-4 shadow-md p-4 rounded-md"
        v-for="(list, index) in listApproval"
        :key="index">
        <h1 class="text-center text-2xl font-semibold mb-2">
          {{ list.username }}
        </h1>
        <p class="text-base mb-4">
          <span class="text-lg font-semibold w-full block capitalize"
            >hai principle</span
          >
          i'am
          <span class="text-lg font-semibold">{{ list.username }}</span
          >, please approve my account with email
          <span class="text-lg font-semibold">{{ list.email }}</span> to join to
          your school, and start in the class
        </p>
        <div class="flex gap-2 justify-center items-center">
          <button
            :disabled="form.processing"
            :class="form.processing ? 'bg-rose-300 cursor-wait' : 'bg-rose-500'"
            @click.prevent="handleApprove(list.id, 'rejected')"
            class="p-2 rounded-md text-white text-center capitalize">
            reject
          </button>
          <button
            :disabled="form.processing"
            :class="form.processing ? 'bg-teal-300 cursor-wait' : 'bg-teal-500'"
            class="p-2 rounded-md bg-teal-500 text-white text-center capitalize"
            @click.prevent="handleApprove(list.id, 'approved')">
            approve
          </button>
        </div>
      </div>
    </div>
  </DashboardTemplate>
</template>
