<template>
  <div>
    <div class="form-group" :class="{ 'has-error': errors.usernamemicro }">
      <label for="">Username Micro BPR Online</label>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">
            <i class="fas fa-user"></i>
          </span>
        </div>
        <input type="text" class="form-control" v-model="micro.usernamemicro" placeholder="enter username micro bpr online..." autocomplete="off">
        <p class="text-danger" v-if="errors.usernamemicro">{{ errors.usernamemicro[0] }}</p>
      </div>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.passwordmicro }">
      <label for="">Password Micro BPR Online</label>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1">
            <i class="fas fa-key"></i>
          </span>
        </div>
        <input :type="showMicroPass ? 'password' : 'text'" class="form-control" v-model="micro.passwordmicro" placeholder="enter password micro bpr online..." autocomplete="off">
        <div class="input-group-append">
          <button class="input-group-text" @click.prevent="seepassclickMicroshow" v-if="seepassmicroshow">
            <i class="fas fa-eye-slash"></i>
            <i class="fas fa-eye-slash"></i>
          </button>
          <button class="input-group-text" @click.prevent="seepassclickMicrohide" v-if="seepassmicrohide">
            <i class="fas fa-eye"></i>
            <i class="fas fa-eye"></i>
          </button>
        </div>
        <p class="text-danger" v-if="errors.passwordmicro">{{ errors.passwordmicro[0] }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
    name: 'FormMicro',
    data() {
        return {
          showMicroPass: true,
          seepassmicroshow: true,
          seepassmicrohide: false,
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('users', {
            micro: state => state.userMicro
        })
    },
    methods: {
        ...mapMutations('users', ['CLEAR_FORM_MICRO']),
        seepassclickMicroshow() {
          this.showMicroPass=false
          this.seepassmicroshow=false
          this.seepassmicrohide=true
        },
        seepassclickMicrohide() {
          this.showMicroPass=true
          this.seepassmicroshow=true
          this.seepassmicrohide=false
        }
    },
    destroyed() {
        this.CLEAR_FORM_MICRO()
    }
}
</script>
