<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <b-button @click="create()" variant="light" v-b-popover.hover.top="'Data visit baru hanya bisa dibuat pada menu assigment, dan tombol ini akan mengantarkan anda ke halaman tersebut.'" title="Buat Data Activity Baru">
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
    <div class="card-body row">
      <div class="col-md table-responsive">
        <div class="overlay-wrapper">
          <div class="overlay" v-if="loading">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">
              Loading...
            </div>
          </div>
          <b-table striped hover small :items="visit_a_data.data" :fields="fields" show-empty>
            <template v-slot:cell(actions)="row">
              <b-button-group size="sm">
                <b-button variant="light" v-if="$can('visit-list')" @click="view(row.item.id)">
                  <i class="fas fa-binoculars"></i>
                </b-button>
                <b-button variant="light" v-if="$can('visit-edit')" @click="edit(row.item.id)">
                  <i class="fas fa-edit"></i>
                </b-button>
                <b-button variant="light" v-if="$can('visit-delete')" @click="deleted(row.item.id)">
                  <i class="fas fa-trash"></i>
                </b-button>
              </b-button-group>
            </template>
          </b-table>
        </div>
        <div class="row p-3">
          <div class="col-md-6">
              <p v-if="visit_a_data.data"><i class="fa fa-bars"></i> {{ visit_a_data.data.length }} item dari {{ visit_a_data.meta.total }} total data</p>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <b-pagination
                  v-model="page" pills
                  :total-rows="visit_a_data.meta.total"
                  :per-page="visit_a_data.meta.per_page"
                  aria-controls="users"
                  v-if="visit_a_data.data && visit_a_data.data.length > 0"
                  >
              </b-pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    name: 'DataVisit',
    created() {
        this.getActTT()
    },
    data() {
        return {
            fields: [
                { key: 'actions', label: '#' },
                { key: 'task_code', label: 'Taskcode' },
                { key: 'kondisi_tempat', label: 'Kondisi' },
                { key: 'latitude', label: 'Lat' },
                { key: 'longitude', label: 'Long' },
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('visitjm', {
            visit_a_data: state => state.visit_a_data,
            loading: state => state.loading,
        }),
        page: {
            get() {
                return this.$store.state.visitjm.page
            },
            set(val) {
                this.$store.commit('visitjm/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getActTT()
        },
        search() {
            this.getActTT(this.search)
        }
    },
    methods: {
        ...mapActions('visitjm', ['getActTT', 'removeVisit']),
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
                    this.removeVisit(id)
                }
            })
        },
        view(id){
          this.$router.push({ name: 'visitjm.show', params: {id: id} })
        },
        edit(id){
          this.$router.push({ name: 'visitjm.edit', params: {id: id} })
        },
        create(){
          this.$router.push({ name: 'assigment.data'})
        }
    }
}
</script>
