<template>
  <div class="hold-transition" v-bind:class="[!isAuth ? 'login-page' : 'sidebar-mini layout-navbar-fixed layout-fixed']">
    <div v-if="isAuth" class="wrapper">
      <app-navbar v-if="isAuth" />
      <app-sidebar v-if="isAuth" />
      <router-view v-if="isAuth"></router-view>
      <app-footer v-if="isAuth" />
    </div>
    <router-view v-else></router-view>
  </div>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
    import Navbar from './components/Navbar.vue'
    import Sidebar from './components/Sidebar.vue'
    import Footer from './components/Footer.vue'
    export default {
        computed: {
            ...mapState(['token']),
            ...mapGetters(['isAuth'])
        },
        components: {
            'app-navbar': Navbar,
            'app-sidebar': Sidebar,
            'app-footer': Footer
        }
    }
</script>
