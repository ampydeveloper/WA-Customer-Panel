<template>
  <div class="custom-forms">
    <h5 class="heading2" v-text='isEdit === false ? "Add Manager Details" : "Edit Manager"'></h5>
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
    <button
        class="btn btn-outline-green"
        :style="isEdit === false ? 'display: none': ''"
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
  manager_card_image: [],
  manager_id_card: "",
  salary: ""
};

export default {
  props: ["newManager", "isEdit"],
  data() {
    return {
      formValid: false,
      model: this.newManager !== undefined ? this.newManager : emptyManager,
      schema: {
        fields: [
          ...managerFormSchema.fields,
          {
            type: "filepond",
            label: "Card Image",
            allowMultiple: false,
            onFilePondDrop: (fieldName, file, metadata, load) => {
              this.model.manager_card_image = [];
              console.log(file);
              this.model.manager_card_image.push(file);
              window.ttt=file;
              load(Date.now());
            },
            required: true,
            styleClasses:'col-md-4'
          },
          {
            type: "submit",
            styleClasses: "submit-button col-md-12",
            buttonText: this.isEdit === false ? "Add Manager" : "Save",
            caption: "Create Manager form",
            validateBeforeSubmit: true,
            disabled: (model, field, form) => this.formValid,
            onSubmit: (model, schema) => {
              const { farmId } = this.$route.params;
              this.$emit("updatemanager", model, this.isEdit);
            },
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
      this.$emit("cancelEditManager");
    },
    removeExisting: function(){
      this.model.manager_card_image = []
    },
    onValidated(isValid, errors) {
      this.formValid = !isValid;
    }
  }
};
</script>
