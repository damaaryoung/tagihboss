<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <router-link :to="{ name: 'users.add' }" class="btn btn-light btn-sm btn-flat">
          <i class="fas fa-plus"></i> Buat user baru
        </router-link>
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
        <b-table striped hover bordered :items="users.data" :fields="fields" show-empty>
          <template v-slot:cell(actions)="row">
            <b-button-group>
              <b-button variant="light" v-if="$can('user-list')" @click="view(row.item.id)">
                <i class="fas fa-binoculars"></i>
              </b-button>
              <b-button variant="light" v-if="$can('user-edit')" @click="edit(row.item.id)">
                <i class="fas fa-edit"></i>
              </b-button>
              <b-button variant="light" v-if="$can('user-delete')" @click="deleted(row.item.id)">
                <i class="fas fa-trash"></i>
              </b-button>
            </b-button-group>
          </template>
        </b-table>
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-md-6">
            <p v-if="users.data"><i class="fa fa-bars"></i> {{ users.data.length }} item dari {{ users.meta.total }} total data</p>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <b-pagination
                v-model="page" pills
                :total-rows="users.meta.total"
                :per-page="users.meta.per_page"
                aria-controls="users"
                v-if="users.data && users.data.length > 0"
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
    name: 'DataUsers',
    created() {
        this.getUsers()
    },
    data() {
        return {
            fields: [
                { key: 'actions', label: '#' },
                { key: 'name', label: 'Nama Users' },
                { key: 'email', label: 'Alamat Email' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('users', {
            users: state => state.users,
            loading: state => state.loading,
        }),
        page: {
            get() {
                return this.$store.state.users.page
            },
            set(val) {
                this.$store.commit('users/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getUsers()
        },
        search() {
            this.getUsers(this.search)
        }
    },
    methods: {
        ...mapActions('users', ['getUsers', 'removeUsers', 'showUsers']),
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
                    this.removeUsers(id)
                }
            })
        },
        view(id){
          this.$store.commit('users/SET_HISTORYID', id);
          this.$router.push({ name: 'users.show'})
        },
        edit(id){
          this.$router.push({ name: 'users.edit', params: {id: id} })
        },
    }
}
</script>
