<script setup>
import { computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'
import TextInput from '../../Components/TextInput.vue'
import SelectInput from '../../Components/SelectInput.vue'
import Button from '../../Components/Button.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'

const page = usePage()
const user = computed(() => page.props.user)
const classLists = computed(() => page.props.classLists)

const form = useForm({
  username: user.value.username,
  class: user.value.class_id,
  present: null,
  information: null,
})

const presentOptions = [
  {
    value: 'hadir',
    option: 'Hadir',
  },
  {
    value: 'sakit',
    option: 'Sakit',
  },
  {
    value: 'izin',
    option: 'Izin',
  },
  {
    value: 'tanpa-keterangan',
    option: 'Tanpa Keterangan',
  },
]
const classOptions = []
classLists.value.map((list) => {
  classOptions.push({ value: list.id, option: list.class })
})
</script>
<template>
  <DashboardTemplate title="Absen | SiCerdas" :active="2">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      <div class="col-span-12">
        <h1 class="text-4xl mb-6 text-slate-600">
          How are you today
          <span class="font-bold">{{ user.username }}</span
          >? Are you ready to attend the class today?
        </h1>
        <SuccessMessage
          v-if="page.props.flash.success"
          :message="page.props.flash.success" />
      </div>
      <form class="col-span-6" @submit.prevent="form.post('/absen')">
        <TextInput
          @update:value="(val) => (form.username = val)"
          label="username"
          placeholder="username"
          name="username"
          v-model:value="form.username"
          :readonly="true" />
        <SelectInput
          label="class"
          name="class"
          placeholder="Choose Class"
          @update:value="(val) => (form.class = val)"
          :selected="user.class_id"
          :message="form.errors.class"
          :options="classOptions"
          :disabled="user.class_id !== null" />
        <SelectInput
          label="Present"
          name="present"
          placeholder="Choose Present"
          :selected="user.present"
          @update:value="(val) => (form.present = val)"
          :options="presentOptions"
          :message="form.errors.present" />
        <TextInput
          @update:value="(val) => (form.date = val)"
          label="information"
          placeholder="information"
          name="information"
          type="text"
          :message="form.errors.information"
          v-model:value="form.information" />
        <Button type="submit" text="create absen" />
      </form>
    </div>
  </DashboardTemplate>
</template>
