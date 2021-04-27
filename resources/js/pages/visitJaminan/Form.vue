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
          <div class="form-group" :class="{ 'has-error': errors.kondisi_jaminan }">
              <label for="">kondisi</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-street-view"></i></span>
                </div>
                <select class="custom-select form-control" v-model="visit_a.kondisi_jaminan">
                  <option value="">Selected</option>
                  <option value="layak-huni">Layak Huni</option>
                  <option value="akses-mudah">Akses Mudah</option>
                  <option value="tidak-dekat-jalan-raya">Tidak dekat jalan raya</option>
                </select>
              </div>
              <p class="text-danger" v-if="errors.kondisi_jaminan">{{ errors.kondisi_jaminan[0] }}</p>
          </div>
          <div class="form-group" :class="{ 'has-error': errors.latitude_jaminan }">
              <label for="">lat</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_a.latitude_jaminan" readonly>
              </div>
              <p class="text-danger" v-if="errors.latitude_jaminan">{{ errors.latitude_jaminan[0] }}</p>
          </div>
          <div class="form-group" :class="{ 'has-error': errors.longitude_jaminan }">
              <label for="">long</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marked-alt"></i></span>
                </div>
                <input type="text" class="form-control" v-model="visit_a.longitude_jaminan" placeholder="enter name users..." readonly>
              </div>
              <p class="text-danger" v-if="errors.longitude_jaminan">{{ errors.longitude_jaminan[0] }}</p>
          </div>
        </div>
        <div class="col-md">
          <div class="position-relative">
            <img :src="baseUrl+'/storage/'+visit_a.imageJaminan1" alt="Example" class="img-fluid">
            <div class="ribbon-wrapper ribbon-lg">
              <div class="ribbon bg-success text-lg">
                VISIT
              </div>
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
        ...mapState('visitjm', {
            visit_a: state => state.visit_a
        })
    },
    methods: {
        ...mapMutations('visitjm', ['CLEAR_FORM_A']),
    },
    destroyed() {
        this.CLEAR_FORM_A()
    }
}
</script>
