<template>
  <div>
    <div>
      <q-btn
        flat
        rounded
        color="primary"
        label="曲目を追加"
      />
    </div>

    <!-- お稽古日 -->
    <q-input
      label="お稽古日"
      v-model="recordData.recordDate"
      mask="date"
      :rules="['date']"
    >
      <template v-slot:append>
        <q-icon
          name="event"
          class="cursor-pointer"
        >
          <q-popup-proxy
            ref="qDateProxy"
            transition-show="scale"
            transition-hide="scale"
          >
            <q-date
              v-model="recordData.recordDate"
              @input="() => $refs.qDateProxy.hide()"
            />
          </q-popup-proxy>
        </q-icon>
      </template>
    </q-input>

    <!-- 曲目追加フォーム -->
    <div
      class="row no-wrap items-center q-gutter-md q-mb-md"
      v-for="(songData, k) in songListModel"
      :key="k"
    >
      <div
        class="col-3"
      >
        <q-select
          v-model="songData.category"
          :options="songCategoryOptions"
          option-value="value"
          option-label="text"
          emit-value
          map-options
        />
      </div>
      <div
        class="col-7"
      >
        <q-input
          v-model="songData.title"
          label="曲目"
          clearable
        />
      </div>
      <div
        class="col-auto"
      >
        <q-btn
          flat
          round
          dense
          color="negative"
          icon="close"
        />
      </div>
    </div>

  </div>
</template>

<script lang="ts">
import {computed, defineComponent, PropType, ref} from '@vue/composition-api'
import {RecordData, OkeikoDataModel} from "../../models/OkeikoData";

export default defineComponent({
  name: 'EditDetailForm',
  props: {
    value: {
      type: Object as PropType<RecordData>,
      required: true,
    }
  },
  setup (props, ctx) {
    const songList = ref<Array<OkeikoDataModel>>([
      { category: 'both', title: "" }
    ])

    const songCategoryOptions = computed(() => [
      { value: 'both', text: '唄、三味線' },
      { value: 'shamisen', text: '三味線'},
      { value: 'uta', text: '唄' },
    ])

    const songListModel = computed({
      get: () => props.value.songList,
      set: (v) => {
        ctx.emit('input', { ...props.value, songList: v })
      }
    })

    return {
      songList,
      songCategoryOptions,
      songListModel,
    }
  }
})
</script>

<style scoped>

</style>
