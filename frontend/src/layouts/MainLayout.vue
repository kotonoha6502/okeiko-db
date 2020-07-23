<template>
  <q-layout
    view="hHh LpR fFf"
  >
    <q-header
      elevated
      class="bg-primary text-white"
      height-hint="98"
    >
      <q-toolbar>
        <q-toolbar-title>
          お稽古管理システム
        </q-toolbar-title>
      </q-toolbar>

      <q-tabs
        align="left"
        inline-label
      >
        <q-route-tab
          to="/"
          icon="home"
          label="ホーム"
        />
        <q-route-tab
          to="/search"
          icon="search"
          label="検索"
        />
        <q-route-tab
          to="/upload"
          icon="backup"
          label="アップロード"
        />
      </q-tabs>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import {defineComponent} from '@vue/composition-api'

export default defineComponent({
  name: 'MainLayout',
  setup () {
  }
})
</script>
