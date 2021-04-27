<template>
    <div>
      <div class="row">
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.user_id }">
              <label for="">ID Users</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" v-model="outlet.user_id" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.user_id">{{ errors.user_id[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.id_lokasi }">
              <label for="">ID Lokasi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" v-model="outlet.id_lokasi" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.id_lokasi">{{ errors.id_lokasi[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.nik }">
              <label for="">NIK</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" v-model="outlet.nik" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.nik">{{ errors.nik[0] }}</p>
          </div>
        </div>
      </div>
      <div class="form-group" :class="{ 'has-error': errors.name }">
          <label for="">Nama Users</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" v-model="outlet.name" placeholder="enter name users..." readonly>
          </div>
          <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
      </div>
      <div class="form-group" :class="{ 'has-error': errors.email }">
          <label for="">Email</label>
          <div class="input-group">
            <input type="email" class="form-control" v-model="outlet.email" placeholder="enter email users..." readonly>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon2">@</span>
            </div>
          </div>
          <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
      </div>
      <div class="form-group" :class="{ 'has-error': errors.password }">
        <label for="">Password</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
          </div>
          <input :type="showPass ? 'password' : 'text'" class="form-control" v-model="outlet.password" placeholder="enter password users...">
          <div class="input-group-append">
            <button class="input-group-text" id="basic-addon1" @click.prevent="seepassclickshow" v-if="seepasshide">
              <i class="fas fa-eye"></i>
              <i class="fas fa-eye"></i>
            </button>
            <button class="input-group-text" id="basic-addon1" @click.prevent="seepassclickhide" v-if="seepassshow">
              <i class="fas fa-eye-slash"></i>
              <i class="fas fa-eye-slash"></i>
            </button>
          </div>
        </div>
        <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.roles }">
      <label for="">Roles</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
        </div>
        <select class="form-control" v-model="outlet.roles">
            <option value="">Pilih</option>
            <option v-for="(row, index) in roles" :value="row.id" :key="index">{{ row.name }}</option>
        </select>
      </div>
      <p class="text-danger" v-if="errors.roles">{{ errors.roles[0] }}</p>
  </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormUsers',
    data() {
        return {
          showPass: true,
          seepassshow: true,
          seepasshide: false,
        }
    },
    created() {
        this.getRoles() //DATA ROLES
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('users', {
            outlet: state => state.user,
            roles: state => state.roles, //MENGAMBIL STATE OUTLET
        })
    },
    methods: {
        ...mapActions('users', [
            'getRoles',
        ]),
        ...mapMutations('users', ['CLEAR_FORM']),
        seepassclickshow() {
            this.showPass = true
            this.seepassshow = true
            this.seepasshide = false
        },
        seepassclickhide() {
            this.showPass = false
            this.seepassshow = false
            this.seepasshide = true
        }
    },
    destroyed() {
        this.CLEAR_FORM()
    }
}
</script>
