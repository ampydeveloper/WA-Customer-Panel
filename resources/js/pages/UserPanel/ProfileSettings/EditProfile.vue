<template>
  <v-app>

    <div class="main-wrapper">
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

            <!-- Profile -->
            <div class="col-sm-6 content-left-outer">
              <div class="each-profile-block">
                <h5 class="heading2">Edit Profile</h5>
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                    class="slide-right"
                    autocomplete="off"
                  >
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="profile-image">
                        <img
                          src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/avatar-s-5.99691e54.jpg"
                          alt="name"
                        />
                        <div class="image-edit">
                          <i data-feather="edit-3"></i>
                        </div>
                      </div>
                    </div>
                    <v-col cols="6" md="6" class="pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label>First Name</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <v-text-field
                          v-model="userProfile.first_name"
                          required
                          :rules="[(v) => !!v || 'First Name is required.']"
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
                          :rules="[(v) => !!v || 'Last Name is required.']"
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
                            (v) => !!v || 'Email is required.',
                            (v) =>
                              !v ||
                              /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(
                                v
                              ) ||
                              'E-mail must be valid',
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
                          :rules="[(v) => !!v || 'Phone is required.']"
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
                          :rules="[(v) => !!v || 'Address is required.']"
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
                          :rules="[(v) => !!v || 'City is required.']"
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
                          :rules="[(v) => !!v || 'State is required.']"
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
                          :rules="[(v) => !!v || 'Country is required.']"
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
                          :rules="[(v) => !!v || 'Zipcode is required.']"
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

                <div class="button-out">
                  <v-btn
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                    color="success"
                    class="btn-full-green"
                    @click="formSubmit"
                    >Save Profile Info</v-btn
                  >
                </div>
              </div>
            </div>
            <!-- /Profile -->

            <!-- Notifications -->
            <div class="col-sm-6 content-right-outer">
              <div class="each-profile-block">
                <h5 class="heading2">All Notifications</h5>
                <div class="all-noti-div">
                  <div class="each-noti clearfix">
                    <div class="details-one">
                      <p class="title-item">New Message</p>
                      <p class="desc-item">You got new order of goods</p>
                    </div>
                    <div class="details-two">
                      <p class="date-item">10 min ago</p>
                    </div>
                  </div>
                  <div class="each-noti clearfix">
                    <div class="details-one">
                      <p class="title-item">New Message</p>
                      <p class="desc-item">You got new order of goods</p>
                    </div>
                    <div class="details-two">
                      <p class="date-item">10 min ago</p>
                    </div>
                  </div>
                  <div class="each-noti clearfix">
                    <div class="details-one">
                      <p class="title-item">New Message</p>
                      <p class="desc-item">You got new order of goods</p>
                    </div>
                    <div class="details-two">
                      <p class="date-item">10 min ago</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Notifications -->

            <!-- Manage Cards -->
            <div class="col-sm-6 content-right-outer">
              <div class="each-profile-block">
                <h5 class="heading2">Payment Info</h5>
                
                <!-- Card Listing -->
                <table class="table payment-info-table basic-table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Card Number</th>
                      <th>Card Expiry</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(card, index) in cardList" :key="card.id">
                      <td>{{ card.name }}</td>
                      <td>**** **** **** {{ card.last_four }}</td>
                      <td>
                        {{ card.card_exp_month }} / {{ card.card_exp_year }}
                      </td>
                      <td><span class="primary-tag">Primary</span></td>
                      <td>
                        <button class="btn btn-sm btn-danger delete-item" @click="deleteCard(card.id)">
                          <i data-feather="x"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- /Card Listing -->
              
                <!-- Add Card -->
                <div class="add-new-card">
                  <a href="javascript:void(0);" @click="showAddCard" id="add-card-link" class="btn-full-green">Add New Card</a>

                  <div class="add-new-card-form" v-if="addNewCard">
                    <h6 class="heading3">Add New Card</h6>

                    <ul class="credit-cards-list list-unstyled">
                      <li>
                        <img src="img/visa.png" alt="" />
                      </li>
                      <li>
                        <img src="img/card2.png" alt="" />
                      </li>
                      <li>
                        <img src="img/card3.png" alt="" />
                      </li>
                      <li>
                        <img src="img/card4.png" alt="" />
                      </li>
                    </ul>

                    <v-form
                        ref="card_add_form"
                        v-model="card_add_valid"
                        lazy-validation
                        class="slide-right"
                        autocomplete="off"
                      >
                      <div class="row">

                        <div class="col-md-12 form-input">
                          <v-text-field
                            v-model="cardDetails.name"
                            required
                            :rules="[(v) => !!v || 'Name on card is required.']"
                            placeholder="Name on Card"
                          ></v-text-field>
                        </div>

                        <div class="col-md-12 form-input">
                          <v-text-field
                            v-model="cardDetails.card_number"
                            required
                            :rules="[
                              (v) => !!v || 'Card Number is required.',
                              (v) =>
                                !v ||
                                /^(?:(4[0-9]{12}(?:[0-9]{3})?)|(5[1-5][0-9]{14})|(6(?:011|5[0-9]{2})[0-9]{12})|(3[47][0-9]{13})|(3(?:0[0-5]|[68][0-9])[0-9]{11})|((?:2131|1800|35[0-9]{3})[0-9]{11}))$/.test(
                                  v
                                ) ||
                                'Card Number must be valid.',
                            ]"
                            placeholder="Enter Card Number"
                          ></v-text-field>
                        </div>

                        <div class="col-md-4 form-input">
                          <v-select
                            v-model="cardDetails.card_exp_month"
                            :items="expiryMonths"
                            placeholder="Enter Expiry Month"
                            :rules="[(v) => !!v || 'Expiry Month is required.']"
                          ></v-select>
                        </div>
                        
                        <div class="col-md-4 form-input">
                          <v-select
                            v-model="cardDetails.card_exp_year"
                            :items="expiryYears"
                            placeholder="Enter Expiry Year"
                            :rules="[(v) => !!v || 'Expiry Year is required.']"
                          ></v-select>
                        </div>

                        <div class="col-md-4 form-input">
                          <v-text-field
                            v-model="cardDetails.cvv"
                            type="password"
                            required
                            :rules="[
                              (v) => !!v || 'CVV is required.',
                              (v) =>
                                !v ||
                                /^[0-9]{3,3}$/.test(v) ||
                                'CVV must be valid.',
                            ]"
                            placeholder="Enter CVV"
                          ></v-text-field>
                        </div>
                      </div>
                    </v-form>
                      <div class="button-out">
                        <v-btn
                          type="submit"
                          :loading="loading"
                          :disabled="loading"
                          color="success"
                          class="btn-full-green"
                          @click="cardAddSubmit"
                          >Save Payment Info</v-btn
                        >
                      </div>
                  </div>
                </div>
                <!-- /Add Card -->

              </div>
            </div>
            <!-- /Manage Cards -->


          </div>
        </div>
      </section>
    </div>

  </v-app>
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

