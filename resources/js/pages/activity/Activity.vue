<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <b-button @click="create()" variant="light" v-b-popover.hover.top="'Data activity baru hanya bisa dibuat pada menu assigment, dan tombol ini akan mengantarkan anda ke halaman tersebut.'" title="Buat Data Activity Baru">
          <i class="fas fa-plus"></i> Buat data baru
        </b-button>
      </div>
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
        <b-table striped hover small :items="activitys.data" :fields="fields" show-empty>
          <template v-slot:cell(actions)="row">
            <b-button-group>
              <b-button variant="light" v-if="$can('activity-list')" @click="view(row.item.id)">
                <i class="fas fa-binoculars"></i>
              </b-button>
              <b-button variant="light" v-if="$can('activity-edit')" @click="edit(row.item.id)">
                <i class="fas fa-edit"></i>
              </b-button>
              <b-button variant="light" v-if="$can('activity-delete')" @click="deleted(row.item.id)">
                <i class="fas fa-trash"></i>
              </b-button>
            </b-button-group>
          </template>
          <template v-slot:cell(janji_byr)="row">
            <span class="right badge badge-success" v-if="row.item.janji_byr == 'Y'">
            <i class="fas fa-clipboard-check"></i>  Promise
            </span>
            <span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Unpromise</span>
          </template>
        </b-table>
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-md-6">
            <p v-if="activitys.data"><i class="fa fa-bars"></i> {{ activitys.data.length }} item dari {{ activitys.meta.total }} total data</p>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <b-pagination
                v-model="page" pills
                :total-rows="activitys.meta.total"
                :per-page="activitys.meta.per_page"
                aria-controls="users"
                v-if="activitys.data && activitys.data.length > 0"
                >
            </b-pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    name: 'DataActivity',
    created() {
        this.getAct()
    },
    data() {
        return {
            fields: [
                { key: 'actions', label: '#' },
                { key: 'bertemu', label: 'Bertemu Dgn' },
                { key: 'janji_byr', label: 'Janji Bayar' },
                { key: 'keterangan', label: 'Ket.' },
                { key: 'next_action', label: 'Langkah Selanjutnya' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('activity', {
            activitys: state => state.activitys,
            loading: state => state.loading
        }),
        page: {
            get() {
                return this.$store.state.activity.page
            },
            set(val) {
                this.$store.commit('activity/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getAct()
        },
        search() {
            this.getAct(this.search)
        }
    },
    methods: {
        ...mapState('user', {
            authenticated: state => state.authenticated
        }),
        ...mapActions('activity', ['getAct', 'removeAct']),
        deleted(id) {
            this.$swal({
                title: 'Kamu Yakin?',
                text: "Tindakan ini akan menghapus secara permanent!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!'
            }).then((result) => {
                if (result.value) {
                    this.removeAct(id)
                }
            })
        },
        view(id){
          this.$router.push({ name: 'activity.show', params: {id: id} })
        },
        edit(id){
          this.$router.push({ name: 'activity.edit', params: {id: id} })
        },
        create(){
          this.$router.push({ name: 'assigment.data'})
        }
    }
}
</script>
