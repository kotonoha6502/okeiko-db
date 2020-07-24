<template>
  <div
    class="text-h5 text-blue-7"
    style="margin: 24px 0 16px; font-weight: bold"
  >
    <slot></slot>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "@vue/composition-api";

export default defineComponent({
  name: "Headline",
})
</script>
