<template>
  <div class="loc-page logged-in-page">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>Profile</h2>
          </div>
        </div>
      </div>
    </section>

    <section class="profile-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 content-left-outer">
            <div class="each-profile-block">
              <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                class="slide-right"
                autocomplete="off"
              >
                <div class="row">
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>First Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.first_name"
                        required
                        :rules="[v => !!v || 'First Name is required.']"
                        placeholder="Enter First Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.last_name"
                        required
                        :rules="[v => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Email</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.email"
                        required
                        :rules="[
                          v => !!v || 'Email is required.',
                          v =>
                            !v ||
                            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(
                              v
                            ) ||
                            'E-mail must be valid'
                        ]"
                        placeholder="Email"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Phone</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.phone"
                        required
                        :rules="[v => !!v || 'Phone is required.']"
                        placeholder="Phone"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Address</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-textarea
                        v-model="userProfile.address"
                        placeholder="Address"
                        required
                        :rules="[v => !!v || 'Address is required.']"
                      ></v-textarea>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>City</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.city"
                        required
                        :rules="[v => !!v || 'City is required.']"
                        placeholder="City"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>State</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.state"
                        required
                        :rules="[v => !!v || 'State is required.']"
                        placeholder="State"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Country</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.country"
                        required
                        :rules="[v => !!v || 'Country is required.']"
                        placeholder="Country"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Zipcode</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="userProfile.zip_code"
                        required
                        :rules="[v => !!v || 'Zipcode is required.']"
                        placeholder="Zipcode"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Profile Photo</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <file-pond
                        name="userProfile.user_image"
                        ref="pond"
                        label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
                        accepted-file-types="image/jpg, image/gif,image/svg,image/jpeg, image/png"
                        v-bind:server="filePondServer"
                      />
                    </div>
                  </v-col>
                </div>
              </v-form>
              <div class="form-group">
                <button
                  type="button"
                  class="btn btn-success btn-lg btn-block"
                  @click="formSubmit"
                >
                  Submit
                </button>
              </div>
            </div>
          </div>

          <div class="col-sm-6 content-right-outer"></div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import _ from "lodash";
import UserService from "../../../services/UserService";
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

export default {
  components: {
    FilePond
  },
  data() {
    return {
      valid: true,
      userImage: "",
      userProfile: {
        first_name: "",
        last_name: "",
        user_image: "",
        address: "",
        email: "",
        phone: "",
        city: "",
        state: "",
        country: "",
        zip_code: ""
      },
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          // set data
          const formData = new FormData();
          formData.append("user_image", file, file.name);
          this.userProfile.user_image = formData;
          load(Date.now());
        }
      }
    };
  },
  methods: {
    formSubmit: async function() {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          var userProfileRequest = new FormData();

          /**
           * Adding form values to Request
           * except of user_image
           */
          for (var key in this.userProfile) {
            if (key !== "user_image") {
              userProfileRequest.append(key, this.userProfile[key]);
            }
          }

          /**
           * If user image is uploaded then add it to form data
           */
          if (this.userProfile.user_image !== null) {
            userProfileRequest.append(
              "user_image",
              this.userProfile.user_image.get("user_image")
            );
          }

          const response = await UserService.updateProfile(userProfileRequest);
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false
          });
          this.userProfile = {
            ...this.userProfile,
            ...response.data.data
          };
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false
          });
        }
      }
    }
  },
  created: async function() {
    try {
      const response = await UserService.getProfile();
      this.userProfile = {
        ...this.userProfile,
        ...response.data.data
      };
      this.userImage = response.data.data.image_url;
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false
      });
    }
  }
};
</script>
