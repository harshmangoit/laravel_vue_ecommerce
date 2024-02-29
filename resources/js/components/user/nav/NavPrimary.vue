<script setup>
import TheLogo from './TheLogo.vue'
import {useNav} from '../../../composables/nav'
import {useMobileMenu} from '../../../composables/mobile-menu'
  const { navlinksPrimary } = useNav()
  const { isMobileMenuOpen } = useMobileMenu()
</script>
<template>
  <div
    class="flex flex-col justify-center md:space-y-0 space-y-4 md:flex md:flex-row md:items-center md:space-x-4"
  >
    <TheLogo class="mb-6 md:hidden"></TheLogo>
    <RouterLink
      v-for="(navlink, index) in navlinksPrimary"
      :key="index"
      :to="navlink.link"
      class="hover:text-primary underline-offset-8"
      color="gray"
      active-class="text-primary underline"
      exact
      @click="isMobileMenuOpen = false"
      >{{ $t(navlink.text) }}</RouterLink
    >
  </div>
</template>
<style scoped></style>
