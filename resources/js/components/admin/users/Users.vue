<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Χρήστες Συστήματος</h3>
         
              <b-form-checkbox
      id="checkbox-1"
      v-model="filter"
      name="checkbox-1"
      value="1"
      unchecked-value="0"
    ></b-form-checkbox>
        <div class="btn-group float-right">
          <b-button
            class="mr-2"
            variant="outline-primary"
            size="sm"
            role="button"
            v-b-modal.userModal
            @click="showModal()"
          >
            <i class="fas fa-plus"></i> Νέος Χρήστης
          </b-button>
          <b-button
            :disabled="deleteIds.length > 0 ? false : true"
            variant="outline-danger"
            size="sm"
            role="button"
            @click="deleteUsers()"
          >
            <i class="fas fa-trash"></i> Διαγραφή Χρήστη
          </b-button>
        </div>
      </div>
      <div class="card-body">
        <b-table
          bordered
          responsive
          hover
          show-empty
     
          :items="users"
          :fields="fields"
          ref="table"
        >
          <template #cell(id)="row">
            <b-form-checkbox v-model="deleteIds" :value="row.item.id">
            </b-form-checkbox>
          </template>
          <template #cell(is_admin)="row">
            <b-badge pill variant="success" v-if="row.value">Ναι </b-badge>
            <b-badge pill variant="danger" v-else>Όχι </b-badge>
          </template>
          <template #cell(role)="row">
            {{ row.value.name }}
          </template>
          <template #cell(active)="row">
            <b-badge pill variant="success" v-if="row.value == 1">Ναι </b-badge>
            <b-badge pill variant="danger" v-else>Όχι </b-badge>
          </template>
          <template #cell(actions)="row">
            <div class="btn-group">
              <b-button
                size="sm"
                variant="outline-primary"
                v-b-modal.userModal
                @click="showModal(row.item, row.index)"
                class="mr-1"
              >
                <i class="fas fa-edit"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </div>
    </div>

    <b-modal
      id="userModal"
      ref="userModal"
      size="lg"
      hide-footer
      :title="(modalData.id ? 'Επεξεργασία' : 'Προσθήκη') + ' Χρήστη'"
    >
      <b-form @submit.prevent="saveUser">
        <input type="hidden" name="id" :value="modalData.id" />
        <b-form-group id="input-name" label="Όνομα:" label-for="input-name">
          <b-form-input
            id="input-name"
            type="text"
            v-model="modalData.name"
            placeholder="Όνομα"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-roles" label="Ρόλος" label-for="input-roles">
          <b-form-select v-model="modalData.role_id">
            <b-form-select-option
              v-for="(role, i) in roles"
              :key="i"
              :value="role.id"
              >{{ role.name }}</b-form-select-option
            >
          </b-form-select>
        </b-form-group>
        <b-form-group id="input-email" label="Email:" label-for="input-email">
          <b-form-input
            id="input-email"
            type="email"
            v-model="modalData.email"
            placeholder="Email"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          id="input-active"
          label="Ενεργός"
          label-for="input-active"
        >
          <b-form-select
            v-model="modalData.active"
            :options="active_options"
          ></b-form-select>
        </b-form-group>
        <b-form-group
          id="input-password"
          label="Κωδικός:"
          label-for="input-password"
        >
          <b-form-input
            id="input-password"
            type="password"
            v-model="modalData.password"
            placeholder="Κωδικός"
          ></b-form-input>
        </b-form-group>
        <b-button-group class="float-right mt-3">
          <b-button type="submit" variant="success" size="sm" class="mr-1"
            >Αποθήκευση</b-button
          >
        </b-button-group>
      </b-form>
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      users: [],
      roles: [],
      deleteIds: [],
      modalData: [],
      index: "",
      filter: null,
      active_options: [
        { value: "0", text: "Όχι" },
        { value: "1", text: "Ναι" },
      ],
      fields: [
        { key: "id", label: "" },
        { key: "name", label: "Όνομα Χρήστη" },
        { key: "role", label: "Ρόλος" },
        { key: "email" },
        { key: "active", label: "Ενεργός", class: "text-center" },
        { key: "actions", label: "Ενέργειες", class: "text-right" },
      ],
    };
  },
  async created() {
    const [res, roles] = await Promise.all([
      this.callApi("get", "/getUsers"),
      this.callApi("get", "/getRoles"),
    ]);
    this.users = res.data;
    this.roles = roles.data;
  },
   watch: {
    async filter() {
       const res = await this.callApi("get", "/getUsers?filter=" + this.filter)
    this.users = res.data;
    console.log(res.data)
    this.$refs.table.refresh();
    }
  },
  methods: {
   
    showModal(data, index) {
      this.index = typeof index !== "undefined" ? index : -1;
      this.modalData = {
        id: data ? data.id : "",
        role_id: data ? data.role.id : 1,
        name: data ? data.name : "",
        email: data ? data.email : "",
        password: data ? data.password : "",
        is_admin: data ? data.is_admin : 1,
        active: data ? data.active : 0,
      };
    },
    async saveUser() {
      const res = await this.callApi("post", "/saveUser", this.modalData);
      if (res.status == 200 || res.status == 201) {
        if (this.index != -1) {
          this.users[this.index] = res.data;
        } else {
          this.users.unshift(res.data);
        }
        this.$refs["userModal"].hide();
        this.$refs.table.refresh();
        this.makeToaster(
          "Οι χρήστες επεξεργάστηκαν επιτυχώς!",
          "Επιτυχία",
          "success"
        );
      } else {
        alert();
      }
    },
    async deleteUsers() {
      const res = await this.callApi("post", "/deleteUser", this.deleteIds);
       if (res.status == 200 || res.status == 201) {
       for (let i in this.deleteIds) {
        for (let j = 0;  j < this.users.length; j++) {
          if(this.users[j].id == this.deleteIds[i]) {
            this.users.splice(j, 1);
            break;
          }
        }
      }

      this.deleteIds = [];
      this.$refs.table.refresh();
      
      this.makeToaster(
        "Οι χρήστες διαγράφηκαν επιτυχώς!",
        "Επιτυχία",
        "success"
      );
       } else {
         alert();
       }
    },
  },
};
</script>