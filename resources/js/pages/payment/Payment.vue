<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <div class="card-title">
        <b-button @click="create()" variant="light" v-b-popover.hover.top="'Data payment baru hanya bisa dibuat pada menu assigment, dan tombol ini akan mengantarkan anda ke halaman tersebut.'" title="Buat Data Activity Baru">
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
          <b-table striped hover small :items="payments.data" :fields="fields" show-empty>
            <template v-slot:cell(actions)="row">
              <b-button-group size="sm">
                <b-button variant="light" v-if="$can('visit-list')" @click="download(row.item.id)">
                  <i class="fas fa-cloud-download-alt"></i>
                </b-button>
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
              <p v-if="payments.data"><i class="fa fa-bars"></i> {{ payments.data.length }} item dari {{ payments.meta.total }} total data</p>
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <b-pagination
                  v-model="page" pills
                  :total-rows="payments.meta.total"
                  :per-page="payments.meta.per_page"
                  aria-controls="users"
                  v-if="payments.data && payments.data.length > 0"
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
                { key: 'nama_nasabah', label: 'Nama Nasabah' },
                { key: 'no_rekening', label: 'OS. Pokok' },
                { key: 'total_bayar', label: 'T. Angsuran' },
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('payment', {
            payments: state => state.payments,
            loading: state => state.loading
        }),
        page: {
            get() {
                return this.$store.state.payment.page
            },
            set(val) {
                this.$store.commit('payment/SET_PAGE', val)
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
        ...mapActions('payment', ['getActTT', 'removePayment', 'print']),
        create(){
          this.$router.push({ name: 'assigment.data'})
        },
        download(id){
          this.print(id)
        },
        view(id){
          this.$router.push({ name: 'payment.show', params: {id: id} })
        },
        edit(id){
          this.$router.push({ name: 'payment.edit', params: {id: id} })
        },
        deleted(id){
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
                  this.removePayment(id)
              }
          })
        }
    }
}
</script>
