<template>
  <div>
    <div class="sign-up-form-outer">
      <div class="sign-up-form-inner">
        <div class="row">
          <div class="col-md-12">
            <div class="new-user-signup">
              <form action novalidate>
                <h1>Edit Farm</h1>
                <vue-form-generator
                  tag="div"
                  :schema="schema"
                  :options="formOptions"
                  :model="model"
                />
              </form>
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
import router from "../../../router";

export default {
  data() {
    return {
      model: {
        farm_address: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: 1,
        latitude: "",
        longitude: ""
      },
      schema: {
        fields: [
          ...farmFormSchema.fields,
          {
            type: "radios",
            label: "Active Status",
            model: "farm_active",
            values: [
              { name: "Active", value: 1 },
              { name: "Inactive", value: 0 }
            ]
          },
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
                  response => {
                    this.$toast.open({
                      message: response.data.message,
                      type: "success",
                      position: "top-right",
                      dismissible: false
                    });
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
            }
          }
        ]
      },
      formOptions: {
        validateAfterChanged: true
      },
      isCreatingFarm: false
    };
  },
  created: async function() {
    const {
      data: { data: farmDetails }
    } = await FarmService.get(this.$route.params.farmId);
    this.model = { ...farmDetails };
  }
};
</script>
