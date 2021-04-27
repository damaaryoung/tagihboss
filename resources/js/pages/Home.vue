<template>
  <div class="content-wrapper">
    <!-- Main content -->
    <breadcrumb></breadcrumb>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{assigment}}</h3>

                <p>Assigments</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <router-link :to="{name: 'assigment.data'}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{activity}}</h3>

                <p>Activity</p>
              </div>
              <div class="icon">
                <i class="ion ion-briefcase"></i>
              </div>
              <router-link :to="{name: 'activity.data'}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{visit}}</h3>

                <p>Visits</p>
              </div>
              <div class="icon">
                <i class="ion ion-map"></i>
              </div>
              <router-link :to="{name: 'visittt.data'}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{payment}}</h3>

                <p>Payments</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-analytics"></i>
              </div>
              <router-link :to="{name: 'payment.data'}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </router-link>
            </div>
          </div>
        </div>
        <section>
          <div class="row">
            <div class="col-md-4">
              <div class="card card-widget widget-user shadow">
                <div class="widget-user-header bg-dark">
                  <h3 class="widget-user-username">{{ authenticated.name }}</h3>
                  <h5 class="widget-user-desc">{{ authenticated.nik }}</h5>
                </div>
                <div class="widget-user-image">
                  <b-avatar variant="warning" size="6rem"></b-avatar>
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">{{activity_}}</h5>
                        <span class="description-text">Activity</span>
                      </div>
                    </div>
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">{{visit_}}</h5>
                        <span class="description-text">Visit</span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header">{{payment_}}</h5>
                        <span class="description-text">Payment</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header border-transparent">
                  <div class="card-title">
                    <select class="form-control" v-on:change="handleIColl($event)">
                        <option value="">Pilih Informasi</option>
                        <option v-for="(row, index) in infocoll" :value="row.id" :key="index">{{ row.title }}</option>
                    </select>
                  </div>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="overlay-wrapper">
                    <div class="overlay" v-if="loadingInfo">
                      <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                      <div class="text-bold pt-2">Loading...</div>
                    </div>
                    <div class="callout callout-info">
                      {{(info)?info.information:'Untuk memuat informasi anda harus memilih judul informasi pada kolom pilihan informasi.'}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>
<script>
    import Breadcrumb from '../components/Breadcrumb.vue'
    import { mapActions, mapState } from 'vuex'
    export default {
        name: 'Home',
        created() {
            this.infocollTitle(),
            this.countAssigment(),
            this.countActivity(),
            this.countVisit(),
            this.countPayment()
        },
        computed: {
            ...mapState('user', {
                authenticated: state => state.authenticated
            }),
            ...mapState('home', {
                loadingInfo: state => state.loadingInfo,
                info: state => state.info,
                infocoll: state => state.infocoll,
                assigment: state => state.assigment,
                activity: state => state.activity,
                activity_: state => state.activity_,
                visit: state => state.visit,
                visit_: state => state.visit_,
                payment: state => state.payment,
                payment_: state => state.payment_,
                unauthorization: state => state.unauthorization,
            }),
        },
        methods: {
            ...mapActions('home', [
              'getInfo',
              'infocollTitle',
              'countAssigment',
              'countActivity',
              'countVisit',
              'countPayment'
            ]),
            handleIColl(event){
              this.getInfo(event.target.value)
            }
        },
        components: {
            'breadcrumb': Breadcrumb
        }
    }
</script>
