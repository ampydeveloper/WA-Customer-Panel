<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Create Job</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="create-job-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 content-left-outer">
              <div class="row">
                <v-col cols="12" md="12" class="pt-0 pb-0">
                  <div class="label-align pt-0">
                    <label>Service</label>
                  </div>
                  <div class="pt-0 pb-0">
                    <v-select
                      :items="servicesList"
                      label="Select Service"
                      :rules="[(v) => !!v || 'Service is required.']"
                    ></v-select>
                  </div>
                </v-col>

                <v-col cols="12" md="12" class="pt-0 pb-0">
                  <v-row>
                    <v-col cols="6" md="6" class="pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label>Date</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <v-menu
                          :close-on-content-click="false"
                          :nudge-right="40"
                          transition="scale-transition"
                          offset-y
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on }">
                            <v-text-field
                              prepend-icon="event"
                              readonly
                              v-on="on"
                              required
                              :rules="[(v) => !!v || 'Date is required.']"
                              placeholder="Select Date"
                            ></v-text-field>
                          </template>
                          <v-date-picker @input="menu1 = false"></v-date-picker>
                        </v-menu>
                      </div>
                    </v-col>

                    <v-col cols="6" md="6" class="pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label>Weight</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <v-select
                          :items="servicesList"
                          placeholder="Select Weight"
                          :rules="[(v) => !!v || 'Weight is required.']"
                        ></v-select>
                      </div>
                    </v-col>
                  </v-row>
                </v-col>

                <v-col cols="12" md="12" class="t-s-inner pt-0 pb-0">
                  <div class="row">
                    <div class="label-align pt-0">
                      <label>Time Slots</label>
                    </div>
                    <div class="pt-0">radio buttons</div>
                  </div>
                </v-col>

                <v-col cols="12" md="12" class="pt-0 pb-0">
                  <div class="label-align pt-0">
                    <label>Gate Number</label>
                  </div>
                  <div class="pt-0 pb-0">
                    <v-text-field
                      required
                      :rules="[(v) => !!v || 'Gate Number is required.']"
                      placeholder="Enter Gate Number"
                    ></v-text-field>
                  </div>
                </v-col>

                <v-col cols="12" md="12" class="textarea-parent pt-0 pb-0">
                  <div class="label-align pt-0">
                    <label class="label_text">Notes</label>
                  </div>
                  <div class="pt-0 pb-0">
                    <v-textarea
                      placeholder="Enter Notes"
                      rows="3"
                      auto-grow
                      :rules="[(v) => !!v || 'Notes is required.']"
                      required
                    ></v-textarea>
                  </div>
                </v-col>

                <v-col cols="12" md="12" class="t-s-inner pt-0 pb-0">
                  <div class="row">
                    <div class="pt-0">Upload Images</div>
                  </div>
                </v-col>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="row">
                <v-col cols="12" md="12" class="pt-0 pb-0">
                  <div class="label-align pt-0">
                    <label>Farms</label>
                  </div>
                  <div class="pt-0 pb-0">
                    <v-select
                      :items="servicesList"
                      placeholder="Select Farms"
                      :rules="[(v) => !!v || 'Farms is required.']"
                    ></v-select>
                  </div>
                </v-col>
              </div>
              <div class="map-farm-location">Farm on map</div>

              <!-- <div class="farm-manager">
                <img
                  src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/avatar-s-5.99691e54.jpg"
                  alt="name"
                />
                <h5>Andreson Riggs</h5>
              </div> -->

              <div class="estimate-price">
                <h5>Estimated Price</h5>
                <h3><span>$</span> 1240</h3>
              </div>

              <div class="send-payment">
                <h5 class="heading2">Initiate Payment</h5>
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
                </div>
              </div>

              <v-col class="pt-0 pb-0 create-job" cols="12" md="12">
                <v-btn
                  type="submit"
                  :loading="loading"
                  :disabled="loading"
                  color="success"
                  class="btn-full-green"
                  >Create Job</v-btn
                >
              </v-col>

              <div class="reach-out">
                <span
                  ><plus-icon size="1.5x" class="custom-class"></plus-icon
                ></span>
                React out to us at +91 (345)-7867-787 for any issue. We're all
                ears!
              </div>

              <div class="info-box">
                <span></span>
                Shift on weekdays is rush hour. There might be delay in service.
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
    return {
      loading: false,
      servicesList: ["One", "Teo", "Three"],
      menu1: false,
    };
  },
};
</script>