<template>
  <div>
    <div class="sign-up-form-outer">
      <div class="sign-up-form-inner">
        <div class="row">
          <div class="col-md-12">
            <div class="new-user-signup">
              <div v-if="addManagers">
                <create-manager
                  v-on:updatemanager="updateManager"
                  v-bind:new-manager="newManager"
                />
              </div>
              <form action novalidate v-if="!addManagers">
                <h1>New Farm</h1>
                <vue-form-generator
                  tag="div"
                  :schema="schema"
                  :options="formOptions"
                  :model="model"
                />
              </form>
              <div class="form-group" v-if="hasManager && !addManagers">
                <ul class="list-group">
                  <li
                    class="list-group-item"
                    v-for="(manager, index) in model.manager_details"
                    :key="index"
                  >
                    {{ manager.manager_first_name }} / {{ manager.email }}
                  </li>
                </ul>
                <button
                  class="btn btn-success btn-lg btn-block"
                  style="display: inline; margin-top: 20px"
                  @click="addNewManager"
                >
                  Add New Manager
                </button>
                <button
                  class="btn btn-success btn-lg btn-block"
                  style="display: inline; margin-top: 20px"
                  @click="createFarm"
                >
                  Create Farm
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import farmFormSchema from "../../../forms/farmFormSchema";
import FarmService from "../../../services/FarmService";
import CreateManager from "./Managers/CreateManager";
import router from "../../../router";

const emptyManager = {
  manager_first_name: "",
  manager_last_name: "",
  email: ``,
  manager_phone: "",
  manager_address: "",
  manager_city: "",
  manager_province: "",
  manager_zipcode: "",
  manager_card_image: "",
  manager_id_card: "",
  salary: ""
};

const emptyFarmRequest = {
  farm_address: "",
  farm_city: "",
  farm_province: "",
  farm_zipcode: "",
  farm_active: 1,
  latitude: "",
  longitude: "",
  manager_details: []
};

export default {
  components: {
    CreateManager
  },
  computed: {
    hasManager: function() {
      return this.model.manager_details.length > 0;
    }
  },
  data() {
    return {
      newManager: { ...emptyManager },
      model: emptyFarmRequest,
      schema: {
        fields: [
          ...farmFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Create Farm",
            caption: "Create Farm form",
            validateBeforeSubmit: true,
            disabled: () => this.isCreatingFarm,
            onSubmit: (model, schema) => {
              this.isCreatingFarm = true;
              this.addManagers = true;
              this.newManager = { ...emptyManager };
            }
          }
        ]
      },
      formOptions: {
        validateAfterChanged: true
      },
      isCreatingFarm: false,
      addManagers: false
    };
  },
  methods: {
    updateManager: function(manager) {
      this.model.manager_details.push(manager);
      this.addManagers = false;
    },
    createFarm: function() {
      FarmService.create(this.model)
        .then(
          response => {
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false
            });
            this.model = emptyFarmRequest;
            this.addManagers = false;
            router.push({ name: "farmsList" });
          },
          error => {
            this.$toast.open({
              message: error.response.data.message,
              type: "error",
              position: "bottom-right",
              dismissible: false
            });
          }
        )
        .finally(_ => {
          this.isCreatingFarm = false;
        });
    },
    addNewManager: function() {
      this.newManager = { ...emptyManager };
      this.addManagers = true;
    }
  }
};
</script>
