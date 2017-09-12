// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router/index'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import VueNativeSock from 'vue-native-websocket'
import Vuex from 'vuex'
import store from './store/index'

Vue.config.productionTip = false

Vue.use(Vuex);
Vue.use(ElementUI);
Vue.use(VueNativeSock, 'ws://localhost:9501', { store: store, format: 'json' })

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
