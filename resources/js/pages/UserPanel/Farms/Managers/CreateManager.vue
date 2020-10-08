<template>
  <div class="new-user-signup">
    <h1>New Manager</h1>
    <form action novalidate>
      <vue-form-generator
        tag="div"
        :schema="schema"
        :options="formOptions"
        :model="model"
      />
    </form>
    <button
        class="btn btn-success btn-lg btn-block"
        @click="cancel"
      >
        Cancel
      </button>
  </div>
</template>

<script>
import managerFormSchema from "../../../../forms/managerFormSchema";
import FarmService from "../../../../services/FarmService";
import router from "../../../../router";

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

export default {
  props: ["newManager", "isEdit"],
  data() {
    return {
      model: this.newManager !== undefined ? this.newManager : emptyManager,
      schema: {
        fields: [
          ...managerFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Create Manager",
            caption: "Create Manager form",
            validateBeforeSubmit: true,
            onSubmit: (model, schema) => {
              const { farmId } = this.$route.params;
              if (farmId === undefined) {
                this.$emit("updatemanager", model, this.isEdit);
              } else {
                const managerRequest = { ...this.model, farm_id: farmId };
                FarmService.createManager(managerRequest).then(response => {
                  this.$toast.open(
                    {
                      message: response.data.message,
                      type: "success",
                      position: "top-right",
                      dismissible: false
                    },
                    error => {
                      this.$toast.open({
                        message: error.response.data.message,
                        type: "error",
                        position: "bottom-right",
                        dismissible: false
                      });
                    }
                  );
                });
              }
            }
          },    
        ]
      },
      formOptions: {
        validateAfterChanged: true
      }
    };
  },
  methods: {
    cancel: function() {
      this.$emit("hideAddNewManager");
    }
  }
};
</script>
