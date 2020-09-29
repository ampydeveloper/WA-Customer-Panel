<template>
  <button
    class="form-control"
    :disabled="disabled"
    :maxlength="schema.max"
    :placeholder="schema.placeholder"
    :readonly="schema.readonly"
    style="background-color: grey; height: 57px;"
    @click="onClick"
  >
    Cancel
  </button>
</template>

<script>
import { abstractField } from "vue-form-generator";
import { isFunction } from "lodash";

export default {
  mixins: [abstractField],
  methods: {
    onClick($event) {
      if (isFunction(this.schema.onCancel)) {
        this.schema.onCancel(this.model, this.schema, $event);
      }
    }
  }
};
</script>
