<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <b-button @click="create()" variant="light" v-b-popover.hover.top="'Data info collections baru hanya bisa dibuat setelah anda mengisi form pembuatan informasi, dan tombol ini akan mengantarkan anda ke halaman tersebut.'" title="Buat Data Informasi Baru">
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
          <b-table striped hover small :items="collections.data" :fields="fields" show-empty>
            <template v-slot:cell(actions)="row">
              <b-button-group>
                <b-button variant="light" v-if="$can('infocoll-list')" @click="view(row.item.id)">
                  <i class="fas fa-binoculars"></i>
                </b-button>
                <b-button variant="light" v-if="$can('infocoll-edit')" @click="edit(row.item.id)">
                  <i class="fas fa-edit"></i>
                </b-button>
                <b-button variant="light" v-if="$can('infocoll-delete')" @click="deleted(row.item.id)">
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
              <p v-if="collections.data"><i class="fa fa-bars"></i> {{ collections.data.length }} item dari {{ collections.meta.total }} total data</p>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <b-pagination
                  v-model="page" pills
                  :total-rows="collections.meta.total"
                  :per-page="collections.meta.per_page"
                  aria-controls="users"
                  v-if="collections.data && collections.data.length > 0"
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
        this.getData()
    },
    data() {
        return {
            fields: [
                { key: 'title', label: 'Title' },
                { key: 'slug', label: 'Slug' },
                { key: 'state', label: 'State' },
                { key: 'actions', label: 'Actions' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('collection', {
            collections: state => state.collections,
            loading: state => state.loading
        }),
        page: {
            get() {
                return this.$store.state.collection.page
            },
            set(val) {
                this.$store.commit('collection/SET_PAGE', val)
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
        ...mapActions('collection', ['getData', 'removeCollection', 'print']),
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
                    this.removeCollection(id)
                }
            })
        },
        create(){
          this.$router.push({ name: 'collection.add' })
        },
        view(id){
          this.$router.push({ name: 'collection.show', params: {id: id} })
        },
        edit(id){
          this.$router.push({ name: 'collection.edit', params: {id: id} })
        },
    }
}
</script>
