<script setup>
import { computed } from 'vue'
import { usePage, Link, useForm } from '@inertiajs/vue3'
import DashboardTemplate from '../../Templates/DashboardTemplate.vue'

const page = usePage()
const user = computed(() => page.props.user)
const friendLists = computed(() => page.props.friendLists)

const form = useForm({
  friend_id: null,
})
function getChat(id) {
  form.friend_id = id
  form.get('/chat-room', form)
}
</script>
<template>
  <DashboardTemplate title="Chat | SiCerdas" :active="4">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      <div class="col-span-12">
        <h1 class="text-4xl mb-6 text-slate-600">
          Friend List
          <span class="font-bold">{{ user.username }}</span>
        </h1>
        <SuccessMessage
          v-if="page.props.flash.success"
          :message="page.props.flash.success" />
      </div>
      <div
        class="col-span-1 lg:col-span-12 gap-2 overflow-scroll grid grid-cols-1 lg:grid-cols-12">
        <h1
          v-if="friendLists.length === 0"
          class="text-2xl text-start font-bold capitalize text-slate-600/80 col-span-1 lg:col-span-12">
          --friend not yet--
        </h1>
        <Link
          v-else
          v-for="(friend, index) in friendLists"
          @click="() => getChat(friend.id)"
          :key="index"
          class="col-span-1 lg:col-span-6 w-full border-2 border-slate-600 rounded-md p-2 pr-6 flex justify-between items-center hover:bg-slate-600 hover:text-white group">
          <div class="flex flex-col gap-2">
            <h1
              class="text-2xl font-bold text-slate-600 group-hover:text-white">
              {{ friend.username }}
            </h1>
            <p
              class="text-lg font-semibold text-slate-600 group-hover:text-white capitalize">
              {{ friend.gender }}
            </p>
          </div>
          <h1
            class="text-xl font-bold text-slate-600 group-hover:text-white uppercase">
            {{ friend.class }}
          </h1>
        </Link>
      </div>
    </div>
  </DashboardTemplate>
</template>
