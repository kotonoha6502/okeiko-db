<template>
  <q-page
    class="q-pa-md"
  >
    <div
      class="row"
    >
      <div
        class="col-10 offset-1"
      >
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

        <file-drop-form />

        <div
          class="row no-wrap items-center text-h6"
        >
          稽古内容
          <q-btn
            round
            flat
            dense
            icon="add"
            color="primary"
            class="q-ml-md"
          />
        </div>
        <div
          class="row no-wrap items-center q-gutter-md q-mb-md"
        >
          <div
            class="col-3"
          >
            <q-select
              value="null"
              :options="[{ label: '唄', value: 1 }, { label: '三味線', value: 2 }]"
            />
          </div>
          <div
            class="col-7"
          >
            <q-input
              label="曲目"
              value=""
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

        <div
          class="row no-wrap items-center q-gutter-md q-mb-md"
        >
          <div
            class="col-all"
          >
            <q-input
              label="備考"
              filled
              v-model="recordData.remark"
              type="textarea"
            />
          </div>
        </div>

        <div
          class="row no-wrap items-center q-gutter-md"
        >
          <div
            class="col-all"
          >
            <q-btn
              color="primary"
              class="q-mr-md"
            >
              保存
            </q-btn>
            <q-btn
              outline
              color="negative"
            >
              キャンセル
            </q-btn>
          </div>
        </div>

      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import {defineComponent, reactive, ref} from "@vue/composition-api"
import FileDropForm from "components/FileDropForm.vue"

export default defineComponent({
  name: 'UploadPage',
  components: {FileDropForm},
  setup () {
    const recordData = reactive({
      recordDate: null,
      uta: [],
      shamisen: [],
      remark: ""
    })
    return {
      recordData
    }
  }
})
</script>
