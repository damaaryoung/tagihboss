<template>
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <router-link :to="{name: 'home'}" class="nav-link">Home</router-link>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown" @click="clickNotif">
        <router-link :to="{ name: 'notification' }" class="nav-link" data-toggle="dropdown">
          <i class="far fa-bell"></i>
          <span v-if="notif" class="badge badge-warning navbar-badge" style="right: 19px !important;top: 4px !important;">
            new
          </span>
        </router-link>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0)" @click="postLogout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
          notif: false,
        }
    },
    computed: {
        ...mapState('user', {
            authenticated: state => state.authenticated
        }),
        ...mapGetters(['isAuth']),
      	...mapState(['errors'])
    },
    mounted () {
      Echo.channel('EveryoneChannel').listen('.EveryoneMessage', (e) => {
        // console.log(e.message);
        this.$toast("Pemberitahuan baru.");
        this.notif = true
      })
    },
    methods: {
      ...mapActions('auth', ['logout']),
      postLogout() {
          this.logout().then(() => {
            this.$router.push({ name: 'login' })
          })
      },
      clickNotif() {
          this.notif = false
      }
    }
}
</script>
