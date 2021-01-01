<template>
  <div class="main-wrapper">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h2>
              Create A
              <br />
              <span class="bg-custom-thickness"> Farm </span>
            </h2>
          </div>
          <div class="col-md-8">
            <div class="desc-details pickup-desc-details">
              <h2>
                Add <span class="bg-custom-thickness">farm details</span> &
                <span class="bg-custom-thickness">manager details</span><br />
                & create a farm.
              </h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="create-farm-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="basic-grey-box">
              <h5 class="heading2">Add Farm Details</h5>
              <form action novalidate>
                <vue-form-generator
                  ref="form"
                  tag="section"
                  class="custom-forms"
                  :schema="schema"
                  :options="formOptions"
                  :model="model"
                />
              </form>
            </div>
            <!-- <button
                class="btn btn-success btn-lg btn-block"
                style="width : 200px;"
                @click="addNewManager"
                v-if="!addManagers"
              >
                Add New Manager
              </button> -->

            <div class="basic-grey-box">
              <create-manager
                :key="managerKey"
                v-on:updatemanager="updateManager"
                v-on:cancelEditManager="cancelEditManager"
                v-bind:new-manager="newManager"
                v-bind:is-edit="isEdit"
              />
            </div>

            <div class="form-group" v-if="hasManager">
              <ul class="list-group">
                <li
                  class="list-group-item"
                  v-for="(manager, index) in model.manager_details"
                  :key="index"
                >
                  {{ manager.manager_first_name }}
                  {{ manager.manager_last_name }} / {{ manager.email }}

                  <div style="display: inline; float: right">
                    <button
                      type="button"
                      class="btn btn-sm btn-info"
                      @click="onManagerEdit(manager, index)"
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

              <div class="basic-button-out clearfix mt-3">
                <v-btn
                  type="button"
                  :loading="loading"
                  :disabled="loading"
                  class="btn-full-green"
                  @click="createFarm"
                  >Create Farm <i data-feather="arrow-right"></i
                ></v-btn>
              </div>
            </div>
          </div>
        </div>
      </div>

      <sub-footer />
    </section>
  </div>
</template>

<script>
import farmFormSchema from "../../../forms/farmFormSchema";
import FarmService from "../../../services/FarmService";
import CreateManager from "./Managers/CreateManager";
import router from "../../../router";
import _ from "lodash";
import VFormBase from "vuetify-form-base";
import subFooter from "../subFooter";

const emptyManager = {
  manager_first_name: "",
  manager_last_name: "",
  email: "",
  manager_phone: "",
  manager_address: "",
  manager_city: "",
  manager_province: "",
  manager_zipcode: "",
  manager_card_image: [],
  manager_id_card: "",
  salary: "",
};

const emptyFarmRequest = {
  farm_address: "",
  farm_city: "",
  farm_province: "",
  farm_zipcode: "",
  farm_place: "",
  farm_active: 1,
  latitude: "54",
  longitude: "54",
  manager_details: [],
};

export default {
  components: {
    CreateManager,
    VFormBase,
    subFooter,
  },
  computed: {
    hasManager: function () {
      return this.model.manager_details.length > 0;
    },
  },
  data() {
    return {
      managerKey: Math.random().toString(36).substring(7),
      newManager: { ...emptyManager },
      model: emptyFarmRequest,
      fileContainer: [],
      isEdit: false,
      loading: false,
      schema: {
        styleClasses: "row",
        fields: [
          {
            label: 'Address',
            type: "vueGoogleAutocomplete",
            required: true,
            model: 'farm_address',
            validator: ["required", "string"],
            styleClasses:'col-md-4',
            getAddressData: (addressData, placeResultData, id) => {
              if(addressData.route) this.model.farm_address = addressData.route
              if(addressData.locality) this.model.farm_city = addressData.locality
              if(addressData.administrative_area_level_1) this.model.farm_province = addressData.administrative_area_level_1
              if(addressData.postal_code) this.model.farm_zipcode = addressData.postal_code
              if(addressData.longitude) this.model.longitude = addressData.longitude
              if(addressData.latitude) this.model.latitude = addressData.latitude
            }
          },
          ...farmFormSchema.fields,
          {
            type: "filepond",
            onFilePondDrop: (fieldName, file, metadata, load) => {
              this.fileContainer.push(file);
              load(Date.now());
            },
            required: true,
            // validator: ["required"],
            styleClasses: "col-md-4"
          },
          // {
          //   type: "submit",
          //   styleClasses: "submit-button",
          //   label: "Create Farm",
          //   caption: "Create Farm form",
          //   validateBeforeSubmit: true,
          //   disabled: () => this.isCreatingFarm,
          //   onSubmit: (model, schema) => {
          //     this.isCreatingFarm = true;
          //     this.addManagers = true;
          //     this.newManager = { ...emptyManager };
          //   }
          // }
        ],
      },
      formOptions: {
        validateAfterChanged: true,
      },
      isCreatingFarm: false,
      addManagers: false,
    };
  },
   created: async function () {
$(document).ready(function() {
               feather.replace();
            });
   },
  methods: {
    updateManager: function (manager, isEdit) {
      if (isEdit !== false) {
        this.model.manager_details = _.filter(
          this.model.manager_details,
          function (v, k) {
            return k != isEdit;
          }
        );
      } else {
        const existingManager = _.find(
          this.model.manager_details,
          (emanager) => emanager.email === manager.email
        );

        if (existingManager !== undefined) {
          this.model.manager_details = _.filter(
            this.model.manager_details,
            (emanager) => emanager.email !== manager.email
          );
        }
      }

      this.model.manager_details.push({ ...manager });
      this.addManagers = false;
      this.newManager = { ...emptyManager };
      this.refreshManagerKey();
      this.isEdit = false;
    },
    refreshManagerKey() {
      this.managerKey = Math.random().toString(36).substring(7);
    },
    createFarm: function () {
      return false;
      const isValidated = this.$refs.form.validate();
      if (isValidated !== true) {
        return false;
      }

      this.loading = true;
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
              Object.keys(manager).forEach((k) => {
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
        let file = this.fileContainer[0];
        createFarmRequest.append('farm_image', file, file.name);
        // this.fileContainer.forEach((file, ind) => {
          // createFarmRequest.append(`farm_image[${ind}]`, file, file.name);
        // });
      }

      FarmService.create(createFarmRequest)
        .then(
          (response) => {
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false,
            });
            this.loading = false;
            this.model = emptyFarmRequest;
            this.addManagers = false;
            router.push({ name: "farmsList" });
          },
          (error) => {
            this.loading = false;
            this.$toast.open({
              message: error.response.data.message,
              type: "error",
              position: "bottom-right",
              dismissible: false,
            });
          }
        )
        .finally((_) => {
          this.isCreatingFarm = false;
        });
    },
    addNewManager: function () {
      this.newManager = { ...emptyManager };
      this.addManagers = true;
      this.isEdit = false;
    },
    cancelEditManager: function () {
      this.newManager = { ...emptyManager };
      this.refreshManagerKey();
      this.addManagers = true;
      this.isEdit = false;
    },
    onManagerDelete: function (manager) {
      this.model.manager_details = _.filter(
        this.model.manager_details,
        function (man) {
          return man.email !== manager.email;
        }
      );
    },
    onManagerEdit: function (manager, index) {
      console.log(manager);
      this.newManager = { ...manager };
      this.refreshManagerKey();
      this.addManagers = true;
      this.isEdit = index;
    },
  },
};
</script>
