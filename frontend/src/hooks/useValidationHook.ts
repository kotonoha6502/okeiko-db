import {computed, isRef, Ref, ref, WritableComputedRef} from "@vue/composition-api";

export type ValidationRule = <T>(v: T) => (true|string)

interface Option<T> {
  get: () => T;
  set: (value: T) => void;
}

export function validate<T>(rules: ValidationRule[], option: Option<T>) : [ WritableComputedRef<T>, Ref<string[]> ]
export function validate<T>(rules: ValidationRule[], target: WritableComputedRef<T>) : [ WritableComputedRef<T>, Ref<string[]> ]
export function validate<T>(
  rules: ValidationRule[],
  target: Option<T>|WritableComputedRef<T>
): [ WritableComputedRef<T>, Ref<string[]> ] {
  const base = isRef(target) ? target : computed(target)
  const errors = ref<string[]>([])

  const validated = computed({
    get () {
      return base.value
    },
    set (v: T) : void {
      errors.value = rules.reduce((acc: string[], rule) => {
        const result = rule(v)
        return result === true ? acc : [...acc, result]
        }, [])

      if (errors.value.length <= 0) {
        base.value = v
      }
    }
  })

  return [ validated, errors ]
}
