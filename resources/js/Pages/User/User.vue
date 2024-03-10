<script setup>
import { computed, defineProps } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'
import TextInput from '../../Components/TextInput.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'
import SelectInput from '../../Components/SelectInput.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
import Button from '../../Components/Button.vue'

defineProps({ classLists: Array })

const page = usePage()
const user = computed(() => page.props.user)
const classLists = computed(() => page.props.classLists)

const form = useForm({
  username: user.value.username,
  class: user.value.class_id,
  gender: user.value.gender,
  date: user.value.date_birth,
})

const classOptions = []
const genderOptions = [
  {
    value: 'laki-laki',
    option: 'Laki-Laki',
  },
  {
    value: 'perempuan',
    option: 'Perempuan',
  },
]

classLists.value.map((list) => {
  classOptions.push({ value: list.id, option: list.class })
})
</script>

<template>
  <DashboardTemplate :active="1">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      <div class="col-span-12">
        <h1 class="text-4xl mb-6 text-slate-600">
          Profile From
          <span class="font-bold">{{ user.username }}</span>
        </h1>
        <ErrorMessage
          v-if="page.props.flash.message"
          :message="page.props.flash.message" />
        <SuccessMessage
          v-if="page.props.flash.success"
          :message="page.props.flash.success" />
      </div>
      <form class="col-span-6" @submit.prevent="form.post('/profile', form)">
        <TextInput
          @update:value="(val) => (form.username = val)"
          label="username"
          placeholder="username"
          name="username"
          :message="form.errors.username"
          v-model:value="form.username" />
        <SelectInput
          label="class"
          name="class"
          placeholder="Choose Class"
          @update:value="(val) => (form.class = val)"
          :selected="user.class_id"
          :message="form.errors.class"
          :options="classOptions"
          :disabled="user.class_id !== null" />
        <div v-if="!form.errors.class">
          <ErrorMessage
            v-if="user.class_id === null"
            message="class can only changed once" />
          <SuccessMessage v-else message="already attending in class" />
        </div>
        <SelectInput
          label="gender"
          name="gender"
          placeholder="Choose Gender"
          :selected="user.gender"
          @update:value="(val) => (form.gender = val)"
          :options="genderOptions"
          :message="form.errors.gender" />
        <TextInput
          @update:value="(val) => (form.date = val)"
          label="Datebirth"
          placeholder="date"
          name="date"
          type="date"
          :message="form.errors.date"
          v-model:value="form.date" />
        <Button type="submit" text="update profile" />
      </form>
    </div>
  </DashboardTemplate>
</template>
