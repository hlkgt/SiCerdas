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
    <div>
      <div>
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
      <table class="min-w-full divide-y divide-gray-200 border-2">
        <thead>
          <tr>
            <th
              v-for="head in headers"
              class="px-6 py-3 bg-gray-50 text-center text-base font-bold leading-4 text-gray-500 uppercase tracking-wider">
              {{ head }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="listApproval.length === 0">
            <td
              class="px-6 py-4 whitespace-no-wrap capitalize text-center text-slate-600 text-basexl font-semibold"
              colspan="6">
              who need approval?
            </td>
          </tr>
          <tr
            v-else
            class="hover:bg-gray-300"
            v-for="(list, index) in listApproval"
            :key="index">
            <td class="px-6 py-4 whitespace-no-wrap">
              {{ index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap">
              {{ list.username }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap">
              {{ list.email }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap">
              {{ list.name }}
            </td>
            <td class="px-6 py-4 whitespace-no-wrap">
              <p class="p-2 rounded-md bg-red-500 text-center text-white">
                Need Approved
              </p>
            </td>
            <td
              class="px-6 py-4 whitespace-no-wrap flex justify-center items-center gap-2">
              <button
                :disabled="form.processing"
                :class="
                  form.processing ? 'bg-blue-300 cursor-wait' : 'bg-blue-500'
                "
                @click.prevent="handleApprove(list.id, 'rejected')"
                class="p-2 rounded-md text-white text-center capitalize">
                reject
              </button>
              <button
                :disabled="form.processing"
                :class="
                  form.processing ? 'bg-teal-300 cursor-wait' : 'bg-teal-500'
                "
                class="p-2 rounded-md bg-teal-500 text-white text-center capitalize"
                @click.prevent="handleApprove(list.id, 'approved')">
                approve
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </DashboardTemplate>
</template>
