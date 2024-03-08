<script setup>
import { defineProps, computed } from 'vue'
import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3'
import SuccessMessage from '../../Components/SuccessMessage.vue'
import Button from '../../Components/Button.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'

defineProps({ email: String, token: String })

const page = usePage()
const token = computed(() => page.props.token)
const email = computed(() => page.props.email)

const form = useForm({
  token: token.value,
  email: email.value,
  password: null,
  password_confirmation: null,
})
function resetPassword() {
  router.post('/reset-password', form, {
    onError: (errors) => {
      form.errors.email = errors.email
      form.errors.password = errors.password
      form.reset('password', 'password_confirmation')
    },
  })
}
</script>

<template>
  <Head title="Reset Password | SiCerdas" />
  <div
    class="px-4 lg:px-80 flex justify-center items-center flex-col gap-4 min-h-dvh bg-slate-700/80">
    <img src="../../../image/logo.png" alt="logo" />
    <div
      class="p-8 rounded-md text-slate-700 max-w-max max-h-max text-xl font-medium shadow-lg bg-slate-50/80">
      <h1
        class="text-center font-semibold capitalize text-2xl text-slate-600/80 mb-2">
        update password
      </h1>
      <p class="text-center">update your password here</p>
      <div
        class="mb-4 flex justify-center"
        v-if="form.errors.password || form.errors.email">
        <ErrorMessage :message="form.errors.password || form.errors.email" />
      </div>
      <div class="flex justify-center" v-if="$page.props.flash.message">
        <SuccessMessage :message="$page.props.flash.message" />
      </div>
      <input
        type="password"
        v-model="form.password"
        class="my-4 w-full rounded-md p-2 outline-none border-2 focus:border-primary text-base text-slate-600/80 font-bold"
        placeholder="Password" />
      <input
        type="password"
        v-model="form.password_confirmation"
        class="my-4 w-full rounded-md p-2 outline-none border-2 focus:border-primary text-base text-slate-600/80 font-bold"
        placeholder="Confirm Password" />
      <div class="flex justify-end items-center gap-2">
        <Link
          href="/auth/login"
          class="font-semibold text-slate-600/80 text-center capitalize underline mb-6 block"
          >back</Link
        >
        <form @submit.prevent="resetPassword">
          <div class="flex justify-center">
            <Button
              text="reset password"
              type="submit"
              :is-disabled="form.processing" />
          </div>
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
