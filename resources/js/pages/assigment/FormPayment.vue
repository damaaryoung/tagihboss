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
            <input type="text" class="form-control" v-model="payments.task_code" readonly>
          </div>
          <p class="text-danger" v-if="errors.task_code">{{ errors.task_code[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.nama }">
          <label for="">Nama Nasabah</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.nama" readonly>
          </div>
          <p class="text-danger" v-if="errors.nama">{{ errors.nama[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.no_rek }">
          <label for="">No. Rekening</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-credit-card"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.no_rek" readonly>
          </div>
          <p class="text-danger" v-if="errors.no_rek">{{ errors.no_rek[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.os_pokok }">
          <label for="">OS Pokok</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-hand-holding-usd"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.os_pokok" readonly>
          </div>
          <p class="text-danger" v-if="errors.os_pokok">{{ errors.os_pokok[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.a_tertunggak }">
          <label for="">Angsuran Tertunggak</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.a_tertunggak" readonly>
          </div>
          <p class="text-danger" v-if="errors.a_tertunggak">{{ errors.a_tertunggak[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.b_tertunggak }">
          <label for="">Biaya Tertunggak</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.b_tertunggak" readonly>
          </div>
          <p class="text-danger" v-if="errors.b_tertunggak">{{ errors.b_tertunggak[0] }}</p>
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
            <input type="number" min="0" class="form-control" v-model="payments.angsuran" v-on:keyup="handdlingA($event)">
          </div>
          <p class="text-danger" v-if="errors.angsuran">{{ errors.angsuran[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.bayar_denda }">
          <label for="">Bayar Denda</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payments.bayar_denda" v-on:keyup="handdlingB($event)">
          </div>
          <p class="text-danger" v-if="errors.bayar_denda">{{ errors.bayar_denda[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.collect_fee }">
          <label for="">Collect fee</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-coins"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payments.collect_fee" v-on:keyup="handdlingC($event)">
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
            <input type="number" min="0" class="form-control" v-model="payments.titipan" v-on:keyup="handdlingD($event)">
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
            <input type="number" min="0" class="form-control" v-model="payments.ang_ke">
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
            <input type="number" min="0" class="form-control" v-model="payments.tenor">
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
            <input type="number" min="0" class="form-control" v-model="payments.no_bss">
          </div>
          <p class="text-danger" v-if="errors.no_bss">{{ errors.no_bss[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.total_bayar_angsuran }">
          <label for="">Total Bayar Angsuran</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-sticky-note"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payments.total_bayar_angsuran">
          </div>
          <p class="text-danger" v-if="errors.total_bayar_angsuran">{{ errors.total_bayar_angsuran[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.sisa_angsuran }">
          <label for="">Sisa Angsuran</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-balance-scale"></i></span>
            </div>
            <input type="number" min="0" class="form-control" v-model="payments.sisa_angsuran">
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
            <input type="number" min="0" class="form-control" v-model="payments.sisa_denda">
          </div>
          <p class="text-danger" v-if="errors.sisa_denda">{{ errors.sisa_denda[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.tgl_jt }">
          <label for="">Tgl. Jatuh Tempo</label>
          <div class="input-group">
            <b-form-datepicker id="example-datepicker" v-model="payments.tgl_jt" class="mb-2" readonly>
            </b-form-datepicker>
          </div>
          <p class="text-danger" v-if="errors.tgl_jt">{{ errors.tgl_jt[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.take_foto }">
          <label for="">Take Foto</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon3"><i class="fas fa-camera"></i></span>
            </div>
            <div class="custom-file">
              <input type="file" accept="image/*" capture class="custom-file-input" ref="imgPayment" v-on:change="imgPayment()">
              <label class="custom-file-label" for="inputGroupFile04"></label>
            </div>
          </div>
          <p class="text-danger" v-if="errors.take_foto">{{ errors.take_foto[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.signature64Nasabah }">
          <label for="">Signature Nasabah</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-signature"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.signature64Nasabah" readonly>
          </div>
          <p class="text-danger" v-if="errors.signature64Nasabah">{{ errors.signature64Nasabah[0] }}</p>
        </div>
      </div>
      <div class="col-md">
        <div class="form-group" :class="{ 'has-error': errors.signature64Collection }">
          <label for="">Signature Collections</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-signature"></i></span>
            </div>
            <input type="text" class="form-control" v-model="payments.signature64Collection" readonly>
          </div>
          <p class="text-danger" v-if="errors.signature64Collection">{{ errors.signature64Collection[0] }}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md mt-2">
        <div class="card">
          <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
              <i class="fas fa-file-signature mr-1"></i>
              Signature Nasabah
            </h3>
          </div>
          <div class="card-body">
            <VueSignaturePad
              id="signatureNas"
              width="100%"
              height="100%"
              ref="signaturePadNas"
              :options="options"
            />
          </div>
          <div class="card-footer clearfix">
            <button type="button" class="btn btn-warning float-left btn-sm" @click="undoNas"><i class="fas fa-undo-alt"></i> Undo</button>
            <button type="button" class="btn btn-primary float-right btn-sm" @click="saveNas"><i class="fas fa-save"></i> Save Signature</button>
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
            <VueSignaturePad
              id="signatureColl"
              width="100%"
              height="100%"
              ref="signaturePadColl"
              :options="options"
            />
          </div>
          <div class="card-footer clearfix">
            <button type="button" class="btn btn-warning float-left btn-sm" @click="undoColl"><i class="fas fa-undo-alt"></i> Undo</button>
            <button type="button" class="btn btn-primary float-right btn-sm" @click="saveColl"><i class="fas fa-save"></i> Save Signature</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormPayment',
    data: () => ({
      options: {
        penColor: "#000000"
      }
    }),
    computed: {
        ...mapState(['errors']),
        ...mapState('assigment', {
            payments: state => state.payment,
        })
    },
    methods: {
        ...mapActions('assigment', []),
        ...mapMutations('assigment', ['CLEAR_FORM_PAYMENT']),
        handdlingA($event){
          this.$store.commit('assigment/SET_CALLCULATIONS_PAYMENT_A', $event.target.value)
        },
        handdlingB($event){
          this.$store.commit('assigment/SET_CALLCULATIONS_PAYMENT_B', $event.target.value)
        },
        handdlingC($event){
          this.$store.commit('assigment/SET_CALLCULATIONS_PAYMENT_C', $event.target.value)
        },
        handdlingD($event){
          this.$store.commit('assigment/SET_CALLCULATIONS_PAYMENT_D', $event.target.value)
        },
        imgPayment(){
          this.$store.commit('assigment/SET_PAYMENT_FOTO', this.$refs.imgPayment.files[0])
        },
        undoNas() {
          this.$refs.signaturePadNas.undoSignature();
          this.$store.commit('assigment/SET_SIGNATURE_NAS', this.$refs.signaturePadNas.undoSignature())
        },
        saveNas() {
          const { data } = this.$refs.signaturePadNas.saveSignature();
          this.$store.commit('assigment/SET_SIGNATURE_NAS', data.replace(/^data\:image\/\w+\;base64\,/, ''))
          this.$swal('Terimakasih','Signature nasabah ditambahkan!','success')
        },
        undoColl() {
          this.$refs.signaturePadColl.undoSignature();
          this.$store.commit('assigment/SET_SIGNATURE_COLL', this.$refs.signaturePadColl.undoSignature())
        },
        saveColl() {
          const { data } = this.$refs.signaturePadColl.saveSignature();
          this.$store.commit('assigment/SET_SIGNATURE_COLL', data.replace(/^data\:image\/\w+\;base64\,/, ''))
          this.$swal('Good Job','Signature collections ditambahkan!','success')
        }
    },
    destroyed() {
        this.CLEAR_FORM_PAYMENT()
    }
}
</script>
