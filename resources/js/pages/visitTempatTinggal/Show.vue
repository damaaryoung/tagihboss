<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Show Visit Data Tempat Tinggal</h3>
      </div>
        <div class="card-body">
          <div class="overlay-wrapper">
              <act-form></act-form>
          </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormAct from './Form.vue'
    export default {
        created() {
            this.editActTT(this.$route.params.id)
        },
        methods: {
            ...mapActions('visittt', ['editActTT'])
        },
        components: {
            'act-form': FormAct
        },
    }
</script>
