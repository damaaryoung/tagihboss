<template>
    <div>
      <div class="row">
          <div class="col-md">
            <div class="form-group" :class="{ 'has-error': errors.task_code }">
                <label for="">task code</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="number" class="form-control" min="1" v-model="activity.task_code" placeholder="enter name users..." readonly>
                </div>
                <p class="text-danger" v-if="errors.task_code">{{ errors.task_code[0] }}</p>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group" :class="{ 'has-error': errors.kunjungan_ke }">
                <label for="">kunjungan ke</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-signs"></i></span>
                  </div>
                  <input type="number" class="form-control" min="1" v-model="activity.kunjungan_ke" placeholder="enter visit at...">
                </div>
                <p class="text-danger" v-if="errors.kunjungan_ke">{{ errors.kunjungan_ke[0] }}</p>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group" :class="{ 'has-error': errors.bertemu }">
                <label for="">bertemu dengan</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-users"></i></span>
                  </div>
                  <select class="custom-select form-control" v-model="activity.bertemu">
      							<option value="">Selected</option>
      							<option value="debitur/pasangan">Debitur/Pasangan</option>
      							<option value="keluarga">Keluarga</option>
      							<option value="tetangga">Tetangga</option>
      							<option value="orang lain">Orang lain</option>
      						</select>
                </div>
                <p class="text-danger" v-if="errors.bertemu">{{ errors.bertemu[0] }}</p>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group" :class="{ 'has-error': errors.karakter_debitur }">
                <label for="">karakter debitur</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-american-sign-language-interpreting"></i></span>
                  </div>
                  <select class="custom-select form-control" v-model="activity.karakter_debitur">
      							<option value="">Selected</option>
      							<option value="noncorporative">Tidak Kooperatif</option>
      							<option value="corporative">Kooperatif</option>
      						</select>
                </div>
                <p class="text-danger" v-if="errors.karakter_debitur">{{ errors.karakter_debitur[0] }}</p>
            </div>
          </div>
          <div class="col-md">
            <div class="form-group" :class="{ 'has-error': errors.total_penghasilan }">
                <label for="">total penghasilan</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-funnel-dollar"></i></span>
                  </div>
                  <input type="number" class="form-control" min="100000" v-model="activity.total_penghasilan">
                </div>
                <p class="text-danger" v-if="errors.total_penghasilan">{{ errors.total_penghasilan[0] }}</p>
            </div>
          </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.kondisi_pekerjaan }">
              <label for="">kondisi pekerjaan</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-briefcase"></i></span>
                </div>
                <select class="form-control" v-model="activity.kondisi_pekerjaan">
                    <option value="">Pilih</option>
                    <option v-for="(row, index) in kon_pekOptions" :value="row.options" :key="index">{{ row.options }}</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.kondisi_pekerjaan">{{ errors.kondisi_pekerjaan[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.asset_debt }">
              <label for="">asset debitur</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-shopping-basket"></i></span>
                </div>
                <select class="form-control" v-model="activity.asset_debt">
                    <option value="">Pilih</option>
                    <option v-for="(row, index) in asset_debOptions" :value="row.options" :key="index">{{ row.options }}</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.asset_debt">{{ errors.asset_debt[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.janji_byr }">
              <label for="">Janji bayar</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-glass-cheers"></i></span>
                </div>
                <select class="form-control" v-model="activity.janji_byr" v-on:change="handleTglJB($event)">
                  <option value="Y">Ya</option>
                  <option value="N">Tidak</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.janji_byr">{{ errors.janji_byr[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.case_category }">
              <label for="">case category</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-podcast"></i></span>
                </div>
                <select class="form-control" v-model="activity.case_category">
                    <option value="">Pilih</option>
                    <option v-for="(row, index) in case_categoryOptions" :value="row.options" :key="index">{{ row.options }}</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.case_category">{{ errors.case_category[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.next_action }">
            <label for="">next action</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-arrow-circle-right"></i></span>
              </div>
              <select class="form-control" v-model="activity.next_action">
                  <option value="">Pilih</option>
                  <option v-for="(row, index) in next_actionOptions" :value="row.options" :key="index">{{ row.options }}</option>
              </select>
            </div>
            <p class="text-danger" v-if="errors.next_action">{{ errors.next_action[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.invalid_no }">
            <label for="">Invalid Numbers</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-funnel-dollar"></i></span>
              </div>
              <input type="number" class="form-control" min="0" v-model="activity.invalid_no">
            </div>
            <p class="text-danger" v-if="errors.invalid_no">{{ errors.invalid_no[0] }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md" v-if="tgljbstate">
          <div class="form-group" :class="{ 'has-error': errors.tgl_janji_byr }">
              <label for="">Tgl. Janji bayar</label>
              <div class="input-group">
                <b-form-datepicker id="example-datepicker" v-model="activity.tgl_janji_byr" class="mb-2">
                </b-form-datepicker>
              </div>
              <p class="text-danger" v-if="errors.tgl_janji_byr">{{ errors.tgl_janji_byr[0] }}</p>
          </div>
        </div>
        <div class="col-md-4">
          <div v-if="img" class="form-group" :class="{ 'has-error': errors.take_foto }">
            <label for="">foto</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-camera"></i></span>
              </div>
              <div class="custom-file">
                <input type="file" accept="image/*" capture class="custom-file-input" id="inputGroupFile" ref="inputGroupFile" v-on:change="handleFileUpload()">
                <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
              </div>
            </div>
            <p class="text-danger" v-if="errors.name">{{ errors.take_foto[0] }}</p>
          </div>
          <div class="position-relative" v-else >
            <img :src="baseUrl+'/storage/'+activity.take_foto" alt="Example" class="img-fluid">
            <div class="ribbon-wrapper ribbon-lg">
              <div class="ribbon bg-success text-lg">
                Activity
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group" :class="{ 'has-error': errors.keterangan }">
              <label for="">keterangan</label>
              <div class="input-group">
                <textarea class="form-control" v-model="activity.keterangan"></textarea>
              </div>
              <p class="text-danger" v-if="errors.name">{{ errors.keterangan[0] }}</p>
          </div>
        </div>
      </div>
      <!-- field data -->
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormActivity',
    data() {
        return {
            tgljbstate:false
        }
    },
    created() {
        this.getNextActions(),
        this.getCaseCategory(),
        this.getAssetDeb(),
        this.getKondPek()
    },
    computed: {
        ...mapState('user', {
            baseUrl: state => state.baseUrl
        }),
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('activity', {
            activity: state => state.activity,
            next_actionOptions: state => state.next_action,
            case_categoryOptions: state => state.case_category,
            asset_debOptions: state => state.asset_deb,
            kon_pekOptions: state => state.kondisi_pekerjaan,
            img: state => state.img,
        })
    },
    methods: {
      handleTglJB(event){
        if (event.target.value === 'Y') {
          this.tgljbstate = true
        }else{
          this.tgljbstate = false
        }
      },
      handleFileUpload(){
        this.$store.commit('activity/SET_TAKEFOTO', this.$refs.inputGroupFile.files[0])
      },
        ...mapActions('activity', [
            'getNextActions',
            'getCaseCategory',
            'getAssetDeb',
            'getKondPek',
        ]),
        ...mapMutations('activity', ['CLEAR_FORM']),
    },
    destroyed() {
        this.CLEAR_FORM()
    }
}
</script>
