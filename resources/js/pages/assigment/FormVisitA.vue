<template>
    <div>
      <div class="row">
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.task_code }">
              <label for="">taskcode</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_a.task_code" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.task_code">{{ errors.task_code[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.kondisi_tempat_tinggal }">
              <label for="">kondisi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-street-view"></i></span>
                </div>
                <select class="custom-select form-control" v-model="visit_a.kondisi_tempat_tinggal">
                  <option value="">Selected</option>
                  <option value="layak-huni">Layak Huni</option>
                  <option value="akses-mudah">Akses Mudah</option>
                  <option value="tidak-dekat-jalan-raya">Tidak dekat jalan raya</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.kondisi_tempat_tinggal">{{ errors.kondisi_tempat_tinggal[0] }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.latitude_tempat_tinggal }">
              <label for="">lat</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_a.latitude_tempat_tinggal" readonly>
              </div>
              <p class="text-danger" v-if="errors.latitude_tempat_tinggal">{{ errors.latitude_tempat_tinggal[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.longitude_tempat_tinggal }">
              <label for="">long</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_a.longitude_tempat_tinggal" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.longitude_tempat_tinggal">{{ errors.longitude_tempat_tinggal[0] }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md" v-if="showImage1">
          <div class="form-group" :class="{ 'has-error': errors.imageTempatTinggal1 }">
              <label for="">Image</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-camera"></i></span>
                </div>
                <div class="custom-file">
                  <input type="file" accept="image/*" capture class="custom-file-input" ref="imageTempatTinggal1" v-on:change="imageTempatTinggal1()">
                  <label class="custom-file-label" for="inputGroupFile04"></label>
                </div>
              </div>
              <p class="text-danger" v-if="errors.imageTempatTinggal1">{{ errors.imageTempatTinggal1[0] }}</p>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormVisit',
    created() {
        this.editAssigment(this.$route.params.id)
    },
    data() {
        return {
          showImage1: true
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('assigment', {
            visit_a: state => state.visit_a
        })
    },
    methods: {
      imageTempatTinggal1(){
        this.$store.commit('assigment/SET_VISITFOTO_1', this.$refs.imageTempatTinggal1.files[0])
        this.showImage1 = false
      },
        ...mapActions('assigment',['editAssigment']),
        ...mapMutations('assigment', ['CLEAR_FORM_VISIT_A'])
    },
    destroyed() {
        this.CLEAR_FORM_VISIT_A()
    }
}
</script>
