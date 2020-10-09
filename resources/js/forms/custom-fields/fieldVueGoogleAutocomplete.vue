<template>
  <vue-google-autocomplete
    id="map"
    classname="form-control"
    placeholder="Start typing"
    v-on:placechanged="getAddressData"
>
</vue-google-autocomplete>
</template>

<script>
import { abstractField } from "vue-form-generator";
import { isFunction } from "lodash";
import VueGoogleAutocomplete from 'vue-google-autocomplete';



export default {
  mixins: [abstractField],
  components: {
    VueGoogleAutocomplete
  },
  data() {
    return {
      // getAddressData: {
      //   process: (fieldName, file, metadata, load) => {
      //     if (isFunction(this.schema.onFilePondDrop)) {
      //       this.schema.onFilePondDrop(fieldName, file, metadata, load);
      //     }
      //   }
      // }
    };
  },
  methods: {
    getAddressData: function (addressData, placeResultData, id) {
        console.log(addressData);
    }
  }
};
</script>
