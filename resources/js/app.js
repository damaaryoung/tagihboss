import Echo from 'laravel-echo';
import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'
import Permissions from './mixins/permission.js'
import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueSignature from "vue-signature-pad"
import Toast from "vue-toastification";
import Vue2Editor from "vue2-editor";
import 'vue-push-notification-preview/src/assets/devices.scss';
import "vue-toastification/dist/index.css";
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'sweetalert2/dist/sweetalert2.min.css';
import { mapActions, mapGetters } from 'vuex'
import HighchartsVue from 'highcharts-vue'
Vue.use(HighchartsVue)
Vue.use(Toast, {
  transition: "Vue-Toastification__bounce",
  maxToasts: 20,
  newestOnTop: true
});
Vue.use(Vue2Editor);
Vue.use(VueSignature);
Vue.mixin(Permissions)
Vue.use(VueSweetalert2)
Vue.use(BootstrapVue)
Vue.config.productionTip = false;
window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});
new Vue({
    el: '#app',
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(['isAuth'])
    },
    methods: {
        ...mapActions('user', ['getUserLogin'])
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin()
        }
    }
})
