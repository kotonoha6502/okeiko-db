<template>
  <div>
    <!-- お稽古日 -->
    <q-input
      label="お稽古日"
      v-model="recordDate"
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
              v-model="recordDate"
              @input="() => $refs.qDateProxy.hide()"
            />
          </q-popup-proxy>
        </q-icon>
      </template>
    </q-input>

    <!-- 曲目追加フォーム -->
    <div
      class="row no-wrap items-center"
    >
      <div
        class="q-pr-md text-blue-6"
        style="font-size: 1.0rem"
      >
        稽古した曲目
      </div>
      <div>
        <q-btn
          flat
          rounded
          color="primary"
          icon="add"
          @click="addSongData"
        >
          曲目を追加
        </q-btn>
      </div>
    </div>
    <div
      v-for="({ value: songData }, k) in songDataList"
      class="row no-wrap items-center q-gutter-md q-mb-md"
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
          @click="removeSongData(k)"
        />
      </div>
    </div>

    <div>
      <q-input
        type="textarea"
        v-model="remark"
        label="備考"
      />
    </div>
  </div>
</template>

<script lang="ts">
import {computed, defineComponent, PropType, watch, watchEffect, WritableComputedRef} from '@vue/composition-api'
import {RecordDetail, SongData} from 'src/models/OkeikoData'
import { date } from 'quasar'

const { formatDate } = date

export default defineComponent({
  name: 'EditDetailForm',
  props: {
    value: {
      type: Object as PropType<RecordDetail>,
      required: true,
    }
  },
  setup (props, ctx) {
    const songCategoryOptions = Object.freeze([
      { value: 'both', text: '唄、三味線' },
      { value: 'shamisen', text: '三味線'},
      { value: 'uta', text: '唄' },
    ])

    const recordDate = computed({
      get () {
        return formatDate(props.value.recordDate, 'YYYY/MM/DD')
      },
      set (v :string) {
        const [y, m, d] = v.split('/').map(Number)
        ctx.emit('input', { ...props.value, recordDate: new Date(y, m, d) })
      }
    })

    const songDataList = [] as Array<WritableComputedRef<SongData>>;

    const mkSongDataListComputedCollection = () => {
      songDataList.splice(0)
      props.value.songDataList.forEach((songDataProp, k) => {
        songDataList.push( computed({
          get() {
            return songDataProp
          },
          set(v: SongData) {
            const newVal = [...props.value.songDataList]
            ctx.emit('input', {
              ...props.value,
              songDataList: newVal.splice(k, 1, v)
            })
          }
        }))
      })
    }
    mkSongDataListComputedCollection()

    watch(
      () => props.value.songDataList,
      mkSongDataListComputedCollection
    )

    const remark = computed({
      get () {
        return props.value.remark
      },
      set (v) {
        ctx.emit('input', {
          ...props.value,
          remark: v
        })
      }
    })

    const addSongData = () => {
      ctx.emit('input', {
        ...props.value,
        songDataList: [
          ...props.value.songDataList,
          { category: 'both', title: '' }
        ]
      })
    }

    const removeSongData = (k: number) => {
      if (k >= songDataList.length) {
        return
      }

      if (songDataList[k].value.title !== '') {
        ctx.emit('input', {
          ...props.value,
          songDataList: props.value.songDataList.map((songData, l) => {
            return  l === k
              ? { 'title': '', 'category': 'both' }
              : songData
          })
        })
      }
      else {
        ctx.emit('input', {
          ...props.value,
          songDataList: props.value.songDataList.filter((_, l) => l !== k)
        })
      }
    }

    return {
      songCategoryOptions,
      recordDate,
      songDataList,
      remark,
      addSongData,
      removeSongData
    }
  }
})
</script>

<style scoped>

</style>
