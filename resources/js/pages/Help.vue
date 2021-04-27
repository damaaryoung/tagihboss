<template>
  <div class="content-wrapper">
    <!-- Main content -->
    <breadcrumb></breadcrumb>
    <div class="content">
      <div class="container-fluid">
        <div class="card card-warning card-outline">
          <div class="card-header">
            <div class="card-tools">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-key"></i>
                  </span>
                </div>
                <select class="form-control" v-model="selected" v-on:change="_pick(selected)">
                    <option value="">Pilih</option>
                    <option v-for="(row, index) in titleOptions" :value="row.id" :key="index">{{ row.title }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="overlay-wrapper">
              <div class="overlay" v-if="loading">
                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                <div class="text-bold pt-2">
                  Loading...
                </div>
              </div>
              <h5 v-html="pick.title"></h5>
              <p v-html="pick.authorization"></p>
              <div v-html="pick.information"></div>
              <div v-html="pick.attention"></div>
            </div>
            <div class="pd-20 card-box mb-30">
          		<h4 class="h4 text-blue mb-10">Introduction</h4>
          		<p>TagihBoss adalah WebApp untuk dasbor panel kontrol. TagihBoss juga adalah Webapp yang sepenuhnya responsif, yang didasarkan pada kerangka kerja CSS Bootstrap 4, ini menggunakan semua komponen Bootstrap dalam desainnya dan menata ulang banyak plugin yang umum digunakan untuk membuat desain yang konsisten yang dapat digunakan sebagai antarmuka pengguna aplikasi. TagihBoss didasarkan pada desain modular perpaduan Laravel mix VueJs, yang memungkinkannya untuk disesuaikan dan dibangun dengan mudah. Dokumentasi ini akan memandu Anda dalam sistem dan menjelajahi berbagai fitur yang disertakan dengan sistem.</p>
          		<p>Kami menaruh banyak cinta dan upaya untuk menjadikan TagihBoss sebagai Webapp yang berguna untuk semua orang khususnya team collections, IT, dan team yang bersangkutan. Kami ingin merilis pembaruan jangka panjang yang berkelanjutan dan banyak fitur baru akan segera hadir di rilis mendatang</p>
          	</div>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><i class="icon fas fa-exclamation-triangle"></i> Noted!</h5>
              <ul>
          			<li class="d-flex pb-20"><i class="fas fa-file font-20 mr-2"></i> Kami telah mengintegrasikan sistem TagihBoss dengan core Micro BPR Online dan juga SEFIN, namun tetap menggunakan kerangka kerja data user dengan terpisah.</li>
          			<li class="d-flex pb-20"><i class="fas fa-file font-20 mr-2"></i> Sistem dilengkapi dengan log activity untuk merekam seluruh aktivitas user didalam sistem.</li>
          			<li class="d-flex pb-20"><i class="fas fa-file font-20 mr-2"></i> Jika menemukan kesulitan dalam penggunaan sistem, anda bisa menanyakan pada pihak yang ikut serta dalam perancangan sistem.</li>
          			<li class="d-flex pb-20"><i class="fas fa-file font-20 mr-2"></i> Sesuai dengan semboyan MRX pada film "Who I'm I" yakni "Tidak ada sistem yang aman, kami menyadari akan banyaknya kekurangan didalam sistem, untuk itu kami sangat mengapresiasi setiap user yang turut serta dalam pengembangan sistem dengan melaporkan berbagai kekurangan yang ada melalui Tools yang akan kami siapkan (coming soon).</li>
          		</ul>
            </div>
            <div class="alert alert-primary alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><i class="icon fas fa-info"></i> Development!</h5>
              <ul>
                <li>
                  Tagihboss dirancang dengan menggunakan framework php (Laravel) sebagai backend, untuk informasi penggunaanya bisa di cek pada halaman <a href="https://laravel.com/">dokumentasi ini</a>
                </li>
                <li>
                  Tagihboss juga dirancang dengan menggunakan framework javascript (Vue.js) sebagai frontend, untuk informasi penggunaanya bisa di cek pada halaman <a href="https://vuejs.org/">dokumentasi ini</a>
                </li>
                <li>
                  Tagihboss juga dirancang dengan menggunakan framework javascript (Vuex.js) sebagai state management, untuk informasi penggunaanya bisa di cek pada halaman <a href="https://vuex.vuejs.org/">dokumentasi ini</a>
                </li>
                <li>
                  Tagihboss juga menggunakan web shocket system dengan perangkat Laravel echo server yang dipadukan dengan Redis dan Shocket.Io, untuk informasi penggunaanya bisa di cek pada halaman
                  <a href="https://laravel.com/docs/8.x/broadcasting">dokumentasi Laravel Echo</a>
                  <a href="https://socket.io/">dokumentasi Socket.Io</a>
                  <a href="https://redis.io/">dokumentasi Redis</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Database Schema
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/tagihbossDBMapping.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Use Case Schema
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/Usecase.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Login Activity
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/ActivityLoginUser.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              FC/RC/ARC Activity
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/ActivitydiagramFc,rc,arc.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Admin/Superadmin Activity
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/Activity-Superadmin.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              FC/RC/ARC Squance
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/FC-RC-ARC-squance.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Users Squance
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/Users-squance.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
        <!-- card diagram -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Admin/Superadmin/Spv Squance
            </h3>
          </div>
          <div class="card-body">
            <div class="position-relative">
              <img :src="baseUrl+'/storage/Administrators-squance.png'" alt="Example" class="img-fluid" width="100%">
            </div>
          </div>
        </div>
        <!-- card diagram -->
      </div>
    </div>
  </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import Breadcrumb from '../components/Breadcrumb.vue'
    export default {
        name: 'help',
        data() {
            return {
              selected: ""
            }
        },
        created() {
            this.getData()
        },
        computed: {
            ...mapState('user', {
                baseUrl: state => state.baseUrl
            }),
            ...mapState('help', {
                titleOptions: state => state.doc,
                pick: state => state.pick,
                loading: state => state.loading
            }),
        },
        methods: {
            ...mapActions('help', ['getData','getPic']),
            _pick(id){
              this.getPic(id)
            }
        },
        components: {
            'breadcrumb': Breadcrumb
        }
    }
</script>
