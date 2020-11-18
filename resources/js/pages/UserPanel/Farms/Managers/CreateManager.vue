<template>

<div :class="{'main-wrapper' : standalone}">
    <section :class="'page-section-top ' + (standalone == false ? 'd-none' : '')" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h2>
              <h2 v-text='isEditS === false ? "Create" : "Edit"'></h2> A Farm
              <span class="bg-custom-thickness"> Manager </span>
            </h2>
          </div>
          <div class="col-md-6">
              <div class="desc-details pickup-desc-details">
                 <h2>
                   Add the <span class="bg-custom-thickness">farm manager details</span><br> & create a <span class="bg-custom-thickness">manager</span>.
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
            <div :class="{'basic-grey-box':standalone}">
              <div class="custom-forms">
                <h5 class="heading2" v-if='!standalone' v-text='isEdit === false ? "Add Farm Manager Details" : "Edit Farm Manager Details"'></h5>
                <v-row align="center" v-if='standalone'>
                  <v-col class="d-flex pb-0 mb-0" cols="12" sm="4">
                    <label for='farms' class="font-weight-bold mr-2"><span>Farm</span></label><br/>
                    <select class="form-control required" v-model="farmId">
                      <option value="">Select Farm</option>
                      <option :value="farm.value" v-bind:key='farm.value' v-for='farm in farms' v-text='farm.text'></option>
                    </select>
                    <!-- <v-select data-app v-model='farmId' :items="farms" item-text="text" item-value="value"></v-select> -->
                  </v-col>
                </v-row>
                <form action novalidate>
                  <vue-form-generator
                    tag="section"
                    ref="managerForm"
                    :schema="schema"
                    :options="formOptions"
                    :model="model"
                    @validated="onValidated"
                  />
                </form>
                <!-- <button
                    class="btn btn-outline-green"
                    :style="isEdit === false ? 'display: none': ''"
                    @click="cancel"
                  >
                    Cancel
                  </button> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>  
</template>

<script>
import managerFormSchema from "../../../../forms/managerFormSchema";
import FarmService from "../../../../services/FarmService";
import router from "../../../../router";
import subFooter from "../../subFooter";

const emptyManager = {
  manager_prefix: "",
  manager_first_name: "",
  manager_last_name: "",
  email: ``,
  manager_phone: "",
  manager_address: "",
  manager_city: "",
  manager_province: "",
  manager_zipcode: "",
  manager_card_image: [],
  manager_id_card: "",
  salary: ""
};

export default {
  components: {
       subFooter,
  },
  props: ["newManager", "isEdit"],
  data() {
    return {
      isEditS: false,
      farmId: null,
      farms: [],
      managerId: null,
      standalone: false,
      formValid: false,
      model: this.newManager !== undefined ? this.newManager : emptyManager,
      schema: {
        fields: [
          ...managerFormSchema.fields,
          // {
          //   type: "filepond",
          //   label: "Card Image",
          //   allowMultiple: false,
          //   onFilePondDrop: (fieldName, file, metadata, load) => {
          //     this.model.manager_card_image = [];
          //     // console.log(file);
          //     this.model.manager_card_image.push(file);
          //     window.ttt=file;
          //     load(Date.now());
          //   },
          //   required: true,
          //   styleClasses:'col-md-4'
          // },
          {
            type: "submit",
            styleClasses: "submit-button col-md-12",
            buttonText: this.isEdit === false ? "Add Manager" : "Save",
            caption: "Create Manager form",
            validateBeforeSubmit: true,
            disabled: (model, field, form) => this.formValid,
            onSubmit: (model, schema) => {
              if(!this.standalone){
                const { farmId } = this.$route.params;
                this.$emit("updatemanager", model, this.isEdit);
              }else{
                const isValidated = this.$refs.managerForm.validate();
                if (isValidated !== true) {
                  return false;
                }
                var createManagerRequest = new FormData();

                /**
                 * Adding form values to Request
                 * except of user_image
                 */
                for (var key in this.model) {
                  if(key == 'manager_card_image'){
                    if (this.model.manager_card_image > 0) {
                      let file = this.model.manager_card_image[0];
                      createManagerRequest.append('manager_image', file, file.name);
                    }
                  }else{
                    createManagerRequest.append(key, this.model[key]);
                  }
                }
                createManagerRequest.append('farm_id', this.farmId);
                if(this.isEditS == true){
                  FarmService.saveManager(createManagerRequest, this.farmId, this.managerId)
                    .then(
                      (response) => {
                        this.$toast.open({
                          message: response.data.message,
                          type: "success",
                          position: "top-right",
                          dismissible: false,
                        });
                        this.model = emptyManager;
                        router.push({ name: "ManagersDashboard" });
                      },
                      (error) => {
                        this.$toast.open({
                          message: error.response.data.message,
                          type: "error",
                          position: "bottom-right",
                          dismissible: false,
                        });
                      }
                    );
                }else{
                  FarmService.createManager(createManagerRequest, this.farmId)
                    .then(
                      (response) => {
                        this.$toast.open({
                          message: response.data.message,
                          type: "success",
                          position: "top-right",
                          dismissible: false,
                        });
                        this.model = emptyManager;
                        router.push({ name: "ManagersDashboard" });
                      },
                      (error) => {
                        this.$toast.open({
                          message: error.response.data.message,
                          type: "error",
                          position: "bottom-right",
                          dismissible: false,
                        });
                      }
                    );
                }
              }
            },
          },    
        ]
      },
      formOptions: {
        validateAfterChanged: true
      }
    };
  },
  watch:{
    '$route' (to, from) {
      window.location.reload();
    }
  },
  methods: {
    cancel: function() {
      if(this.standalone == true){
        this.model = emptyManager;
        router.push({ name: "ManagersDashboard" });
      }else{
        this.$emit("cancelEditManager");
      }
    },
    removeExisting: function(){
      this.model.manager_card_image = []
    },
    onValidated(isValid, errors) {
      this.formValid = !isValid;
    }
  },
  beforeCreate(){
    FarmService.list().then(response => {
      this.farms = [...response.data.farms.map((farm) => {
                                  return {
                                    text: farm.farm_address,
                                    value: farm.id,
                                  }
                                })];
      if(this.farms.length > 0){ this.farmId = this.farms[0].value; }
    });
  },
  created(){
    if(['createManager', 'editManager'].includes(this.$route.name)){ 
      this.standalone = true;
    }
    if(this.$route.name == 'editManager'){
      this.isEditS = true;
      FarmService.getManager(this.$route.params.managerId).then(response => {
        this.farmId = response.data.data[0].farm_id;
        this.managerId = this.$route.params.managerId;
        this.model = response.data.data.map((manager) => {
          return {
            manager_prefix: manager.prefix,
            manager_first_name: manager.first_name,
            manager_last_name: manager.last_name,
            email: manager.email,
            manager_phone: manager.phone,
            manager_address: manager.address,
            manager_city: manager.city,
            manager_province: manager.state,
            manager_zipcode: manager.zip_code,
          }
        })[0];
        this.model.manager_is_active = 1;
      });
    }
    $(document).ready(function() {
      feather.replace();
    });
  }
};
</script>
