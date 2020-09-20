<template>
  <div>
    <div class="sign-up-form-outer">
      <div class="sign-up-form-inner">
        <div class="row">
          <div class="col-md-12">
            <div class="new-user-signup">
              <form action novalidate>
                <h1>Start Job</h1>
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
import jobFormSchema from "../../../forms/jobFormSchema";
import JobService from "../../../services/JobService";

export default {
  data() {
    return {
      model: {
        service: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: 1,
        latitude: "",
        longitude: ""
      },
      schema: {
        fields: [
          ...jobFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Create Job",
            caption: "Create Job form",
            validateBeforeSubmit: true,
            disabled: () => this.isCreatingJob,
            onSubmit: (model, schema) => {
              JobService.create(this.model)
                .then(
                  response => {
                    this.$toast.open({
                      message: response.data.message,
                      type: "success",
                      position: "top-right",
                      dismissible: false
                    });
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
                  this.isCreatingJob = false;
                });
            }
          }
        ]
      },
      formOptions: {
        validateAfterChanged: true
      },
      isCreatingJob: false,
      timeSlotList: []
    };
  },
  created: async function() {
    const {
      data: { data: serviceList }
    } = await JobService.listServices();
    this.schema.fields.map((fieldObject, index) => {
      if (fieldObject.model === "service") {
        this.schema.fields[index].values = [...serviceList].map(service => {
          return {
            id: service.id,
            name: service.service_name
          };
        });
      }
    });
  }
};
</script>
