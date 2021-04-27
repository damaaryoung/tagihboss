<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Update Collection Info Data</h3>
      </div>
        <div class="card-body">
          <div class="overlay-wrapper">
            <div class="overlay" v-if="loading">
              <i class="fas fa-3x fa-sync-alt fa-spin"></i>
              <div class="text-bold pt-2">
                Loading...
              </div>
            </div>
              <coll-form></coll-form>
          </div>
        </div>
        <div class="card-footer">
          <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
              <i class="fa fa-save"></i> Update
          </button>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import Form from './Form.vue'
    export default {
        name: 'Edit',
        data() {
            return {
              loading: false,
            }
        },
        created() {
            this.editCollection(this.$route.params.id)
        },
        methods: {
            ...mapActions('collection', ['editCollection', 'updateCollection']),
            submit() {
                this.loading = true
                this.updateCollection(this.$route.params.id).then((response) => {
                  this.loading = false
                  this.$bvToast.toast(response['message'], {
                    title: `created ${response['status']}`,
                    variant: response['status'],
                    solid: true
                  })
                })
            }
        },
        components: {
            'coll-form': Form
        },
    }
</script>
