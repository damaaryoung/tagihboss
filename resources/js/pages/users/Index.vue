<template>
  <div class="content-wrapper">
    <!-- Main content -->
    <breadcrumb></breadcrumb>
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>
<script>
    import Breadcrumb from '../../components/Breadcrumb.vue'
    export default {
        name: 'IndexOutlet',
        components: {
            'breadcrumb': Breadcrumb
        }
    }
</script>
