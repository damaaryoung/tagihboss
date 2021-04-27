<template>
  <div class="card card-warning card-outline">
    <div class="card-header">
      <h5>lat: {{location.lat}} || long: {{location.long}}</h5>
      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 250px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Search" v-model="search">
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
      <div class="overlay-wrapper">
        <div class="overlay" v-if="loading">
          <i class="fas fa-3x fa-sync-alt fa-spin"></i>
          <div class="text-bold pt-2">
            Loading...
          </div>
        </div>
          <!-- table -->
          <b-table striped hover small :items="assigment.data" :fields="fields" show-empty>
            <template #cell(nasabah)="data">
              <strong>Nama Nasabah : </strong>{{ data.item.nama_nasabah }}<br/>
              <strong>No. Rek :</strong> {{ data.item.no_rekening }}<br/>
              <strong>Alamat :</strong> {{ data.item.alamat }}<br/>
              <span class="right badge badge-success" v-if="data.item.status_activity == 1"><i class="fas fa-clipboard-check"></i>  Activity After Created</span><span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Activity Before Created</span><br/>
              <span class="right badge badge-success" v-if="data.item.status_visit == 1"><i class="fas fa-clipboard-check"></i>  Visit After Created</span><span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Visit Before Created</span><br/>
              <span class="right badge badge-success" v-if="data.item.status_payment == 1"><i class="fas fa-clipboard-check"></i>  Payment After Created</span><span class="right badge badge-danger" v-else><i class="fas fa-times-circle"></i> Payment Before Created</span><br/>
              <div class="mt-3">
                <b-button @click="create(data.item.task_code)" size="sm" variant="light" v-b-popover.hover.top="'Aksi assigment, tindakan ini dilakukan untuk membuat aksi/mengeksekusi/membuat tindakan pada data assigment yang dipilih baik activity, visit, maupun payment.'" title="Tindak assigment anda disini.">
                  <i class="fas fa-business-time"></i> Buat tindakan sekarang!
                </b-button>
              </div>
            </template>
          </b-table>
          <!-- table -->
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-md-6">
            <p v-if="assigment.data"><i class="fa fa-bars"></i> {{ assigment.data.length }} item dari {{ assigment.meta.total }} total data</p>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <b-pagination
                v-model="page" pills
                :total-rows="assigment.meta.total"
                :per-page="assigment.meta.per_page"
                aria-controls="assigment"
                v-if="assigment.data && assigment.data.length > 0"
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
    name: 'DataAssigment',
    created() {
        this.getAssigment(),
        this.getLocation()
    },
    data() {
        return {
            fields: [
                { key: 'nasabah', label: 'Nasabah' }
            ],
            search: '',
        }
    },
    computed: {
        ...mapState('assigment', {
            assigment: state => state.assigments,
            loading: state => state.loading,
            location: state => state.location
        }),
        page: {
            get() {
                return this.$store.state.assigment.page
            },
            set(val) {
                this.$store.commit('assigment/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getAssigment()
        },
        search() {
            this.getAssigment(this.search)
        }
    },
    methods: {
        ...mapActions('assigment', ['getAssigment','getLocation', 'removeAssigment']),
        deleteAssigment(id) {
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
                    this.removeAssigment(id)
                }
            })
        },
        create(id){
          this.$router.push({ name: 'assigment.act', params: {id: id} })
        }
      }
}
</script>
