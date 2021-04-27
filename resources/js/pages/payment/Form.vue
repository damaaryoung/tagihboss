<template>
  <div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.task_code }">
          <label for="">Task Code</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payment.task_code" readonly>
          </div>
          <p class="text-danger" v-if="errors.task_code">{{ errors.task_code[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.nama_nasabah }">
          <label for="">Nama Nasabah</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payment.nama_nasabah" readonly>
          </div>
          <p class="text-danger" v-if="errors.nama_nasabah">{{ errors.nama_nasabah[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.no_rekening }">
          <label for="">No. Rekening</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-credit-card"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payment.no_rekening" readonly>
          </div>
          <p class="text-danger" v-if="errors.no_rekening">{{ errors.no_rekening[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.collect_fee }">
          <label for="">OS Pokok</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-hand-holding-usd"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payment.collect_fee" readonly>
          </div>
          <p class="text-danger" v-if="errors.collect_fee">{{ errors.collect_fee[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.angsuran }">
          <label for="">Angsuran</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.angsuran" v-on:keyup="handdlingA($event)">
          </div>
          <p class="text-danger" v-if="errors.angsuran">{{ errors.angsuran[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.denda }">
          <label for="">Bayar Denda</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.denda" v-on:keyup="handdlingB($event)">
          </div>
          <p class="text-danger" v-if="errors.denda">{{ errors.denda[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.collect_fee }">
          <label for="">Collect fee</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.collect_fee" v-on:keyup="handdlingC($event)">
          </div>
          <p class="text-danger" v-if="errors.collect_fee">{{ errors.collect_fee[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.titipan }">
          <label for="">Titipan</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.titipan" v-on:keyup="handdlingD($event)">
          </div>
          <p class="text-danger" v-if="errors.titipan">{{ errors.titipan[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.ang_ke }">
          <label for="">Angsuran ke</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search-dollar"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.ang_ke">
          </div>
          <p class="text-danger" v-if="errors.ang_ke">{{ errors.ang_ke[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.tenor }">
          <label for="">Tenor</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-receipt"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.tenor">
          </div>
          <p class="text-danger" v-if="errors.tenor">{{ errors.tenor[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.no_bss }">
          <label for="">No. BSS</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.no_bss">
          </div>
          <p class="text-danger" v-if="errors.no_bss">{{ errors.no_bss[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.total_bayar }">
          <label for="">Total Bayar Angsuran</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-sticky-note"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.total_bayar">
          </div>
          <p class="text-danger" v-if="errors.total_bayar">{{ errors.total_bayar[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.sisa_angsuran }">
          <label for="">Sisa Angsuran</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-balance-scale"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.sisa_angsuran">
          </div>
          <p class="text-danger" v-if="errors.sisa_angsuran">{{ errors.sisa_angsuran[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.sisa_denda }">
          <label for="">Sisa Denda</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-balance-scale"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payment.sisa_denda">
          </div>
          <p class="text-danger" v-if="errors.sisa_denda">{{ errors.sisa_denda[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.tgl_jt_tempo }">
          <label for="">Tgl. Jatuh Tempo</label>
          <div class="input-group">
            <b-form-datepicker id="example-datepicker" v-model="payment.tgl_jt_tempo" class="mb-2" readonly>
            </b-form-datepicker>
          </div>
          <p class="text-danger" v-if="errors.tgl_jt_tempo">{{ errors.tgl_jt_tempo[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md mt-2">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
              <i class="fas fa-camera mr-1"></i>
              <img :src="baseUrl+'/storage/'+payment.file" alt="Example" class="img-fluid">
            </h3>
          </div>
          <div class="card-body">
            foto selfi
          </div>
        </div>
      </div>
      <div class="col-md mt-2">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
              <i class="fas fa-file-signature mr-1"></i>
              Signature Nasabah
            </h3>
          </div>
          <div class="card-body">
            <img :src="baseUrl+'/storage/'+payment.file_ttd_nasabah" alt="Example" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="col-md mt-2">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
              <i class="fas fa-file-signature mr-1"></i>
              Signature Collections
            </h3>
          </div>
          <div class="card-body">
            <img :src="baseUrl+'/storage/'+payment.file_ttd_collection" alt="Example" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormVisit',
    computed: {
        ...mapState('user', {
            baseUrl: state => state.baseUrl
        }),
        ...mapState(['errors']),
        ...mapState('payment', {
            payment: state => state.payment
        })
    },
    methods: {
        ...mapMutations('payment', ['CLEAR_FORM']),
    },
    destroyed() {
        this.CLEAR_FORM()
    }
}
</script>
