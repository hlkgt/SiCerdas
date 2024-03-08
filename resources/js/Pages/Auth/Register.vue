<script setup>
import { defineProps } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import Button from '../../Components/Button.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'
import AuthTemplate from '../../Templates/AuthTemplate.vue'

defineProps({ headSchools: { type: Array, default: [] } })

const form = useForm({
  headschool: null,
  username: '',
  email: null,
  password: null,
  confirm_password: null,
})

function register() {
  form.post('/auth/register', form)
}
</script>

<template>
  <Head title="Register | SiCerdas" />
  <AuthTemplate>
    <form
      class="col-span-5 min-h-max bg-white p-10 rounded-xl shadow-md max-h-dvh overflow-scroll w-full"
      @submit.prevent="register">
      <h1 class="font-bold text-primary capitalize text-center text-4xl mb-2">
        register
      </h1>
      <p class="text-lg text-center font-bold text-slate-600 mb-4 capitalize">
        ready to register now? :)
      </p>
      <div class="mb-4 flex justify-center">
        <ErrorMessage
          v-if="form.errors.message"
          :message="form.errors.message" />
      </div>
      <Select
        placeholder="choose your headschool"
        :options="headSchools"
        @select-value="(val) => (form.headschool = val)"
        :message="form.errors.headschool" />
      <Input
        placeholder="username"
        type="text"
        name="username"
        :is-focus="true"
        v-model:value="form.username"
        @update:value="(val) => (form.username = val)"
        :message="form.errors.username" />
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
      <Input
        placeholder="confirm password"
        type="password"
        name="confirm password"
        :is-focus="false"
        v-model:value="form.confirm_password"
        @update:value="(val) => (form.confirm_password = val)"
        :message="form.errors.confirm_password" />
      <p class="text-center my-6 text-base font-medium">
        Already have account?
        <Link href="/auth/login" class="font-semibold text-primary underline"
          >click here</Link
        >
      </p>
      <div class="flex justify-center">
        <Button text="register" type="submit" :is-disabled="form.processing" />
      </div>
    </form>
  </AuthTemplate>
</template>
