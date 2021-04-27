<template>
  <div class="content-wrapper">
    <!-- Main content -->
    <breadcrumb></breadcrumb>
    <div class="content">
      <div class="container-fluid">
        <div class="card card-warning card-outline">
          <div class="card-header">
            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search" v-model="search">
                <div class="input-group-append">
                  <span class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <div class="overlay-wrapper">
              <div class="overlay" v-if="loading">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                <div class="text-bold pt-2">
                  Loading...
                </div>
              </div>
              <b-table striped hover bordered :items="notif.data" :fields="fields" show-empty>
                <template #cell(info)="data">
                  <strong>Desc : </strong>{{ data.item.desc }}<br/>
                  <strong>Times : </strong>{{ data.item.created_at }}<br/>
                  <span class="right badge badge-success" v-if="data.item.show == 'y'"><i class="fas fa-clipboard-check"></i>  Read</span><span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Unread</span><br/>
                  <div class="mt-3">
                    <b-button variant="light" @click="move(data.item.id)">
                      <i class="fas fa-chevron-right"></i> Lihat informasi
                    </b-button>
                  </div>
                </template>
              </b-table>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-6">
                  <p v-if="notif.data"><i class="fa fa-bars"></i> {{ notif.data.length }} item dari {{ notif.meta.total }} total data</p>
              </div>
              <div class="col-md-6">
                <div class="pull-right">
                  <b-pagination
                      v-model="page" pills
                      :total-rows="notif.meta.total"
                      :per-page="notif.meta.per_page"
                      aria-controls="users"
                      v-if="notif.data && notif.data.length > 0"
                      >
                  </b-pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import Breadcrumb from '../components/Breadcrumb.vue'
    export default {
        name: 'Notification',
        created() {
            this.getData()
        },
        data() {
            return {
                fields: [
                    { key: 'info', label: 'Informasi' }
                ],
                search: ''
            }
        },
        computed: {
            ...mapState('notification', {
                notif: state => state.notif,
                loading: state => state.loading
            }),
            page: {
                get() {
                    return this.$store.state.notification.page
                },
                set(val) {
                    this.$store.commit('notification/SET_PAGE', val)
                }
            }
        },
        watch: {
            page() {
                this.getData()
            },
            search() {
                this.getData(this.search)
            }
        },
        methods: {
            ...mapActions('notification', ['getData','checkD']),
            move(id) {
              this.checkD(id).then((response) => {
                if (response === 'activity') {
                  this.$router.push({ name: 'activity.data' })
                }else if (response === 'visit') {
                  this.$router.push({ name: 'visittt.data' })
                }else {
                  this.$router.push({ name: 'payment.data' })
                }
              })
            }
        },
        components: {
            'breadcrumb': Breadcrumb
        }
    }
</script>
