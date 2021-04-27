<template>
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Show Activity Data</h3>
      </div>
        <div class="card-body">
          <act-form></act-form>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    import FormAct from './Form.vue'
    export default {
        name: 'EditActivity',
        created() {
            this.editAct(this.$route.params.id)
        },
        methods: {
            ...mapActions('activity', ['editAct'])
        },
        components: {
            'act-form': FormAct
        },
    }
</script>
