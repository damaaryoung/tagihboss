<template>
  <div>
    <div class="row">
      <div class="col-sm-10">
        <div class="form-group" :class="{ 'has-error': errors.title }">
          <label for="">Title</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
            </div>
            <input type="text" class="form-control" v-model="collection.title">
          </div>
          <p class="text-danger" v-if="errors.title">{{ errors.title[0] }}</p>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group" :class="{ 'has-error': errors.state }">
          <b-form-group label="State" v-slot="{ ariaDescribedby }">
            <b-form-radio v-model="collection.state" :aria-describedby="ariaDescribedby" name="some-radios" value="active">Active</b-form-radio>
            <b-form-radio v-model="collection.state" :aria-describedby="ariaDescribedby" name="some-radios" value="nonactive">Non Active</b-form-radio>
          </b-form-group>
          <p class="text-danger" v-if="errors.state">{{ errors.state[0] }}</p>
        </div>
      </div>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.information }">
      <label for="">information</label>
      <vue-editor v-model="collection.information" />
      <p class="text-danger" v-if="errors.information">{{ errors.information[0] }}</p>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormColl',
    computed: {
        ...mapState(['errors']),
        ...mapState('collection', {
            collection: state => state.collection
        })
    },
    methods: {
        ...mapMutations('collection', ['CLEAR_FORM']),
    },
    destroyed() {
        this.CLEAR_FORM()
    }
}
</script>
