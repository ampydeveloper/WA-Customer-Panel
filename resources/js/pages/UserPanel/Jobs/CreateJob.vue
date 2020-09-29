<template>
  <v-app class="sign-up-form-outer">
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
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            class="slide-right"
            autocomplete="off"
          >
            <div class="row">
              <div class="col-sm-6 content-left-outer">
                <div class="row">
                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Service</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-select
                        v-model="jobRequest.service_id"
                        :items="serviceList"
                        label="Select Service"
                        :rules="[v => !!v || 'Service is required.']"
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
                            v-model="menu2"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="jobRequest.job_providing_date"
                                readonly
                                placeholder="Select Date"
                                v-on="on"
                              ></v-text-field>
                            </template>
                            <v-date-picker
                              v-model="jobRequest.job_providing_date"
                              @input="menu2 = false"
                            ></v-date-picker>
                          </v-menu>
                        </div>
                      </v-col>

                      <v-col
                        cols="6"
                        md="6"
                        class="pt-0 pb-0"
                        v-if="weightShow"
                      >
                        <div class="label-align pt-0">
                          <label>Weight</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-text-field
                            v-model="jobRequest.weight"
                            required
                            placeholder="Enter Weight"
                            type="number"
                            :rules="[v => !!v || 'Weight is required.']"
                          ></v-text-field>
                        </div>
                      </v-col>
                    </v-row>
                  </v-col>

                  <v-col cols="12" md="12" class="t-s-inner pt-0 pb-0">
                    <div class="row">
                      <div class="label-align pt-0">
                        <label>Time Slots</label>
                      </div>
                      <div class="pt-0">
                        <v-radio-group
                          v-model="selectedTimePeriod"
                          row
                          :rules="requiredRules"
                          label="Time Period"
                        >
                          <v-radio
                            v-for="(n, i) in Object.keys(serviceTimeSlotMap)"
                            :key="i"
                            :label="`${timePeriod[n]}`"
                            :value="n"
                          ></v-radio>
                        </v-radio-group>

                        <v-radio-group
                          v-model="jobRequest.time_slots_id"
                          row
                          :rules="requiredRules"
                          label="Time Slot"
                        >
                          <v-radio
                            v-for="(slot, ind) in slotsForPeriod"
                            :key="ind"
                            :label="slot.time"
                            :value="slot.id"
                          ></v-radio>
                        </v-radio-group>
                      </div>
                    </div>
                  </v-col>

                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Repeating</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-switch
                        v-model="jobRequest.is_repeating_job"
                        :label="
                          `${
                            jobRequest.is_repeating_job === true ? 'Yes' : 'No'
                          }`
                        "
                      ></v-switch>

                      <v-text-field
                        v-model="jobRequest.repeating_days"
                        required
                        type="number"
                        :rules="requiredRules"
                        v-if="jobRequest.is_repeating_job"
                        label="Repeating Days"
                      ></v-text-field>
                    </div>
                  </v-col>

                  <v-col cols="12" md="12" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Gate Number</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[v => !!v || 'Gate Number is required.']"
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
                        name="notes"
                        placeholder="Enter Notes"
                        v-model="jobRequest.notes"
                        rows="3"
                        :rules="[v => !!v || 'Notes is required.']"
                      ></v-textarea>
                    </div>
                  </v-col>

                  <v-col cols="12" md="12" class="t-s-inner pt-0 pb-0">
                    <file-pond
                      name="jobImage"
                      v-bind:allow-multiple="true"
                      ref="pond"
                      label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
                      accepted-file-types="image/jpg,image/jpeg, image/png"
                      v-bind:server="filePondServer"
                    />
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
                        v-model="jobRequest.farm_id"
                        :items="farmList"
                        placeholder="Select Farm"
                        :rules="[v => !!v || 'Farms is required.']"
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

                  <v-card-text>
                    <h3><span>$</span> {{ jobRequest.amount }}</h3>
                  </v-card-text>
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
                        v-model="jobRequest.card.name"
                        :rules="[v => !!v || 'Name on card is required.']"
                        placeholder="Name on Card"
                      ></v-text-field>
                    </div>
                    <div class="col-md-12 form-input">
                      <v-text-field
                        required
                        v-model="jobRequest.card.card_number"
                        size="20"
                        :rules="[v => !!v || 'Card Number is required.']"
                        placeholder="Card Number"
                      ></v-text-field>
                    </div>
                    <div class="col-md-4 form-input">
                      <select
                        id="exp_month"
                        data-stripe="exp_month"
                        v-model="jobRequest.card.card_exp_month"
                        name="exp_month"
                        class="form-control required"
                        required
                      >
                        <option value="">Exp. Month</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="8">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                    </div>
                    <div class="col-md-4 form-input">
                      <select
                        id="exp_year"
                        v-model="jobRequest.card.card_exp_year"
                        data-stripe="exp_year"
                        name="exp_year"
                        class="form-control required"
                        required
                      >
                        <option value="">Exp. Year</option>
                        <option v-for="year in expiryYears" v-bind:value="year">
                          {{ year }}
                        </option>
                      </select>
                    </div>
                    <div class="col-md-4 form-input">
                      <v-text-field
                        size="4"
                        required
                        v-model="jobRequest.card.cvv"
                        :rules="[v => !!v || 'CVV is required.']"
                        placeholder="CVV"
                      ></v-text-field>
                    </div>
                  </div>
                </div>

                <v-col class="pt-0 pb-0 create-job-but" cols="12" md="12">
                  <v-btn
                    type="button"
                    :loading="loading"
                    :disabled="loading"
                    color="success"
                    class="btn-full-green"
                    @click="formSubmit"
                    >Create Job</v-btn
                  >
                </v-col>

                <div class="reach-out">
                  <span><i data-feather="phone"></i></span>
                  React out to us at +91 (345)-7867-787 for any issue. We're all
                  ears!
                </div>

                <div class="info-box">
                  <span><i data-feather="info"></i></span>
                  Shift on weekdays is rush hour. There might be delay in
                  service.
                </div>
              </div>
            </div>
          </v-form>
        </div>
      </section>
    </div>
  </v-app>
