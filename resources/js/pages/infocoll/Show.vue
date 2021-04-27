<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Show Payment Data</h3>
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
            this.editCollection(this.$route.params.id)
        },
        methods: {
            ...mapActions('collection', ['editCollection'])
        },
        components: {
            'act-form': FormAct
        },
    }
</script>
