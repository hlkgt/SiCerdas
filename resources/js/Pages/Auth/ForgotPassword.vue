<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import SuccessMessage from '../../Components/SuccessMessage.vue'
import Button from '../../Components/Button.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'

const form = useForm({
  email: null,
})
function resetPassword() {
  form.post('/forgot-password', form)
  console.log(form)
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
        forgot password
      </h1>
      <div class="mb-4 flex justify-center" v-if="form.errors.email">
        <ErrorMessage :message="form.errors.email" />
      </div>
      <div class="flex justify-center" v-if="$page.props.flash.message">
        <SuccessMessage :message="$page.props.flash.message" />
      </div>
      <p>
        Forgot password? Type your email bellow and you will send the reset
        password link by clicking the "Reset Password" button.
      </p>
      <input
        type="email"
        v-model="form.email"
        class="my-4 w-full rounded-md p-2 outline-none border-2 focus:border-primary text-base text-slate-600/80 font-bold"
        placeholder="e.g : your_email@example.com" />
      <div class="flex justify-end items-center gap-2">
        <Link
          href="/auth/login"
          class="font-semibold text-slate-600/80 text-center capitalize underline mb-6 block"
          >back</Link
        >
        <form @submit.prevent="resetPassword">
          <div class="flex justify-center">
            <Button
              text="send reset link"
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