</template>

<script>
import jobFormSchema from "../../../forms/jobFormSchema";
import JobService from "../../../services/JobService";
import FarmService from "../../../services/FarmService";
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
    FilePond
  },
  data() {
    return {
      valid: true,
      expiryMonths: _.range(1, 13),
      expiryYears: _.range(
        moment().format("YYYY"),
        moment()
          .add(21, "year")
          .format("YYYY")
      ),
      jobRequest: {
        farm_id: "",
        service_id: "",
        time_slots_id: "",
        job_providing_date: moment().format("YYYY-MM-DD"),
        weight: 1,
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: 0,
        card: {
          name: "",
          card_number: "",
          card_exp_month: "",
          card_exp_year: "",
          cvv: ""
        }
      },
      requiredRules: [v => !!v || "This field is required."],
      submitted: false,
      loading: false,
      selectedTimePeriod: 0,
      returnUrl: "",
      serviceList: [],
      farmList: [],
      allServices: [],
      menu2: false,
      weightShow: false,
      servicePrice: 0,
      fileContainer: [],

      timePeriod: { 1: "Morning", 2: "Afternoon", 3: "Evening" },
      serviceTimeSlotMap: { 1: [{ id: 1, time: "12AM - 12AM" }] },
      slotsForPeriod: [{ id: 1, time: "12AM - 12AM" }],
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          this.fileContainer.push(file);
          load(Date.now());
        }
      }
    };
  },
  watch: {
    "jobRequest.service_id": function(serviceId) {
      const { timeSlots, service_type, price } = _.find(this.allServices, {
        id: serviceId
      });
      /** Clear any existing slots */
      this.serviceTimeSlotMap = {};
      /** Clear any existing time durations */
      this.slotsForPeriod = [];
      /** Clear any selection of period */
      this.selectedTimePeriod = null;

      if (timeSlots !== undefined && timeSlots.length > 0) {
        timeSlots.forEach(timeSlot => {
          this.serviceTimeSlotMap[timeSlot.slot_type] = [
            ...(this.serviceTimeSlotMap[timeSlot.slot_type] || []),
            {
              id: timeSlot.id,
              time: `${timeSlot.slot_start} - ${timeSlot.slot_end}`
            }
          ];
        });
      }
      this.weightShow = service_type === 1;
      this.jobRequest.amount = price;
      this.servicePrice = price;
    },
    selectedTimePeriod: function(timePeriod) {
      this.slotsForPeriod = this.serviceTimeSlotMap[timePeriod];
    },
    "jobRequest.weight": function(weight) {
      this.jobRequest.amount = this.servicePrice * weight;
    }
  },
  created: async function() {
    this.expiryMonths = [...this.expiryMonths].map(month => {
      return month < 10 ? `0${month}` : month;
    });

    const {
      data: { data: serviceList }
    } = await JobService.listServices();

    /** Collection of all services */
    this.allServices = [...serviceList];

    /** Select Box Values */
    this.serviceList = [...serviceList].map(service => {
      return {
        text: service.service_name,
        value: service.id
      };
    });

    /** Collection of farms */
    const {
      data: { farms }
    } = await FarmService.list();
    this.farmList = [...farms].map(farm => {
      return {
        text: farm.farm_address,
        value: farm.id
      };
    });
  },
  methods: {
    formSubmit: async function() {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          var createJobRequest = new FormData();

          /**
           * Adding form values to Request
           * except of user_image
           */
          for (var key in this.jobRequest) {
            if (key !== "card") {
              createJobRequest.append(key, this.jobRequest[key]);
            } else {
              if (this.jobRequest.card !== undefined) {
                Object.keys(this.jobRequest.card).forEach(k => {
                  createJobRequest.append(
                    `card[${k}]`,
                    this.jobRequest.card[k]
                  );
                });
              }
            }
          }

          /**
           * If user image is uploaded then add it to form data
           */
          if (this.fileContainer.length > 0) {
            this.fileContainer.forEach((file, ind) => {
              createJobRequest.append(`images[${ind}]`, file, file.name);
            });
          }

          const response = await JobService.create(createJobRequest);
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false
          });

          setTimeout(() => {
            router.push({
              name: "jobsList",
              params: { farmId: this.jobRequest.farm_id }
            });
          }, 2000);
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
  }
};
</script>
