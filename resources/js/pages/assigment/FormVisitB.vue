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
                <input type="text" class="form-control" v-model="visit_b.task_code" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.task_code">{{ errors.task_code[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.kondisi_jaminan }">
              <label for="">kondisi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-street-view"></i></span>
                </div>
                <select class="custom-select form-control" v-model="visit_b.kondisi_jaminan">
                  <option value="">Selected</option>
                  <option value="layak-huni">Layak Huni</option>
                  <option value="akses-mudah">Akses Mudah</option>
                  <option value="tidak-dekat-jalan-raya">Tidak dekat jalan raya</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.kondisi_jaminan">{{ errors.kondisi_jaminan[0] }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.latitude_jaminan }">
              <label for="">lat</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_b.latitude_jaminan" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.latitude_jaminan">{{ errors.latitude_jaminan[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="form-group" :class="{ 'has-error': errors.longitude_jaminan }">
              <label for="">long</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_b.longitude_jaminan" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.longitude_jaminan">{{ errors.longitude_jaminan[0] }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md" v-if="showImage1">
          <div class="form-group" :class="{ 'has-error': errors.imageJaminan1 }">
              <label for="">Image</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-camera"></i></span>
                </div>
                <div class="custom-file">
                  <input type="file" accept="image/*" capture class="custom-file-input" id="inputGroupFile" ref="imageJaminan1" v-on:change="imageJaminan1()">
                  <label class="custom-file-label" for="inputGroupFile04"></label>
                </div>
              </div>
              <p class="text-danger" v-if="errors.imageJaminan1">{{ errors.imageJaminan1[0] }}</p>
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
            visit_b: state => state.visit_b,
        })
    },
    methods: {
      imageJaminan1(){
        this.$store.commit('assigment/SET_VISITFOTOJM_1', this.$refs.imageJaminan1.files[0])
        this.showImage1 = false
      },
        ...mapActions('assigment',['editAssigment']),
        ...mapMutations('assigment', ['CLEAR_FORM_VISIT_B'])
    },
    destroyed() {
        this.CLEAR_FORM_VISIT_B()
    }
}
</script>
