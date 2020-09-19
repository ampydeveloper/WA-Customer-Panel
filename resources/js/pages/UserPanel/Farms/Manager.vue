<template>
  <div class="new-user-signup">
    <form action novalidate>
      <vue-form-generator
        tag="div"
        :schema="schema"
        :options="formOptions"
        :model="model"
      />
    </form>
  </div>
</template>

<script>
import managerFormSchema from "../../../forms/managerFormSchema";
export default {
  props: ["newManager"],
  data() {
    return {
      model: this.newManager,
      schema: {
        fields: [
          ...managerFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Create Farm",
            caption: "Create Farm form",
            validateBeforeSubmit: true,
            onSubmit: (model, schema) => {
              this.$emit("updatemanager", model);
            }
          }
        ]
      },
      formOptions: {
        validateAfterChanged: true
      }
    };
  }
};
</script>
