<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import Button from '../../Components/Button.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
defineProps({
  user: {
    type: Object,
  },
})
const form = useForm({})
function resendLink() {
  form.post('/email/verification-notification', form)
}
</script>

<template>
  <Head title="Verification | SiCerdas" />
  <div
    class="px-4 lg:px-80 flex justify-center items-center flex-col gap-4 min-h-dvh">
    <img src="../../../image/logo.png" alt="logo" />
    <div
      class="p-8 rounded-md text-slate-700 max-w-max max-h-max text-xl font-medium shadow-lg bg-slate-50/80">
      <h1 class="text-2xl mb-4">
        Hello <span class="font-bold">{{ user.username }}</span>
      </h1>
      <p class="font-medium">
        Before you start this app, please verify your account first. You can
        check the verification link on your email :
        <span class="font-bold">{{ user.email }}</span
        >, If you miss the verification link, you can resend it by clicking the
        "Resend Link" button.
      </p>
      <div class="mt-4 flex justify-end items-center gap-3">
        <form @submit.prevent="router.post('/logout')">
          <button type="submit" class="underline">logout</button>
        </form>
        <form @submit.prevent="resendLink">
          <div class="flex justify-center">
            <Button
              text="resend link"
              type="submit"
              :is-disabled="form.processing" />
          </div>
        </form>
      </div>
      <div class="flex justify-center" v-if="$page.props.flash.message">
        <SuccessMessage :message="$page.props.flash.message" />
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
