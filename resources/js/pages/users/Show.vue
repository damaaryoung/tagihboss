<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Show Users Data</h3>
      </div>
        <div class="card-body">
          <div class="overlay-wrapper">
            <div class="overlay" v-if="loading">
              <i class="fas fa-3x fa-sync-alt fa-spin"></i>
              <div class="text-bold pt-2">
                Loading...
              </div>
            </div>
            <div class="table-responsive">
              <b-table striped hover bordered :items="history.data" :fields="fields" show-empty>
              </b-table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <b-pagination
              v-model="page"
              :total-rows="history.meta.total"
              :per-page="history.meta.per_page"
              aria-controls="users"
              v-if="history.data && history.data.length > 0"
              >
          </b-pagination>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormUsers from './Form.vue'
    export default {
        name: 'EditUsers',
        data(){
          return{
            fields: [
                { key: 'title', label: 'Title' },
                { key: 'event', label: 'Event Action' },
                { key: 'description', label: 'Description' },
                { key: 'device', label: 'Device' },
                { key: 'platform', label: 'Platform/OS' },
                { key: 'ip_address', label: 'IP Address' },
                { key: 'agent', label: 'Agent' }
            ],
            loading:false
          }
        },
        created() {
            this.showUsers()
        },
        computed: {
            ...mapState('users', {
                history: state => state.history
            }),
            page: {
                get() {
                    return this.$store.state.users.page2
                },
                set(val) {
                    this.$store.commit('users/SET_PAGE2', val)
                }
            }
        },
        watch: {
            page() {
                this.loading=true
                this.showUsers()
                this.loading=false
            }
        },
        methods: {
            ...mapActions('users', ['showUsers']),
        }
    }
</script>
