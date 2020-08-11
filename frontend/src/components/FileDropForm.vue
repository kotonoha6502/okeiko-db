<template>
  <div>
    <div
      v-if="!hideWhenNotEmpty || (hideWhenNotEmpty && valueModel.length <= 0) "
      class="q-px-xl q-py-md q-mb-sm text-center relative-position container"
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
      <div
        v-if="hasErrors"
        style="position: absolute; top: 0; right: 0"
        class="q-mt-sm q-mr-sm"
      >
        <q-icon
          name="error"
          size="xs"
          color="negative"
        />
      </div>
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
        :accept="accept"
        :multiple="multiple"
        @input.stop.prevent="e => addFile(e.target.files)"
      />
    </div>
    <transition
      appear
      enter-active-class="animated fadeInDown"
      leave-active-class="animated fadeOutUp"
    >
      <div
        v-if="hasErrors"
        class="file-drop-form__validation-errors"
      >
        <slot
          name="errors"
          v-bind="{ errors, hasErrors }"
        >
          <ul class="q-mt-xs">
            <li
              v-for="(error, k) in errors"
              :key="k"
              class="text-caption"
              style="color: var(--q-color-negative)"
            >
              {{ error }}
            </li>
          </ul>
        </slot>
      </div>
    </transition>

    <transition
      name="expand"
    >
      <div
        v-if="valueModel.length > 0"
        class="file-drop-form__containing-file-list"
      >
        <slot
          name="containing-files"
          v-bind="containingFilesSlotScopes"
        >
        </slot>
      </div>
    </transition>
  </div>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  isRef,
  PropType,
  Ref,
  ref,
  watchEffect,
  WritableComputedRef
} from '@vue/composition-api'
import {sleep} from "src/utils/async";

type Rule<T> = (v: T) => (true|string)
interface Option<T> {
  get: () => T,
  set: (v: T) => void
}
function validate(
  rules: Rule<File>[],
  target: Option<File[]>|WritableComputedRef<File[]>
): [ WritableComputedRef<File[]>, Ref<string[]> ] {
  const base = isRef(target) ? target : computed(target)

  const errors = ref<string[]>([])

  const validated = computed({
    get () {
      return base.value
    },
    set (vs: File[]) : void {
      errors.value  = []

      const applyRules = (v: File) => rules.reduce((acc: string[], rule)  :string[] => {
        const result = rule(v)
        return result === true
          ? acc
          : [...acc, result]
      }, [])

      errors.value = vs.flatMap(applyRules)

      if (errors.value.length <= 0) {
        base.value = vs
      }
    }
  })

  return [ validated, errors ]
}

function applyAccept (accepts: string[], f: File) : boolean {
  const ext = `.${f.name.split(".").slice(-1)[0]}`

  for (const accept of accepts) {
    if (accept.match(/¥.[a-zA-Z0-9]+$/) && accept === ext) {
      return true
    }

    if (f.type.match(accept))  {
      return true
    }
  }
  return false
}

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
      type: Array as PropType<Array<Rule<File>>>,
      default: () => []
    },
    extensions: {
      type: Array as PropType<Array<string>>,
      default: () => []
    },
    accept: {
      type: String,
      default: ""
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
        : ( active.value || errors.value.length > 0
          ? '#f4f4f4'
          : 'inherit'
          )

      const borderColor =errors.value.length > 0
        ? 'var(--q-color-negative)'
        : (clicked.value
          ? '#134c88'
          : (active.value
            ? '#134c88'
            : '#7e7e7e'
            )
          )

      const color = errors.value.length > 0
        ? 'var(--q-color-negative)'
        : ( active.value
          ? '#134c88'
          : '#5e5e5e'
          )

      return {
        border: `thin dashed ${borderColor}`,
        background,
        color,
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

    const addFile = async (files: FileList) => {
      errors.value = []

      // エラー表示を消すアニメーション完了を待つ
      await sleep(200)

      const newValue = __mkNewValue(files)
      if (newValue.length > 0) {
        valueModel.value = newValue
      }
      nativeRef.value = null
    }

    const acceptComputed = computed(() => {
      return props.accept.split(/\s+|,/).filter(c => c.length>0)
    })

    const validationRules: Array<Rule<File>> = [
      (file: File) => (props.maxFileSize === undefined
        || file.size <= props.maxFileSize
        || 'アップロード可能なファイルサイズを超えています'),

      (file: File) => (acceptComputed.value.length <= 0
        || applyAccept(acceptComputed.value, file)
        || `アップロード可能なファイル形式ではありません`),

      ...props.rules,
    ]

    const [valueModel, errors] = validate(validationRules, {
      get() :File[] {
        return props.value
      },
      set (v: File[]) :void {
        ctx.emit('input', v)
      }
    })

    const hasErrors = computed(() => errors.value.length > 0)

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
      hasErrors,
      containingFilesSlotScopes,
      acceptComputed,
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
