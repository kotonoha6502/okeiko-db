<template>
  <div
    class="row no-wrap items-center text-h6 text-blue-6 q-mt-lg"
  >
    <div
      v-if="prepend"
      class="col-auto self-start"
    >
      <slot name="prepend"></slot>
    </div>

    <div
      class="col"
    >
      <slot name="default"></slot>
    </div>

    <div
      v-if="append"
      class="col-auto self-end"
    >
      <slot name="append"></slot>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "@vue/composition-api";

export default defineComponent({
  name: "SectionTitle",
  props: {
    prepend: Boolean,
    append: Boolean,
  }
})
</script>

<style scoped>

</style>
