<template>
  <q-page
    class="q-pa-md"
  >
    <div
      class="row"
    >
      <div
        class="offset-1 col-10"
      >
        <div
          class="text-h5 text-blue-7"
          style="margin: 24px 0 16px; font-weight: bold"
        >
          アップロード
        </div>

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

        <file-drop-form
          multiple
          icon="backup"
        />

        <div
          class="row no-wrap items-center text-h6 text-blue-6 q-mt-lg"
        >
          <q-icon
            name="edit"
            class="q-mr-sm"
          />
          お稽古詳細
        </div>

        <edit-detail-form
          v-model="recordData"
        />

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
import {defineComponent, reactive} from "@vue/composition-api"
import FileDropForm from "../components/FileDropForm.vue"
import EditDetailForm from "../components/Upload/EditDetailForm.vue"
import {RecordData} from "../models/OkeikoData"

export default defineComponent({
  name: 'UploadPage',
  components: {FileDropForm, EditDetailForm},
  setup () {
    const recordData = reactive<RecordData>({
      recordDate: undefined,
      songList: [],
      remark: ""
    })

    return {
      recordData,
    }
  }
})
</script>
