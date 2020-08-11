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

        <headline>アップロード</headline>

        <file-drop-form
          v-model="uploadedFile"
          icon="backup"
          text="ここにお稽古の録音ファイルをドラッグ&ドロップしてください"
          hide-when-not-empty
          accept=".pdf,image/*"
          :rules="[file => (file.name.match('test.*') !== null || 'ファイル名はtest.*でオナシャス！')]"
        >
          <template
            #containing-files="{ emitValue, clear }"
          >
            <q-btn
              @click="clear"
            >
              Fire!
            </q-btn>
          </template>
        </file-drop-form>

        <section-title>
          お稽古詳細
        </section-title>

        <edit-detail-form
          v-model="recordDetailData"
        />

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
import EditDetailForm from "components/Upload/EditDetailForm.vue"
import Headline from "components/Headline.vue";
import SectionTitle from "components/SectionTitle.vue";
import {RecordDetail} from "src/models/OkeikoData"

export default defineComponent({
  name: 'UploadPage',
  components: {FileDropForm, EditDetailForm, Headline, SectionTitle},
  setup () {
    const recordDetailData = reactive<RecordDetail>({
      recordDate: undefined,
      songDataList: [ { category: 'both', title: '' } ],
      remark: ""
    })

    const uploadedFile = ref<Array<File>>([])

    return {
      recordDetailData,
      uploadedFile,
    }
  }
})
</script>
