<template>
  <div class="row">
    <div class="col-md-6">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Sycn to micro bpr online</h3>
        </div>
          <div class="card-body">
            <div class="overlay-wrapper">
              <div class="overlay" v-if="loading">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                <div class="text-bold pt-2">
                  Loading...
                </div>
              </div>
                <micro-form></micro-form>
            </div>
          </div>
          <div class="card-footer">
            <div class="alert alert-success alert-dismissible" v-if="alert_auth">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <strong><i class="icon fas fa-check"></i> Authenticated!</strong>
            </div>
              <button class="btn btn-warning btn-sm btn-flat float-right" @click.prevent="submitCheck">
                  <i class="fa fa-save"></i> Check
              </button>
          </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Form create new users sycn to micro bpr online</h3>
        </div>
          <div class="card-body">
              <outlet-form></outlet-form>
          </div>
          <div class="card-footer">
              <button class="btn btn-warning btn-sm btn-flat float-right" @click.prevent="submit">
                  <i class="fa fa-save"></i> Save
              </button>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormOutlets from './Form.vue'
    import FormMicro from './MicroForm.vue'
    export default {
        name: 'AddUsers',
        data() {
            return {
              alert_auth: false,
              loading: false,
            }
        },
        methods: {
            ...mapActions('users', ['submitUsers']),
            ...mapActions('users', ['submitUsersCheck']),
            submitCheck() {
                this.loading = true
                this.submitUsersCheck().then(() => {
                  this.loading = false
                  this.alert_auth = true
                  setTimeout(() => {
                      this.alert_auth = false
                  }, 2000)
                })
                this.loading = false
            },
            submit() {
                this.submitUsers().then(() => {
                    this.$router.push({ name: 'users.data' })
                })
            }
        },
        components: {
            'outlet-form': FormOutlets,
            'micro-form': FormMicro,
        }
    }
</script>
