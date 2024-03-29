<script setup>
import { computed, defineProps, ref } from 'vue'
import { Link, usePage, router, Head } from '@inertiajs/vue3'

defineProps({
  title: { type: String, default: 'Home | SiCerdas' },
  active: { type: Number, default: 0 },
  pad: { type: Boolean, default: true },
  isHide: { type: Boolean, default: true },
})

const page = usePage()
const routes = computed(() => page.props.routes)
const user = computed(() => page.props.user)
let isOpen = ref(false)

const linkLists = routes.value.filter((route) => {
  if (typeof route.acc === 'object') {
    return route.acc.includes(user.value.role_id)
  } else {
    return route.acc === user.value.role_id
  }
})

function handleSideBar() {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <Head :title="title" />
  <div class="w-full min-h-screen max-h-screen flex">
    <div
      class="lg:w-60 min-h-screen bg-[#003153] fixed lg:relative -left-96 lg:left-0 transition-all duration-500 ease-in-out z-50"
      :class="isOpen ? 'left-0 w-60' : '-left-96 w-60'">
      <Link
        class="flex justify-center items-center shadow-xl py-2 gap-2 relative"
        href="/">
        <img src="../../image/logo.png" alt="logo" class="w-10 h-10" />
        <h1 class="text-2xl font-bold text-white">SiCerdas</h1>
        <button
          @click.prevent="handleSideBar"
          class="absolute top-4 right-4 block lg:hidden">
          <img src="../../image/icons/close-icon.svg" alt="icon" />
        </button>
      </Link>
      <div class="min-h-max flex flex-col">
        <div>
          <Link
            v-for="link in linkLists"
            class="items-center justify-start gap-2 py-4 px-4 flex"
            :class="{
              'lg:bg-white': active === link.id,
            }"
            :href="link.path"
            :key="link.id">
            <p
              class="font-semibold text-base capitalize text-white"
              :class="active === link.id ? 'lg:text-slate-600' : 'text-white'">
              {{ link.name }}
            </p>
          </Link>
        </div>
        <div
          class="flex justify-center items-center w-max p-1 bg-white lg:left-6 gap-4 rounded-md mx-auto mt-96">
          <Link href="/profile" title="profile">
            <h1 class="text-xl font-semibold">{{ user.username }}</h1>
            <p class="text-base font-medium">{{ user.role }}</p>
          </Link>
          <form @submit.prevent="router.post('/logout')">
            <button
              type="submit"
              class="flex items-center justify-center"
              title="logout">
              <img src="../../image/icons/exit-icon.svg" alt="icon" />
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="flex-1">
      <div class="min-h-screen bg-slate-50">
        <div class="w-full bg-primary p-4 block lg:hidden" v-if="isHide">
          <button @click.prevent="handleSideBar">
            <img src="../../image/icons/menu-icon.svg" alt="icon" />
          </button>
        </div>
        <div
          class="rounded-md bg-white h-screen shadow-xl"
          :class="pad ? 'px-8 py-4' : 'px-0 py-0'">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>
