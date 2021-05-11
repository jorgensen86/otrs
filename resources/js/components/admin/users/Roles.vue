<template>
  <b-container fluid>
    <b-row>
      <b-col md="5">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Προσθήκη ρόλου</h3>
          </div>
          <b-card-body>
            <b-form @submit.prevent="saveRole" @reset="resetForm">
              <input type="hidden" v-model="data.id">
              <b-form-group id="input-name" label-for="input-name">
                <b-form-input
                  id="input-name"
                  type="text"
                  placeholder="Ρόλος"
                  required
                  v-model="data.name"
                ></b-form-input>
              </b-form-group>
              <b-form-group id="input-slug" label-for="input-slug">
                <b-form-input
                  id="input-slug"
                  type="text"
                  placeholder="Slug"
                  required
                  v-model="data.slug"
                ></b-form-input>
              </b-form-group>
              <b-button-group class="float-right">
              <b-button type="submit" variant="outline-success" size="sm" class="mr-1">Αποθήκευση</b-button>
              <b-button type="reset" variant="outline-danger" size="sm">Καθαρισμός</b-button>
              </b-button-group>
            </b-form>
          </b-card-body>
        </div>
      </b-col>
      <b-col md="7">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title pull-right">Ρόλοι χρηστών</h3>
          </div>
          <div class="card-body">
            <b-table
              hover
              ref="table"
              class="table"
              :items="roles"
              :fields="fields"
              show-empty
              responsive
              bordered
            >
              <template #cell(actions)="row">
                <b-button
                  size="sm"
                  variant="primary"
                  @click="editRole(row.item, row.index)"
                  class="mr-1"
                >
                  <i class="fas fa-edit"></i>
                </b-button>
                <b-button
                  size="sm"
                  variant="danger"
                  @click="showModal(row.item, row.index)"
                >
                  <i class="fas fa-trash"></i>
                </b-button>
              </template>
            </b-table>
          </div>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
export default {
  data() {
    return {
      data: {
        id: "",
        name: '',
        slug: ''
      },
      index: -1,
      roles: [],
      fields: [
        { key: "id", label: "Id" },
        { key: "name", label: "Όνομα" },
        { key: "slug", label: "Slug" },
        { key: "actions", label: "Ενέργειες" },
      ],
    };
  },
  async created() {
    const res = await this.callApi("get", "/getRoles", this.data);
    this.roles = res.data;
  },
  methods: {
    resetForm() {
      this.data.id = '';
      this.data.name = '';
      this.data.slug = '';
    },
    editRole(data, index) {
      this.data.id = data.id;
      this.data.name = data.name;
      this.data.slug = data.slug;
      this.index = data ? index : -1;
    },
    async saveRole() {
      if (this.data.name.trim() == '' || this.data.slug.trim() == '') {
        return this.makeToaster('Τα πεδία είναι υποχρεωτικά', 'Προσοχή', 'danger');
      }
      
      const res = await this.callApi("post", "/saveRole", this.data);
      if (res.status == 201 || res.status == 200) {
        if (this.index != -1) {
          this.roles[this.index] = res.data;
        } else {
          this.roles.unshift(res.data);
        }
        this.resetForm();
        this.$refs.table.refresh();
        this.makeToaster('Οι ρόλοι επεξεργάστηκαν επιτυχώς!', 'Επιτυχία', 'success');
      } else if(res.status == 200) {
        for (let key in res.data.errors) {
          this.makeToaster(res.data.errors[key][0], 'Προσοχή', 'danger');
          
        }
        
      }
    },
  },
};
</script>