<script setup>
import { computed, ref } from 'vue'
import { usePage, router, useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'
import SuccessMessage from '../../Components/SuccessMessage.vue'
import ErrorMessage from '../../Components/ErrorMessage.vue'

const page = usePage()

const tokens = computed(() => page.props.tokens)
const users = computed(() => page.props.users)
const message = computed(() => page.props.flash.message)
const error = ref(null)
const isShowModal = ref(false)
const isInProggress = ref(false)

Echo.channel('register-event').listen('RegisterEvent', (email) => {
  users.value.push(email)
})
Echo.channel('usage-token').listen('UsageTokenEvent', ({ token, usage }) => {
  tokens.value.forEach((element, index) => {
    if (element.token === token) {
      tokens.value[index].usage = usage
    }
  })
})

const form = useForm({
  token: null,
  email: null,
})

function generateToken() {
  router.post('/generate-token')
}
function showModal(token) {
  isShowModal.value = !isShowModal.value
  form.token = token
}
function shareToken() {
  isInProggress.value = true
  router.post('/share-token', form, {
    onSuccess: () => {
      isInProggress.value = !isInProggress
      isShowModal.value = !isShowModal
      form.reset()
    },
    onError: (err) => {
      error.value = err.error
    },
  })
}
</script>
<template>
  <div
    class="w-full min-h-dvh fixed bg-white/60 z-[100] flex justify-center items-center"
    v-if="isShowModal">
    <div class="p-4 shadow-md rounded-md relative bg-white">
      <button
        @click="isShowModal = !isShowModal"
        class="p-2 bg-primary rounded-md absolute top-2 right-2">
        <img src="../../../image/icons/close-icon.svg" alt="icon" />
      </button>
      <h1
        class="text-xl font-semibold text-slate-600 mb-4 capitalize text-center">
        choice email to given this token
      </h1>
      <form @submit.prevent="shareToken">
        <input
          type="text"
          v-model="form.token"
          class="px-2 py-3 rounded-md w-full outline-none bg-slate-200 mb-2"
          placeholder="token"
          disabled />
        <select
          name="email"
          id="email"
          @change="(e) => (form.email = e.target.value)"
          class="w-full px-2 py-3 rounded-md bg-white border-2 border-primary text-lg mb-2">
          <option hidden>Choice Email</option>
          <option
            v-for="(user, index) in users"
            :key="index"
            :value="user.email">
            {{ user.email }}
          </option>
        </select>
        <ErrorMessage :message="error" v-if="error" />
        <button
          type="submit"
          :disabled="isInProggress"
          :class="isInProggress ? 'bg-primary/60 cursor-wait' : 'bg-primary'"
          class="w-full py-3 capitalize rounded-md font-semibold text-white">
          share token
        </button>
      </form>
    </div>
  </div>

  <DashboardTemplate title="Home | SiCerdas" :active="7">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <div class="col-span-1 lg:col-span-12 flex items-center gap-8">
        <h1 class="text-4xl text-slate-600 font-bold">List Token</h1>
        <button
          class="text-xl font-bold text-white p-2 rounded-md bg-primary capitalize"
          @click.prevent="generateToken">
          + new token
        </button>
      </div>
      <div
        class="col-span-1 lg:col-span-12 flex justify-center items-center"
        v-if="message">
        <SuccessMessage :message="message" />
      </div>
      <div
        class="col-span-1 lg:col-span-12 text-center"
        v-if="tokens.length === 0">
        <h1 class="text-xl text-slate-600 capitalize font-semibold">
          token not yet
        </h1>
      </div>
      <div
        v-for="(token, index) in tokens"
        :key="index"
        class="col-span-1 lg:col-span-4 flex flex-col justify-center items-center gap-2 p-4 shadow-md rounded-md">
        <h1 class="text-base font-semibold">{{ token.token }}</h1>
        <p>usage : {{ token.usage }}</p>
        <p>max usage : {{ token.max_usage }}</p>
        <button
          v-if="token.usage !== token.max_usage"
          @click.prevent="showModal(token.token)"
          class="p-2 bg-primary rounded-md font-bold text-white flex items-center justify-center gap-2">
          <p class="text-lg uppercase">share</p>
          <img src="../../../image/icons/share-icon.svg" alt="icon" />
        </button>
        <p v-else class="p-2 text-rose-600 font-bold">expired</p>
      </div>
    </div>
  </DashboardTemplate>
</template>
