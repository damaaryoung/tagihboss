<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Update Users Data</h3>
      </div>
        <div class="card-body">
          <div class="overlay-wrapper">
            <div class="overlay" v-if="loading">
              <i class="fas fa-3x fa-sync-alt fa-spin"></i>
              <div class="text-bold pt-2">
                Loading...
              </div>
            </div>
              <users-form></users-form>
          </div>
        </div>
        <div class="card-footer">
          <div class="alert alert-success alert-dismissible" v-if="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong><i class="icon fas fa-check"></i> Success Updated!</strong>
          </div>
          <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
              <i class="fa fa-save"></i> Update
          </button>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormUsers from './Form.vue'
    export default {
        name: 'EditUsers',
        data() {
            return {
              alert: false,
              loading: false,
            }
        },
        created() {
            this.editUsers(this.$route.params.id)
        },
        methods: {
            ...mapActions('users', ['editUsers', 'updateUsers']),
            submit() {
                this.loading = true
                this.updateUsers(this.$route.params.id).then(() => {
                  this.loading = false
                  this.alert = true
                  setTimeout(() => {
                      this.alert = false
                  }, 2000)
                })
            }
        },
        components: {
            'users-form': FormUsers
        },
    }
</script>
