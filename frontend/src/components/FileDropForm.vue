<template>
  <div>
    <div
      v-if="!hideWhenNotEmpty || (hideWhenNotEmpty && valueModel.length <= 0) "
      class="q-pa-md q-mb-sm text-center relative-position container"
      :style="style"
      tabIndex="0"
      @click="__click"
      @keydown.enter="__click"
      @focus="__activate"
      @mouseenter="__activate"
      @blur="__deactivate"
      @mouseleave="__deactivate"
      @dragover.prevent="__activate"
      @dragleave.prevent="__deactivate"
      @drop.stop.prevent="__drop"
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
          {{ text }}
        </div>
      </slot>
      <input
        type="file"
        style="display: none"
        ref="nativeRef"
        :multiple="multiple"
        @input.stop.prevent="e => addFile(e.target.files)"
      />
    </div>

    <template v-if="valueModel.length > 0">
      <slot
        name="containing-files"
        v-bind="containingFilesSlotScopes"
      >
      </slot>
    </template>
  </div>
</template>

<script lang="ts">
import {computed, defineComponent, PropType, ref, watchEffect} from '@vue/composition-api'
import {validate} from "src/hooks/useValidationHook";

export default defineComponent({
  name: "FileDropForm",
  props: {
    // content
    icon: {
      type: String,
      default: 'publish'
    },
    text: {
      type: String,
      default: "ここにファイルをドラッグ&ドラップするか、クリックして選択"
    },
    // behavior
    multiple: Boolean,
    add: Boolean,
    hideWhenNotEmpty: Boolean,
    rules: {
      type: Array as PropType<Array<(v: File) => (Boolean|string)>>,
      default: () => []
    },
    extensions: {
      type: Array as PropType<Array<string>>,
      default: () => []
    },
    maxFileSize: Number,

    // model
    value: {
      type: Array as PropType<Array<File>>,
      default: () => [],
    },

    // state

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

    const __mkNewValue = (files: FileList) => {
      const uploaded = props.add
        ? [...valueModel.value, ...Array.from(files)]
        : Array.from(files)

      if (! props.multiple) {
        uploaded.splice(1)
      }

      return uploaded
    }

    const addFile = (files: FileList) => {
      const newValue = __mkNewValue(files)
      if (newValue.length > 0) {
        valueModel.value = newValue
      }
      nativeRef.value = null
    }

    const validationRules = [
      (file: File) => (props.maxFileSize === undefined
        || file.size <= props.maxFileSize
        || 'アップロード可能なファイルサイズを超えています'),

      (file: File) => { debugger; return (props.extensions === undefined
        || props.extensions.includes(file.name.split('.').slice(-1))
        || `拡張子が${props.extensions.join(',')}のいずれかであるファイルをアップロードしてください`)},

      ...props.rules,
    ]

    const [valueModel, errors] = validate<File[]>(validationRules, {
      get() :File[] {
        return props.value
      },
      set (v: File[]) :void {
        ctx.emit('input', v)
      }
    })

    const __drop = (e: DragEvent) => {
      if (e.dataTransfer?.files instanceof FileList) {
        addFile(e.dataTransfer.files)
      }
    }

    const containingFilesSlotScopes = computed(() => {
      return {
        files: valueModel.value,
        count: valueModel.value.length,
        emitValue: (files: Array<File>) => ctx.emit('input', files),
        clear: () => ctx.emit('input', []),
        empty: () => valueModel.value.length > 0,
      }
    })

    return {
      clicked,
      active,
      style,
      innerStyle,
      nativeRef,
      valueModel,
      errors,
      containingFilesSlotScopes,
      addFile,
      __click,
      __activate,
      __deactivate,
      __drop
    }
  }
})
</script>

<style scoped>

</style>
