<template>
  <file-pond
    name="filePond"
    v-bind:allow-multiple="true"
    ref="pond"
    label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
    accepted-file-types="image/jpg,image/jpeg, image/png"
    v-bind:server="filePondServer"
  />
</template>

<script>
import { abstractField } from "vue-form-generator";
import { isFunction } from "lodash";

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

export default {
  mixins: [abstractField],
  components: {
    FilePond
  },
  data() {
    return {
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          if (isFunction(this.schema.onFilePondDrop)) {
            this.schema.onFilePondDrop(fieldName, file, metadata, load);
          }
        }
      }
    };
  }
};
</script>
