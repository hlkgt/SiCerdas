<script setup>
import { defineProps, computed, ref } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import ErrorMessage from '../../Components/ErrorMessage.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
defineProps({
  user: {
    type: Object,
  },
})

const page = usePage()
const user = computed(() => page.props.user)
const message = ref(null)
let type = ref(false)
const showAlertMessage = ref(0)

Echo.channel('approval-processed').listen(
  'ApproveProcessed',
  ({ userId, status }) => {
    if (user.value.id === userId && status === 'rejected') {
      setApproval('your account rejected,please check your email', 0)
      setTimeout(() => {
        window.location.reload()
      }, 3000)
    } else if (user.value.id === userId && status === 'approved') {
      setApproval('your account approved,please wait to redirect', 1)
      setTimeout(() => {
        window.location.href = '/email/verify'
      }, 3000)
    }
  }
)

function setApproval(setMessage, status) {
  showAlertMessage.value = 1
  status && (type.value = true)
  message.value = setMessage
}
</script>

<template>
  <Head title="Approve | SiCerdas" />
  <div
    class="px-4 lg:px-80 flex justify-center items-center flex-col gap-4 min-h-dvh">
    <img src="../../../image/logo.png" alt="logo" />
    <div
      class="p-8 rounded-md text-slate-700 max-w-c600 max-h-max text-xl font-medium shadow-lg bg-slate-50/80">
      <h1 class="text-center font-bold text-3xl mb-6 capitalize">
        waiting approval
      </h1>
      <div class="flex justify-center items-center" v-if="showAlertMessage">
        <SuccessMessage :message="message" v-if="type" />
        <ErrorMessage :message="message" v-else />
      </div>
      <h1 class="text-2xl mb-4">
        Hello <span class="font-bold">{{ user.username }}</span>
      </h1>
      <p class="font-medium">
        Please wait for your school principal to approve your account, to find
        out whether you are a student from this school or not, if within 1 hours
        you are still waiting for approval, please contact your school
        principal!
      </p>
      <div class="flex justify-center items-center pt-4">
        <form @submit.prevent="router.post('/logout')">
          <button type="submit" class="underline">logout</button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.min-h-dvh {
  background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.8)),
    url('../../../image/bg-register.jpg');
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  object-fit: cover;
}
</style>
