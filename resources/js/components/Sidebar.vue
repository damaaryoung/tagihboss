<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-warning elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img :src="baseUrl+'/storage/tagihbos-transparent.png'" alt="Logo" class="brand-image img-circle " style="opacity: .8" width="100%">
      <span class="brand-text font-weight-light">TagihBoss V2</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <b-avatar variant="warning"></b-avatar>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ authenticated.name }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item" v-if="$can('setting-list')">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('role-list')">
                <router-link :to="{name: 'role.permissions'}" class="nav-link"><i class="fas fa-dice-d20 nav-icon"></i> Role Permission</router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('user-list')">
                <router-link :to="{ name: 'users.data' }" class="nav-link"><i class="fas fa-users-cog nav-icon"></i> User Manage</router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'assigment.data' }" class="nav-link">
              <i class="nav-icon fas fa-archive"></i>
                Assigment
                <span class="right badge badge-danger">Mandatory</span>
              </router-link>
            </a>
          </li>
          <li class="nav-header">MASTER DATA</li>
          <li class="nav-item" v-if="$can('tasklist-list')">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-brain"></i>
              <p>
                Masters Tasklist
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('activity-list')">
                <router-link :to="{name: 'activity.data'}" class="nav-link"><i class="fas fa-briefcase nav-icon"></i> Masters Activity</router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('visit-list')">
                <router-link :to="{ name: 'visittt.data' }" class="nav-link">
                  <i class="fas fa-home nav-icon"></i>
                    M. Tmp. Tgl
                  <span class="right badge badge-info">Visit</span>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('visit-list')">
                <router-link :to="{ name: 'visitjm.data' }" class="nav-link">
                  <i class="far fa-gem nav-icon"></i>
                  M. Jaminan
                  <span class="right badge badge-info">Visit</span>
                </router-link>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item"  v-if="$can('user-list')">
                <router-link :to="{ name: 'payment.data' }" class="nav-link"><i class="fas fa-coins nav-icon"></i> Masters Payments</router-link>
              </li>
            </ul>
          </li>
          <li class="nav-header">MASTER INFO</li>
          <li class="nav-item" v-if="$can('infocoll-list')">
            <router-link :to="{ name: 'collection.data' }" class="nav-link">
              <i class="nav-icon fas fa-sticky-note"></i>
                Collections
              </router-link>
            </a>
          </li>
          <li class="nav-item" v-if="$can('documentation-list')">
            <router-link :to="{ name: 'documentation.data' }" class="nav-link">
              <i class="nav-icon fas fa-book-reader"></i>
                Documentation
              </router-link>
            </a>
          </li>
          <li class="nav-header">HELP & SUPPORT</li>
          <li class="nav-item" v-if="$can('infocoll-list')">
            <router-link :to="{ name: 'help' }" class="nav-link">
              <i class="nav-icon fas fa-question-circle"></i>
                Help
              </router-link>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script>
import { mapState,mapGetters,mapActions } from 'vuex'
export default {
    computed: {
        ...mapState('user', {
            authenticated: state => state.authenticated,
            baseUrl: state => state.baseUrl
        }),
        ...mapGetters(['isAuth'])
    },
    methods: {
        ...mapActions('user', ['getUserLogin'])
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin()
        }
    }
}
</script>
