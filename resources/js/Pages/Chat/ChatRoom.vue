<script setup>
import { computed, ref } from 'vue'
import { usePage, Link, useForm, router } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'

const page = usePage()
const me = computed(() => page.props.me)
const friend = computed(() => page.props.friend)
const chatLists = computed(() => page.props.chatLists)
const messageContainer = ref(null)

Echo.channel('chat').listen('ChatSender', (e) => {
  chatLists.value.push(e)
  messageContainer.value.scrollTop = messageContainer.value.scrollHeight
})

const form = useForm({
  user_id: me.value.id,
  friend_id: friend.value.id,
  message: null,
})

function sendMessage() {
  router.post('/chat-room/send', form, {
    onFinish: () => {
      form.reset('message')
    },
    onSuccess: () => {
      messageContainer.value.scrollTop = messageContainer.value.scrollHeight
      form.clearErrors()
    },
    onError: (errors) => {
      form.errors.message = errors.message
    },
  })
}
</script>
<template>
  <DashboardTemplate
    title="Chat Room | SiCerdas"
    :active="4"
    :pad="false"
    :is-hide="false">
    <div class="flex justify-between flex-col h-full">
      <div class="p-2 w-full flex items-center gap-2 border-b-2">
        <Link href="/chat"
          ><img src="../../../image/icons/left-icon.svg" alt="icon"
        /></Link>
        <h1 class="text-4xl font-bold text-slate-600">{{ friend.username }}</h1>
      </div>
      <div
        class="max-h-full h-full overflow-y-scroll p-4"
        ref="messageContainer">
        <div v-if="chatLists.length === 0">Message not yet</div>
        <div v-else>
          <div
            :class="
              chat.status === 'sending'
                ? 'text-end justify-end'
                : 'text-start justify-start'
            "
            class="p-2 flex"
            v-for="(chat, index) in chatLists"
            :key="index">
            <p
              class="max-w-max rounded-md p-2 text-lg font-semibold"
              :class="
                chat.status === 'sending'
                  ? 'bg-slate-600 text-white'
                  : 'border-2 border-slate-600 text-slate-600'
              ">
              {{ chat.message }}
            </p>
          </div>
        </div>
      </div>
      <form @submit.prevent="sendMessage">
        <div class="bottom-0 p-2 w-full flex items-center gap-2 border-t-2">
          <input
            name="message"
            id="message"
            type="text"
            v-model="form.message"
            @input="(e) => (form.message = e.target.value)"
            class="w-full p-2 rounded-md outline-none text-xl font-semibold"
            :class="
              form.errors.message
                ? 'placeholder:text-red-600/40 text-red-600/80'
                : 'text-slate-600'
            "
            :placeholder="form.errors.message || 'Type Message Here'"
            autocomplete="off"
            autofocus />
          <button type="submit" :disabled="form.processing">
            <img
              src="../../../image/icons/send-icon.svg"
              alt="icon"
              width="40"
              height="40" />
          </button>
        </div>
      </form>
    </div>
  </DashboardTemplate>
</template>
