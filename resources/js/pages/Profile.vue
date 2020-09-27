<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
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
            <div class="col-sm-6 content-left-outer">
              <div class="each-profile-block">
                <h5 class="heading2">Edit Profile</h5>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="profile-image">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/avatar-s-5.99691e54.jpg"
                        alt="name"
                      />
                      <div class="image-edit"><i data-feather="edit-3"></i></div>
                    </div>
                  </div>

                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>First Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
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
                        required
                        :rules="[(v) => !!v || 'Email is required.']"
                        placeholder="Enter Email"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Phone Number</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Phone Number is required.']"
                        placeholder="Enter Phone Number"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Address</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Address is required.']"
                        placeholder="Enter Address"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>City</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'City is required.']"
                        placeholder="Enter City"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Province</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Province is required.']"
                        placeholder="Enter Province"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Zipcode</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Zipcode is required.']"
                        placeholder="Enter Zipcode"
                      ></v-text-field>
                    </div>
                  </v-col>

                  <div class="button-out">
                    <v-btn
                      type="submit"
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      class="btn-full-green"
                      >Save Profile Info</v-btn
                    >
                  </div>
                </div>
              </div>
            </div>

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

            <div class="col-sm-6 content-right-outer">
              <div class="each-profile-block">
                <h5 class="heading2">Payment Info</h5>
                <table class="table payment-info-table">
                  <tr>
                    <th>Card Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  <tr>
                    <td>**** **** **** 9078</td>
                    <td><span class="primary-tag">Primary</span></td>
                    <td>
                      <span class="delete-item"><i data-feather="x"></i></span>
                    </td>
                  </tr>
                  <tr>
                    <td>**** **** **** 9078</td>
                    <td><span class="primary-tag">Primary</span></td>
                    <td>
                      <span class="delete-item"><i data-feather="x"></i></span>
                    </td>
                  </tr>
                </table>

                <div class="add-new-card">
                  <a href="#" id="add-card-link" class="btn-full-green">Add New Card</a>

                  <div class="add-new-card-form">
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

                    <div class="row">
                      <div class="col-md-12 form-input">
                        <v-text-field
                          required
                          :rules="[(v) => !!v || 'Name on card is required.']"
                          placeholder="Name on Card"
                        ></v-text-field>
                      </div>
                      <div class="col-md-12 form-input">
                        <v-text-field
                          required
                          size="20"
                          :rules="[(v) => !!v || 'Card Number is required.']"
                          placeholder="Card Number"
                        ></v-text-field>
                      </div>
                      <div class="col-md-4 form-input">
                        <select
                          id="exp_month"
                          data-stripe="exp_month"
                          name="exp_month"
                          class="form-control required"
                        >
                          <option value="">Exp. Month</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                        </select>
                      </div>
                      <div class="col-md-4 form-input">
                        <select
                          id="exp_year"
                          data-stripe="exp_year"
                          name="exp_year"
                          class="form-control required"
                        >
                          <option value="">Exp. Year</option>
                          <option value="2020">2020</option>
                          <option value="2019">2019</option>
                        </select>
                      </div>
                      <div class="col-md-4 form-input">
                        <v-text-field
                          size="4"
                          required
                          :rules="[(v) => !!v || 'CVV is required.']"
                          placeholder="CVV"
                        ></v-text-field>
                      </div>

                      <div class="button-out">
                    <v-btn
                      type="submit"
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      class="btn-full-green"
                      >Save Payment Info</v-btn
                    >
                  </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <app-small-footer />
  </div>
</template>

<script>
import AppSmallHeader from "../shared/components/AppSmallHeader";
import AppSmallFooter from "../shared/components/AppSmallFooter";
// import {
//   PlusIcon,
// } from "vue-feather-icons";
export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
    //  PlusIcon,
  },
  data() {
    return {};
  },
};
</script>