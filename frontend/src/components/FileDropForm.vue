<template>
  <div
    class="q-pa-md text-center relative-position container"
    :style="style"
    tabIndex="0"
    @click="__click"
    @keydown.enter="__click"
    @focus="__activate"
    @mouseenter="__activate"
    @blur="__deactivate"
    @mouseleave="__deactivate"
  >
    <slot>
      <div
        :style="innerStyle"
      >
        <q-icon
          size="sm"
          :name="icon"
          class="q-mr-sm"
        />
        ここにファイルをドラッグ&ドラップするか、クリックして選択
      </div>
    </slot>
    <input
      type="file"
      style="display: none"
      ref="nativeRef"
      :multiple="multiple"
    />
  </div>
</template>

<script lang="ts">
import {computed, defineComponent, ref, watchEffect} from '@vue/composition-api'

export default defineComponent({
  name: "FileDropForm",
  props: {
    multiple: Boolean,
    icon: {
      type: String,
      default: 'publish'
    }
  },
  setup (props, ctx) {
    const clicked = ref(false)
    const active = ref(false)

    const __activate = () => {
      active.value = true
    }

    const __deactivate = () => {
      active.value = false
    }

    const __click = () => {
      clicked.value = true
      setTimeout(() => {
        clicked.value = false
      }, 200)
    }

    const nativeRef = ref<HTMLElement|null>(null)

    watchEffect(() => {
      if (clicked.value) {
        pickFile()
      }
    })

    const style = computed(() => {
      const background = clicked.value
        ? '#e2e7f9'
        : ( active.value
          ? '#f4f4f4'
          : 'inherit'
          )

      const borderColor = clicked.value
        ? '#134c88'
        : (active.value
          ? '#134c88'
          : '#7e7e7e'
          )

      return {
        border: `thin dashed ${borderColor}`,
        background,
        color: active.value ? '#134c88' : '#5e5e5e',
        borderRadius: '3px',
        cursor: 'pointer',
        outline: 'none',
        transition: 'all .3s ease-in-out',
      }
    })

    const innerStyle = computed(() => {
      return {
        transition: 'all .2s ease-in-out',
//        transform: clicked.value ? 'translate(0, 2px)' : 'inherit'
      }
    })

    const pickFile = () => {
      if (nativeRef.value !== null) {
        nativeRef.value.click()
      }
    }

    return {
      clicked,
      active,
      style,
      innerStyle,
      nativeRef,
      __click,
      __activate,
      __deactivate
    }
  }
})
</script>

<style scoped>

</style>