import CardService from "../../../services/CardService";
import router from "../../../router";
import moment from "moment";

// Create component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

export default {
  components: {
    FilePond,
  },
  data() {
    return {
      userImage: "",
      cardList: [],
      loading: false,
      valid: true,
      card_add_valid : true,
      addNewCard: false,
      cardDetails: {
        name: "",
        card_number: "",
        card_exp_month: "",
        card_exp_year: "",
        cvv: "",
      },
      expiryMonths: _.range(1, 13),
      expiryYears: _.range(
        moment().format("YYYY"),
        moment().add(21, "year").format("YYYY")
      ),
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
        zip_code: "",
      },
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          // set data
          const formData = new FormData();
          formData.append("user_image", file, file.name);
          this.userProfile.user_image = formData;
          load(Date.now());
        },
      },
    };
  },
  methods: {

    formSubmit: async function () {
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
          if (
            this.userProfile.user_image !== null &&
            this.userProfile.user_image instanceof FormData
          ) {
            userProfileRequest.append(
              "user_image",
              this.userProfile.user_image.get("user_image")
            );
          }

          const response = await UserService.updateProfile(userProfileRequest);
          if (response !== undefined && response.data !== undefined) {
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false,
            });
            this.userProfile = {
              ...this.userProfile,
              ...response.data.data,
            };
            window.localStorage.removeItem("user");
            window.localStorage.setItem(
              "user",
              JSON.stringify(response.data.data)
            );
          }
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false,
          });
        }
      }
    },

    cardAddSubmit: async function () {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          const response = await CardService.create(this.cardDetails);
          if (response !== undefined && response.data !== undefined) {
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false,
            });
            this.addNewCard = false;
            CardService.list().then((response) => {
              this.cardList = response.data.data;
            });
          }
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false,
          });
        }
      }
    },

    deleteCard: async function (id) {
      try {
        const response = await CardService.delete(id);
        if (response !== undefined && response.data !== undefined) {
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false,
          });
          CardService.list().then((response) => {
            this.cardList = response.data.data;
          });
        }
      } catch (error) {
        this.$toast.open({
          message: error.response.data.message,
          type: "error",
          position: "bottom-right",
          dismissible: false,
        });
      }
    },
    
    showAddCard: function() {
      this.addNewCard = true;
    }
  },
  created: async function () {
    try {
      const response = await UserService.getProfile();
      this.userProfile = {
        ...this.userProfile,
        ...response.data.data,
      };
      this.userImage = response.data.data.image_url;
      
      CardService.list().then((response) => {
        this.cardList = response.data.data;
      });
      
      this.expiryMonths = [...this.expiryMonths].map((month) => {
        return month < 10 ? `0${month}` : month;
      });

    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false,
      });
    }
  },
};
</script>
