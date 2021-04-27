<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <b-button @click="create()" variant="light" v-b-popover.hover.top="'Data info documentations baru hanya bisa dibuat setelah anda mengisi form pembuatan informasi, dan tombol ini akan mengantarkan anda ke halaman tersebut.'" title="Buat Data Informasi Baru">
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
          <b-table striped hover small :items="documentations.data" :fields="fields" show-empty>
            <template v-slot:cell(actions)="row">
              <b-button-group>
                <b-button variant="light" v-if="$can('documentation-list')" @click="view(row.item.id)">
                  <i class="fas fa-binoculars"></i>
                </b-button>
                <b-button variant="light" v-if="$can('documentation-edit')" @click="edit(row.item.id)">
                  <i class="fas fa-edit"></i>
                </b-button>
                <b-button variant="light" v-if="$can('documentation-delete')" @click="deleted(row.item.id)">
                  <i class="fas fa-trash"></i>
                </b-button>
              </b-button-group>
            </template>
            <template v-slot:cell(state)="row">
              <span class="right badge badge-success" v-if="row.item.state == 'active'">
              <i class="fas fa-clipboard-check"></i>  Active
              </span>
              <span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Unactive</span>
            </template>
          </b-table>
        </div>
        <div class="row p-3">
          <div class="col-md-6">
              <p v-if="documentations.data"><i class="fa fa-bars"></i> {{ documentations.data.length }} item dari {{ documentations.meta.total }} total data</p>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <b-pagination
                  v-model="page" pills
                  :total-rows="documentations.meta.total"
                  :per-page="documentations.meta.per_page"
                  aria-controls="users"
                  v-if="documentations.data && documentations.data.length > 0"
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
    name: 'DataDocumentation',
    created() {
        this.getData()
    },
    data() {
        return {
            fields: [
                { key: 'title', label: 'title'},
                { key: 'slug', label: 'slug'},
                { key: 'authorization', label: 'authorization'},
                { key: 'state', label: 'state'},
                { key: 'created_by_name', label: 'created' },
                { key: 'actions', label: '#'}
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('documentation', {
            documentations: state => state.documentations,
            loading: state => state.loading
        }),
        page: {
            get() {
                return this.$store.state.documentation.page
            },
            set(val) {
                this.$store.commit('documentation/SET_PAGE', val)
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
        ...mapState('user', {
            authenticated: state => state.authenticated
        }),
        ...mapActions('documentation', ['getData', 'Remove', 'print']),
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
                    this.Remove(id)
                }
            })
        },
        create(){
          this.$router.push({ name: 'documentation.add' })
        },
        view(id){
          this.$router.push({ name: 'documentation.show', params: {id: id} })
        },
        edit(id){
          this.$router.push({ name: 'documentation.edit', params: {id: id} })
        },
    }
}
</script>
