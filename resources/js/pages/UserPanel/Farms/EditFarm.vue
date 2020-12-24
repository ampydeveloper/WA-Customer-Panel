<template>
  <div class="main-wrapper">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>
              Edit
              <br />
              <span class="bg-custom-thickness"> Farm </span>
            </h2>
          </div>
        </div>
      </div>
    </section>

    <section class="create-farm-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="basic-grey-box">
              <h5 class="heading2">Edit Farm Details</h5>
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
                <v-col cols="4" md="4" class="pt-0 pb-0" v-if='model.farm_image != null && model.farm_image.length > 0'>
                  <div class="label-align pt-0">
                    <label class="label_text">Existing Photo <small>(Uploading new one will replace following existing one!)</small></label>
                  </div>
                  <v-row>
                    <v-col
                      v-for="(src, n) in model.farm_image"
                      :key="n"
                      class="d-flex child-flex"
                      cols="4"
                    >
                    <v-img
                      max-height="300"
                      max-width="300"
                      :src="src.replace('/storage/', '/storage/user_images/')"
                      aspect-ratio="1"
                      class="grey lighten-2"
                    >
                      <template v-slot:placeholder>
                        <v-row
                          class="fill-height ma-0"
                          align="center"
                          justify="center"
                        >
                          <v-progress-circular
                            indeterminate
                            color="grey lighten-5"
                          ></v-progress-circular>
                        </v-row>
                      </template>
                    </v-img>
                    <!-- <img width="200" :src="src.replace('/storage/', '/storage/user_images/')"/> -->
                    </v-col>
                  </v-row>
                </v-col>
            </div>
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
                  {{ `${manager.manager_first_name} ${manager.manager_last_name}` }}
                  / {{ manager.email }}

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
                <div class="basic-button-out clearfix mt-3">
                  <button class="btn-full-green" @click="saveFarm">
                    Save <i data-feather="arrow-right"></i>
                  </button>
                </div>
              </ul>
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
  latitude: "",
  longitude: "",
  manager_details: [],
};

export default {
  components: {
    CreateManager,
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
      model: {... emptyFarmRequest},
      fileContainer: [],
      isEdit: false,
      schema: {
        fields: [
          {
            label: 'Address',
            type: "vueGoogleAutocomplete",
            required: false,
            model: 'farm_address',
            placeHolder: 'farm_address',
            validator: ["required", "string"],
            styleClasses:'col-md-4',
            getAddressData : (addressData) => {
              if(addressData.route) this.model.farm_address = addressData.route
              if(addressData.locality) this.model.farm_city = addressData.locality
              if(addressData.administrative_area_level_1) this.model.farm_province = addressData.administrative_area_level_1
              if(addressData.postal_code) this.model.farm_zipcode = addressData.postal_code
              if(addressData.longitude) this.model.longitude = addressData.longitude
              if(addressData.latitude) this.model.latitude = addressData.latitude
              // this.model.farm_address = addressData;
            }
          },
          ...farmFormSchema.fields,
          {
            type: "filepond",
            onFilePondDrop: (fieldName, file, metadata, load) => {
              this.fileContainer.push(file);
              load(Date.now());
            },
            required: false,
            validator: ["required", (value, field, model) => {
              console.log(value, field, model);
            }],
            styleClasses: "col-md-4",
          },
          // {
          //   type: "radios",
          //   label: "Active Status",
          //   model: "farm_active",
          //   values: [
          //     { name: "Active", value: 1 },
          //     { name: "Inactive", value: 0 }
          //   ]
          // },
          // {
          //   label: 'Place',
          //   type: "vueGoogleAutocomplete",
          //   model: "farm_place",
          //   // required: true,
          //   // validator: ["required"],
          //   styleClasses:'col-md-4'  ,
          //   onGetAddressData : ($event) => {
          //     console.log($event);
          //   }
          // },
          // {
          //   type: "submit",
          //   styleClasses: "submit-button",
          //   label: "Create Farm",
          //   caption: "Create Farm form",
          //   validateBeforeSubmit: true,
          //   disabled: () => this.isCreatingFarm,
          //   onSubmit: (model, schema) => {
          //     FarmService.update(this.$route.params.farmId, this.model)
          //       .then(
          //         (response) => {
          //           this.$toast.open({
          //             message: response.data.message,
          //             type: "success",
          //             position: "top-right",
          //             dismissible: false,
          //           });
          //           router.push({ name: "farmsList" });
          //         },
          //         (error) => {
          //           this.$toast.open({
          //             message: error.response.data.message,
          //             type: "error",
          //             position: "bottom-right",
          //             dismissible: false,
          //           });
          //         }
          //       )
          //       .finally((_) => {
          //         this.isCreatingFarm = false;
          //       });
          //   },
          // },
        ],
      },
      formOptions: {
        validateAfterChanged: true,
      },
      isCreatingFarm: false,
    };
  },
  created: async function () {
    const {
      data: { data: farmDetails },
    } = await FarmService.get(this.$route.params.farmId);
    this.model = { ...farmDetails };
    if(this.model.farm_image != null){
      this.model.farm_image = JSON.parse(this.model.farm_image);
    }

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

      this.model.manager_details.push({ ...manager});
      this.addManagers = false;
      this.newManager = { ...emptyManager };
      this.refreshManagerKey();
      this.isEdit = false;
    },
    refreshManagerKey(){
      this.managerKey = Math.random().toString(36).substring(7);
    },
    cancelEditManager: function () {
      this.newManager = { ...emptyManager };
      console.log(this.newManager);
      this.refreshManagerKey();
      this.addManagers = true;
      this.isEdit = false;
    },
    onManagerEdit: function (manager, index) {
      this.newManager = { ...manager };
      this.refreshManagerKey();
      this.addManagers = true;
      this.isEdit = index;
    },
    saveFarm(){
      const isValidated = this.$refs.form.validate();
      if (isValidated !== true) {
        return false;
      }
      var saveFarmRequest = new FormData();

      /**
       * Adding form values to Request
       * except of user_image
       */
      for (var key in this.model) {
        if (key !== "manager_details") {
          saveFarmRequest.append(key, this.model[key]);
        }else {
          if (this.model.manager_details.length > 0) {
            this.model.manager_details.forEach((manager, ind) => {
              Object.keys(manager).forEach((k) => {
                saveFarmRequest.append(
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
        let fl = this.fileContainer[0];
        saveFarmRequest.append('farm_image', fl, fl.name)
      }
       FarmService.update(this.$route.params.farmId, saveFarmRequest)
          .then(
            (response) => {
              this.$toast.open({
                message: response.data.message,
                type: "success",
                position: "top-right",
                dismissible: false,
              });
              router.push({ name: "farmsList" });
            },
            (error) => {
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
    }
  },
};
</script>
