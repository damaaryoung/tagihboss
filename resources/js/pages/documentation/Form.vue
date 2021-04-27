<template>
  <div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group" :class="{ 'has-error': errors.title }">
          <label for="">Title</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
            </div>
            <input type="text" class="form-control" v-model="documentation.title">
          </div>
          <p class="text-danger" v-if="errors.title">{{ errors.title[0] }}</p>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group" :class="{ 'has-error': errors.state }">
          <b-form-group label="State" v-slot="{ ariaDescribedby }">
            <b-form-radio v-model="documentation.state" :aria-describedby="ariaDescribedby" name="some-radios" value="active">Active</b-form-radio>
            <b-form-radio v-model="documentation.state" :aria-describedby="ariaDescribedby" name="some-radios" value="nonactive">Non Active</b-form-radio>
          </b-form-group>
          <p class="text-danger" v-if="errors.state">{{ errors.state[0] }}</p>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group" :class="{ 'has-error': errors.authorization }">
          <label for="">Authorizations</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-briefcase"></i></span>
            </div>
            <select class="form-control" v-model="documentation.authorization">
                <option value="">Pilih</option>
                <option v-for="(row, index) in autho" :value="row.name" :key="index">{{ row.name }}</option>
            </select>
          </div>
          <p class="text-danger" v-if="errors.authorization">{{ errors.authorization[0] }}</p>
        </div>
      </div>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.information }">
      <label for="">Information</label>
      <vue-editor v-model="documentation.information" />
      <p class="text-danger" v-if="errors.information">{{ errors.information[0] }}</p>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.attention }">
      <label for="">Attention</label>
      <vue-editor v-model="documentation.attention" />
      <p class="text-danger" v-if="errors.attention">{{ errors.attention[0] }}</p>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState, mapMutations } from 'vuex'
export default {
    name: 'FormColl',
    created() {
        this.getPermission()
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('documentation', {
            documentation: state => state.documentation,
            autho: state => state.permissions,
        })
    },
    methods: {
        ...mapActions('documentation', ['getPermission']),
        ...mapMutations('documentation', ['CLEAR_FORM']),
    },
    destroyed() {
        this.CLEAR_FORM()
    }
}
</script>
