<template>
  <div>
    <!-- TEMPLATE FORM ACTIVITY::STARTED -->
    <div class="row" v-if="act.stat_act">
      <div class="col-md">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Actions Activity Assigment <span class="right badge badge-danger">Mandatory !</span></h3>
          </div>
            <div class="card-body">
              <div class="overlay-wrapper">
                <div class="overlay" v-if="loadingA">
                  <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                  <div class="text-bold pt-2">
                    Loading...
                  </div>
                </div>
                  <a-form></a-form>
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submitA">
                  <i class="fa fa-save"></i> Send
              </button>
            </div>
        </div>
      </div>
    </div>
    <b-alert show variant="success" v-else>
      <strong>Activity after success created!</strong>
      Success activity created and you can try to create form of other unmandatory.
    </b-alert>
    <!-- TEMPLATE FORM ACTIVITY::STARTED -->
    <!-- TEMPLATE FORM VISITS::STARTED -->
    <div v-if="act.stat_vst">
      <div class="row">
        <div class="col-sm">
          <div class="callout callout-info">
            <h5>Visit Assigment</h5>
            <p>Chosed and enter form.</p>
            <b-form-radio-group
              v-model="selected"
              :options="options"
              class="mb-3"
              value-field="item"
              text-field="name"
              disabled-field="notEnabled"
            ></b-form-radio-group>
            <div class="mt-3">Selected: <strong>{{ selected }}</strong></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm" v-if="selected==='A'">
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Actions Visits Tempat Tinggal Assigment <span class="right badge badge-danger">Mandatory !</span></h3>
            </div>
              <div class="card-body">
                <div class="overlay-wrapper">
                  <div class="overlay" v-if="loadingBa">
                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    <div class="text-bold pt-2">
                      Loading...
                    </div>
                  </div>
                    <b-form-a></b-form-a>
                </div>
              </div>
              <div class="card-footer">
                <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submitBa">
                    <i class="fa fa-save"></i> Send
                </button>
              </div>
          </div>
        </div>
        <div class="col-sm" v-if="selected==='B'">
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Actions Visits Jaminan Assigment <span class="right badge badge-danger">Mandatory !</span></h3>
            </div>
              <div class="card-body">
                <div class="overlay-wrapper">
                  <div class="overlay" v-if="loadingBb">
                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    <div class="text-bold pt-2">
                      Loading...
                    </div>
                  </div>
                    <b-form-b></b-form-b>
                </div>
              </div>
              <div class="card-footer">
                <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submitBb">
                    <i class="fa fa-save"></i> Send
                </button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <b-alert show variant="success" v-else>
      <strong>Visits after success created!</strong>
      Success visits created and you can try to create form of other unmandatory.
    </b-alert>
    <!-- TEMPLATE FORM VISITS::STARTED -->
    <!-- TEMPLATE FORM PAYMENTS::STARTED -->
    <div class="row" v-if="act.stat_pym">
      <div class="col-md">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Actions Payment Assigment <span class="right badge badge-info">Unmandatory !</span></h3>
          </div>
            <div class="card-body">
              <div class="overlay-wrapper">
                <div class="overlay" v-if="loadingC">
                  <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                  <div class="text-bold pt-2">
                    Loading...
                  </div>
                </div>
                  <c-form></c-form>
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submitC">
                  <i class="fa fa-save"></i> Send
              </button>
            </div>
        </div>
      </div>
    </div>
    <b-alert show variant="success" v-else>
      <strong>Payment after success created!</strong>
      Success Payment created and you can try to create form of other unmandatory.
    </b-alert>
    <!-- TEMPLATE FORM PAYMENT::STARTED -->
  </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormA from './FormActivity.vue'
    import FormBa from './FormVisitA.vue'
    import FormBb from './FormVisitB.vue'
    import FormC from './FormPayment.vue'
    export default {
        name: 'EditUsers',
        data() {
            return {
              loadingA: false,
              loadingBa: false,
              loadingBb: false,
              loadingC: false,
              selected: 'A',
                options: [
                  { item: 'A', name: 'Tempat Tinggal' },
                  { item: 'B', name: 'Jaminan' }
                ]
            }
        },
        computed: {
            ...mapState('assigment', {
                act: state => state.state_act
            })
        },
        created() {
            this.editAssigment(this.$route.params.id)
        },
        methods: {
            ...mapActions('assigment', ['editAssigment', 'submitActivity', 'submitVisitA','submitVisitB','submitPayment']),
            submitA() {
                this.loadingA = true
                this.submitActivity().then((response) => {
                  if (response['status']==='success') {
                    this.loadingA = false
                    this.$bvToast.toast(response['message'], {
                      title: `created ${response['status']}`,
                      variant: response['status'],
                      solid: true
                    })
                  }else{
                    this.loadingA = false
                  }
                })
                setTimeout(() => {
                    this.loadingA = false
                }, 1000)
            },
            submitBa() {
                this.loadingBa = true
                this.submitVisitA().then((response) => {
                  this.loadingBa = false
                  this.$bvToast.toast(response['message'], {
                    title: `created ${response['status']}`,
                    variant: response['status'],
                    solid: true
                  })
                })
                setTimeout(() => {
                    this.loadingBa = false
                }, 1000)
            },
            submitBb() {
                this.loadingBb = true
                this.submitVisitB().then((response) => {
                  this.loadingBb = false
                  this.$bvToast.toast(response['message'], {
                    title: `created ${response['status']}`,
                    variant: response['status'],
                    solid: true
                  })
                })
                setTimeout(() => {
                    this.loadingBb = false
                }, 1000)
            },
            submitC() {
                this.loadingC = true
                this.submitPayment().then((response) => {
                  this.loadingC = false
                  this.$bvToast.toast(response['message'], {
                    title: `created ${response['status']}`,
                    variant: response['status'],
                    solid: true
                  })
                })
                setTimeout(() => {
                    this.loadingC = false
                }, 1000)
            },
        },
        components: {
            'a-form': FormA,
            'b-form-a': FormBa,
            'b-form-b': FormBb,
            'c-form': FormC
        },
    }
</script>
