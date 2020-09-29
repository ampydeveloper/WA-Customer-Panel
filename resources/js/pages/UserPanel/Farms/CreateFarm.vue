<template>
  <div class="main-wrapper">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>Create Farm</h2>
          </div>
        </div>
      </div>
    </section>

    <section class="create-farm-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="basic-grey-box">
              <div v-if="addManagers">
                <create-manager
                  v-on:updatemanager="updateManager"
                  v-bind:new-manager="newManager"
                />
              </div>

              <form action novalidate v-if="!addManagers">
                <vue-form-generator
                  tag="div"
                  :schema="schema"
                  :options="formOptions"
                  :model="model"
                />
              </form>

              <button
                class="btn btn-success btn-lg btn-block"
                style="width : 200px;"
                @click="addNewManager"
                v-if="!addManagers"
              >
                Add New Manager
              </button>

              <div class="form-group" v-if="hasManager && !addManagers">
                <ul class="list-group">
                  <li
                    class="list-group-item"
                    v-for="(manager, index) in model.manager_details"
                    :key="index"
                  >
                    {{ manager.manager_first_name }}
                    {{ manager.manager_last_name }} / {{ manager.email }}

                    <div style="display: inline; float: right;">
                      <button
                        type="button"
                        class="btn btn-sm btn-info"
                        @click="onManagerEdit(manager)"
                      >
                        Edit
                      </button>
                      <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        @click="onManagerDelete(manager)"
                      >
                        Delete
                      </button>
                    </div>
                  </li>
                </ul>

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
    </section>
  </div>
</template>

<script>
import farmFormSchema from "../../../forms/farmFormSchema";
import FarmService from "../../../services/FarmService";
import CreateManager from "./Managers/CreateManager";
import router from "../../../router";
import _ from "lodash";

const emptyManager = {
  manager_first_name: "",
  manager_last_name: "",
  email: "",
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
      fileContainer: [],
      schema: {
        fields: [
          ...farmFormSchema.fields,
          {
            type: "filepond",
            onFilePondDrop: (fieldName, file, metadata, load) => {
              this.fileContainer.push(file);
              load(Date.now());
            }
          },
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
      const existingManager = _.find(
        this.model.manager_details,
        emanager => emanager.email === manager.email
      );

      if (existingManager !== undefined) {
        this.model.manager_details = _.filter(
          this.model.manager_details,
          emanager => emanager.email !== manager.email
        );
      }

      this.model.manager_details.push(manager);
      this.addManagers = false;
    },
    createFarm: function() {
      var createFarmRequest = new FormData();

      /**
       * Adding form values to Request
       * except of user_image
       */
      for (var key in this.model) {
        if (key !== "manager_details") {
          createFarmRequest.append(key, this.model[key]);
        } else {
          if (this.model.manager_details.length > 0) {
            this.model.manager_details.forEach((manager, ind) => {
              Object.keys(manager).forEach(k => {
                createFarmRequest.append(
                  `manager_details[${ind}][${k}]`,
                  manager[k]
                );
              });
            });
          }
        }
      }

      /**
       * If user image is uploaded then add it to form data
       */
      if (this.fileContainer.length > 0) {
        this.fileContainer.forEach((file, ind) => {
          createFarmRequest.append(`farm_image[${ind}]`, file, file.name);
        });
      }

      FarmService.create(createFarmRequest)
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
    },
    onManagerDelete: function(manager) {
      this.model.manager_details = _.filter(
        this.model.manager_details,
        function(man) {
          return man.email !== manager.email;
        }
      );
    },
    onManagerEdit: function(manager) {
      this.newManager = { ...manager };
      this.addManagers = true;
    }
  }
};
</script>
