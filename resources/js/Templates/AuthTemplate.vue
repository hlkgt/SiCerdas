<template>
  <Head title="Register | SiCerdas" />
  <div
    class="min-h-dvh grid grid-cols-1 lg:grid-cols-12 justify-center items-center gap-4 px-2 lg:px-28">
    <div class="col-span-7 hidden lg:block">
      <h1 class="text-6xl font-bold text-primary">SiCerdas</h1>
      <p class="text-xl font-medium text-white">
        Sebuah aplikasi CMS(Content Management Sistem) yang berjalan di bidang
        sekolah, untuk membantu para guru dan siswa agar dapat melakukan absensi
        dengan mudah dan sebagainya.
      </p>
    </div>
    <slot></slot>
  </div>
</template>

<style scoped>
.min-h-dvh {
  background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.8)),
    url('../../image/bg-register.jpg');
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  object-fit: cover;
}
</style>
