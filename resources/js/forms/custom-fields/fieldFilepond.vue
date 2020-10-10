<template>
  <file-pond
    name="filePond"
    v-bind:allow-multiple="schema.allowMultiple"
    ref="pond"
    label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
    accepted-file-types="image/jpg,image/jpeg, image/png"
    v-bind:server="filePondServer"
    v-bind:files="schema.files"
    v-bind:required="schema.required"
    v-on:init="handleFilePondInit"
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
        },
        load: (source, load, error, progress, abort, headers) => {
          console.log(this.schema);
          console.log(a);
        }
      }
    };
  },
   methods: {
        handleFilePondInit: function() {
          console.log(this.schema);
          // var a = this.schema.getUploadedFile();
          // console.log(a);
            console.log('FilePond has initialized');
            // example of instance method call on pond reference
            // console.log(this);
            // this.$refs.pond.addFiles(schema.files)
            // this.$refs.pond.getFiles();
        }
    },
};
</script>
