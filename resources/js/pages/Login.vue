<!-- HTML SECTION -->
<template>
  <div class="login-box bg-white box-shadow" style="padding: 40px 20px !important;border-radius:50px;margin: -31px auto !important;">
    <div class="login-logo">
      <router-link :to="{ name: 'home' }"><b>Tagih</b>Boss</router-link>
    </div>
    <p class="login-box-msg">Sign in to start your session</p>
    <form>
      <div class="input-group mb-3 has-feedback" :class="{'has-error': errors.email}">
        <input type="text" class="form-control form-control-lg" placeholder="Email" v-model="data.email" autocomplete="off"
        style="border-radius: 3rem; background-color: #ffce2d !important; height:60px;">
        <div class="input-group-append">
          <div class="input-group-text" style="border-radius: 3.25rem;
          background-color: rgb(255, 255, 255);
          width: 54px;
          height: 54px;
          margin-top: 3px;
          margin-right: 3px;
          margin-bottom: 3px;
          margin-left: -56px !important;
          z-index: 1000 !important;">
            <span class="fas fa-user"></span>
          </div>
        </div>
        <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
      </div>
      <div class="input-group mb-3 has-feedback" :class="{'has-error': errors.password}">
        <input type="password" class="form-control" placeholder="Password" v-model="data.password"
        style="border-radius: 3rem; background-color: #ffce2d !important; height:60px;">
        <div class="input-group-append">
          <div class="input-group-text" style="border-radius: 3.25rem;
          background-color: rgb(255, 255, 255);
          width: 54px;
          height: 54px;
          margin-top: 3px;
          margin-right: 3px;
          margin-bottom: 3px;
          margin-left: -56px !important;
          z-index: 1000 !important;">
            <span class="fas fa-lock"></span>
          </div>
        </div>
        <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
      </div>
      <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
      <div class="row">
        <div class="col-8">
          <div class="icheck-primary">
            <input type="checkbox" id="remember" v-model="data.remember_me">
            <label for="remember">
              Remember Me
            </label>
          </div>
        </div>
        <div class="col-4">
          <button type="submit" class="btn btn-warning btn-block" @click.prevent="postLogin"><b-spinner v-if="spinner" small></b-spinner> Sign In</button>
        </div>
      </div>
    </form>
  </div>
</template>

<!-- JAVASCRIPT SECTION -->
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            data: {
                email: '',
                password: '',
                remember_me: false
            },
            spinner:false
        }
    },
    created() {
        if (this.isAuth) {
            this.$router.push({ name: 'home' })
        }
    },
    computed: {
        ...mapGetters(['isAuth']),
      	...mapState(['errors'])
    },
    methods: {
        ...mapActions('auth', ['submit']),
        ...mapMutations(['CLEAR_ERRORS']),
        postLogin() {
          this.spinner=true;
            this.submit(this.data).then(() => {
              this.spinner=false;
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    // this.$router.push({ name: 'home' })
                    // location.reload()
                }
            })
        }
    }
}
</script>
