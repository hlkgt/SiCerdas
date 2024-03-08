<script setup>
import { ref, defineProps } from 'vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import Input from '../../Components/Input.vue'
import Button from '../../Components/Button.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'
import AuthTemplate from '../../Templates/AuthTemplate.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'

defineProps({ headSchools: { type: Array, default: [] } })

const form = useForm({
  email: null,
  password: null,
  remember_me: false,
})

const isDisable = ref(false)

function login() {
  isDisable.value = true
  router.post('/auth/login', form, {
    onError: (errors) => {
      form.errors.email = errors.email
      form.reset()
    },
  })
}
</script>

<template>
  <Head title="Login | SiCerdas" />
  <AuthTemplate>
    <form
      class="col-span-5 min-h-max bg-white p-10 rounded-xl shadow-md max-h-dvh overflow-scroll w-full"
      @submit.prevent="login">
      <h1 class="font-bold text-primary capitalize text-center text-4xl mb-2">
        login
      </h1>
      <p class="text-lg text-center font-bold text-slate-600 mb-4 capitalize">
        Ready to back to school? :)
      </p>
      <div class="mb-4 flex justify-center">
        <ErrorMessage
          v-if="form.errors.message"
          :message="form.errors.message" />
      </div>
      <div class="mb-4 flex justify-center">
        <SuccessMessage
          v-if="$page.props.flash.message"
          :message="$page.props.flash.message" />
      </div>
      <Input
        placeholder="email"
        type="email"
        name="email"
        :is-focus="false"
        v-model:value="form.email"
        @update:value="(val) => (form.email = val)"
        :message="form.errors.email" />
      <Input
        placeholder="password"
        type="password"
        name="password"
        :is-focus="false"
        v-model:value="form.password"
        @update:value="(val) => (form.password = val)"
        :message="form.errors.password" />
      <div class="flex items-center gap-2">
        <input
          type="checkbox"
          id="remember_me"
          name="remember_me"
          v-model="form.remember_me" />
        <label for="remember_me">remember me</label>
      </div>
      <p class="text-center mt-6 mb-2 text-base font-medium">
        Dont have account?
        <Link href="/auth/register" class="font-semibold text-primary underline"
          >click here</Link
        >
      </p>
      <div class="flex justify-center">
        <Link
          href="/forgot-password"
          class="font-semibold text-primary capitalize underline mb-6 block max-w-max"
          >forgot password!</Link
        >
      </div>
      <div class="flex justify-center">
        <Button text="Login" type="submit" :is-disable="isDisable" />
      </div>
    </form>
  </AuthTemplate>
</template>
