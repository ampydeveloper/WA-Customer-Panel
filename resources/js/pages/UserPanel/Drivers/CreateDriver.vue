<style scoped>
  .custom-col{
    margin-top: 1rem;
  }
</style>
<template>
  <v-app class="sign-up-form-outer">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>
                Create<br />
                <span class="bg-custom-thickness"> Driver </span>
              </h2>
            </div>
          </div>
        </div>
      </section>

      <section class="create-job-outer center-content-outer" data-aos="">
        <div class="container">
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            class="slide-right"
            autocomplete="off"
          >
            <v-row>
              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <div class="custom-col row custom-img-holder">
                  <v-col sm="4" class="label-align pt-0 image-upload-label">
                    <label>Profile Image</label>
                  </v-col>
                  <v-col sm="8" class="pt-0 pb-0">
                      <file-pond
                      name="uploadImage"
                      v-bind:allow-multiple="false"
                      ref="pond"
                      label-idle="Drop file here or <span class='filepond--label-action'>Browse</span>"
                      accepted-file-types="image/jpg,image/jpeg, image/png"
                      v-bind:server="filePondServer"
                    />
                  </v-col>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="pl-0 manager-cols" cols="2" md="2" >
                <div class="custom-col">
                  <span class="label-align pt-0">
                    <label>Salutation</label>
                  </span>
                  <v-select
                    :items="prefixes"
                    :rules="[v => !!v || 'Driver Salutation is required.']"
                    v-model='addForm.prefix'
                  ></v-select>
                </div>
              </v-col>
              <v-col col="5" md="5" class="pl-0 manager-cols">
                <div class="custom-col">
                    <span class="label-align pt-0">
                    <label>First Name</label>
                    </span>
                    <v-text-field
                      v-model="addForm.driver_first_name"
                      :rules="[v => !!v || 'Driver First Name is required.']"
                      required
                      placeholder
                    ></v-text-field>
                </div>
              </v-col>
              <v-col col="5" md="5" class="pl-0 manager-cols">
                <div class="custom-col">
                  <span class="label-align pt-0">
                    <label>Last Name</label>
                  </span>
                  <v-text-field
                    v-model="addForm.driver_last_name"
                    :rules="[v => !!v || 'Driver Last Name is required.']"
                    required
                    placeholder
                  ></v-text-field>
                </div>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <div class="custom-col">
                  <span class="label-align pt-0">
                    <label>Mobile Number</label>
                  </span>
                  <v-text-field
                    v-model="addForm.driver_phone"
                    :rules="phoneRules"
                    required
                    placeholder
                    maxlength="10"
                  ></v-text-field>
                </div>
              </v-col>
              <v-col cols="6" md="6" class="pl-0 manager-cols">
                <div class="custom-col">
                  <span class="label-align pt-0">
                    <label>Email</label>
                  </span>
                  <v-text-field
                    v-model="addForm.email"
                    :rules="emailRules"
                    name="email"
                    required
                    placeholder
                  ></v-text-field>
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" md="12">
                <div class="custom-col row">
                  <v-col sm="4" class="pt-0 pb-0">
                    <v-btn
                      type="button"
                      :loading="loading"
                      :disabled="loading"
                      class="btn-full-green"
                      @click="formSubmit"
                      >Create Driver <i data-feather="arrow-right"></i
                    ></v-btn>
                  </v-col>
                </div>
              </v-col>
            </v-row>
          </v-form>
        </div>

        <sub-footer />
      </section>
    </div>
  </v-app>
</template>

<script>
import subFooter from "../subFooter";
import DriverService from "../../../services/DriverService";
import router from "../../../router";
import _ from "lodash";
import moment from "moment";

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
  components: {
    FilePond,
    subFooter,
  },
  data() {
    return {
      valid: true,
      prefixes: ['Mr', 'Miss', 'Mrs.'],
      addForm:{
        prefix: "",
        driver_first_name: "",
        driver_last_name: "",
        email: "",
        driver_phone: "",
        hauler_id: 0,
      },
      emailRules: [
        (v) => !!v || "Email is required.",
        (v) => /.+@.+/.test(v) || "Email must be valid.",
      ],
      phoneRules: [
        (v) => !!v || "Phone number is required.",
        (v) => /^\d*$/.test(v) || "Enter valid number.",
        (v) => v.length >= 10 || "Enter valid number.",
      ],
      requiredRules: [(v) => !!v || "This field is required."],
      submitted: false,
      loading: false,
      returnUrl: "",
      fileContainer: [],
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          this.fileContainer.push(file);
          load(Date.now());
        },
      },
    };
  },
  created: async function () {
    const user = JSON.parse(window.localStorage.getItem('user'));
    this.addForm.hauler_id = user.id;
  },
  methods: {
    formSubmit: async function () {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          var createDriverRequest = new FormData();

          /**
           * Adding form values to Request
           * except of user_image
           */
          for (var key in this.addForm) {
            createDriverRequest.append(key, this.addForm[key]);
          }

          /**
           * If user image is uploaded then add it to form data
           */
          if (this.fileContainer.length > 0) {
            this.fileContainer.forEach((file, ind) => {
              createDriverRequest.append(`driver_image`, file, file.name);
            });
          }
          
          const response = await DriverService.create(createDriverRequest);
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false,
          });

          setTimeout(() => {
            router.push({
              name: "DriversDashboard"
            });
          }, 2000);
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false,
          });
        }
      }
    }
  },
};
</script>
