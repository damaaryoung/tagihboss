<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-warning">
        <div class="card-body">
          <div class="overlay-wrapper">
            <div class="overlay" v-if="loading">
              <i class="fas fa-3x fa-sync-alt fa-spin"></i>
              <div class="text-bold pt-2">
                Loading...
              </div>
            </div>
              <info-form></info-form>
          </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-warning btn-sm btn-flat float-right" @click.prevent="_Save">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import Form from './Form.vue'
    export default {
        name: 'AddUsers',
        data() {
            return {
              loading: false,
            }
        },
        methods: {
            ...mapActions('documentation', ['Save']),
            _Save() {
                this.loading = true
                this.Save().then((response) => {
                  this.loading = false
                  this.$bvToast.toast(response['message'], {
                    title: `created ${response['status']}`,
                    variant: response['status'],
                    solid: true
                  })
                })
                this.loading = false
            }
        },
        components: {
            'info-form': Form,
        }
    }
</script>
