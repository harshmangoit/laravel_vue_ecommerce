<script setup>
  const { value } = defineProps(['value'])

</script>

<template>
  <p class="flex gap-1">
    <slot name="beforePrice" />
    <span>Rs. {{ value }}</span>
    <slot name="afterPrice" />
  </p>
</template>
