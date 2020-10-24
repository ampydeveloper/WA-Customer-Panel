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
            </div>
            <div class="basic-grey-box">
              <create-manager
                v-on:updatemanager="updateManager"
                v-on:hideAddNewManager="hideAddNewManager"
                v-bind:new-manager="newManager"
                v-bind:is-edit="isEdit"
              />
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

export default {
  components: {
    CreateManager,
  },
  data() {
    return {
      model: {
        farm_address: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: 1,
        latitude: "",
        longitude: "",
      },
      schema: {
        fields: [
          ...farmFormSchema.fields,
          // {
          //   type: "radios",
          //   label: "Active Status",
          //   model: "farm_active",
          //   values: [
          //     { name: "Active", value: 1 },
          //     { name: "Inactive", value: 0 }
          //   ]
          // },
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Create Farm",
            caption: "Create Farm form",
            validateBeforeSubmit: true,
            disabled: () => this.isCreatingFarm,
            onSubmit: (model, schema) => {
              FarmService.update(this.$route.params.farmId, this.model)
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
            },
          },
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
  },
  methods: {
    hideAddNewManager: function () {
      this.addManagers = false;
    },
  },
};
</script>
