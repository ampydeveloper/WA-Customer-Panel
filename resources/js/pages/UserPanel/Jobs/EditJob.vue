<template>
  <v-app class="sign-up-form-outer">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>
                Edit<br />
                <span class="bg-custom-thickness"> Job </span>
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
                          placeholder="Select Service"
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
                                min="1970-10-03"
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
                              :rules="[(v) => !!v || 'Weight is required.']"
                            ></v-text-field>
                          </div>
                        </v-col>
                      </v-row>
                    </v-col>

                    <v-col
                      cols="12"
                      md="12"
                      class="t-s-inner pt-0 service-time-timing-outer"
                    >
                      <div class="label-align pt-0">
                        <label>Service Time</label>
                      </div>
                      <div class="pt-0 pb-0 service-time-timing-out">
                        <!-- <v-radio-group
                          v-model="selectedTimePeriod"
                          row
                          :rules="requiredRules"
                          label=""
                          color="black"
                        >
                          <v-radio
                            v-for="(n, i) in Object.keys(serviceTimeSlotMap)"
                            :key="i"
                            :label="`${timePeriod[n]}`"
                            :value="n"
                            color="black"
                          ></v-radio>
                        </v-radio-group> -->

                        <div class="pretty p-default p-round">
                          <input type="radio" name="slot_type" v-model="jobRequest.time_slots_id" value="1" />
                          <div class="state">
                            <label>Morning</label>
                          </div>
                        </div>
                        <div class="pretty p-default p-round">
                          <input type="radio" name="slot_type" v-model="jobRequest.time_slots_id" value="2" />
                          <div class="state">
                            <label>Afternoon</label>
                          </div>
                        </div>
                        <div class="pretty p-default p-round">
                          <input type="radio" name="slot_type" v-model="jobRequest.time_slots_id" value="3" />
                          <div class="state">
                            <label>Evening</label>
                          </div>
                        </div>
                      </div>
                    </v-col>

                    <v-col cols="12" md="12" class="pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label>Repeating</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <v-switch
                          color="success"
                          v-model="jobRequest.is_repeating_job"
                          :label="`${
                            jobRequest.is_repeating_job === true ? 'Yes' : 'No'
                          }`"
                        ></v-switch>

                        <v-row v-if="jobRequest.is_repeating_job">
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Monday" value="monday"></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Tuesday" value="tuesday" hide-details></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Wednesday" value="wednesday" hide-details></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Thursday" value="thursday" hide-details></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Friday" value="friday" hide-details></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="2" md="2">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Saturday" value="saturday" hide-details></v-checkbox>
                          </v-col>
                          <v-col cols="12" sm="3" md="3">
                            <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Sunday" value="sunday" hide-details></v-checkbox>
                          </v-col>
                        </v-row>

                        <!-- <v-text-field
                          v-model="jobRequest.repeating_days"
                          required
                          type="number"
                          :rules="requiredRules"
                          v-if="jobRequest.is_repeating_job"
                          label="Repeating Days"
                        ></v-text-field> -->
                      </div>
                    </v-col>

                    <v-col cols="12" md="12" class="pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label>Gate Number</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <v-text-field
                          v-model="jobRequest.gate_no"
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
                        ></v-textarea>
                      </div>
                    </v-col>

                    <!-- <v-col cols="12" md="12" class="job-p-inner pt-0 pb-0">
                      <div class="label-align pt-0">
                        <label class="label_text">Job Photos</label>
                      </div>
                      <div class="pt-0 pb-0">
                        <file-pond
                          name="jobImage"
                          v-bind:allow-multiple="true"
                          ref="pond"
                          label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>"
                          accepted-file-types="image/jpg,image/jpeg, image/png"
                          v-bind:server="filePondServer"
                        />
                      </div>
                    </v-col> -->

                    <v-col cols="12" md="12" class="pt-0 pb-0" v-if="isCustomer">
                      <div class="label-align pt-0">
                        <label>Farms</label>
                      </div>
                      <div class="pt-0 pb-0 farm-conatiner">
                        <v-select
                          v-model="jobRequest.farm_id"
                          :items="farmList"
                          placeholder="Select Farm"
                          :rules="[(v) => !!v || 'Farms is required.']"
                        ></v-select>
                        <div
                          id="farm_map"
                          class="contain"
                          style="
                            float: left;
                            width: 100%;
                            position: relative;
                            height: 300px;
                          "
                        ></div>
                      </div>
                    </v-col>
                  </div>
                </div>

                <div class="col-sm-6 content-right-outer content-right-outer-wid">
                  <!-- <div class="farm-manager">
                  <img
                    src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/avatar-s-5.99691e54.jpg"
                    alt="name"
                  />
                  <h5>Andreson Riggs</h5>
                </div> -->

                  <div class="estimate-price">
                    <h5>Price</h5>

                    <v-card-text>
                      <h3><span>$</span> {{ jobRequest.amount }}</h3>
                    </v-card-text>
                  </div>

                  <v-col class="pt-0 pb-0 create-job-but" cols="12" md="12">
                    <v-btn
                      type="button"
                      :loading="loading"
                      :disabled="loading"
                      class="btn-full-green"
                      @click="formSubmit"
                      >Save Job <i data-feather="arrow-right"></i
                    ></v-btn>
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
        <sub-footer />
      </section>
    </div>
  </v-app>
</template>

<script>
import subFooter from "../subFooter";
import jobFormSchema from "../../../forms/jobFormSchema";
import JobService from "../../../services/JobService";
import FarmService from "../../../services/FarmService";
import router from "../../../router";
import _ from "lodash";

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import mapboxgl from "mapbox-gl";

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

export default {
  components: {
    FilePond,
    subFooter
  },
  data() {
    return {
      valid: true,
      coordinates: [],
      jobRequest: {
        farm_id: "",
        service_id: "",
        time_slots_id: "",
        job_providing_date: "2020-01-01",
        weight: 1,
        gate_no: "",
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: 0
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
          console.log(file);
          this.fileContainer.push(file);
          load(Date.now());
        },
      },
    };
  },
  watch: {
    "jobRequest.service_id": function(serviceId) {
      const { timeSlots, service_type, slot_type, price } = _.find(this.allServices, {
        id: serviceId
      });
      /** Clear any existing slots */
      this.serviceTimeSlotMap = {};
      /** Clear any existing time durations */
      this.slotsForPeriod = [];
      /** Clear any selection of period */
      this.selectedTimePeriod = null;

      // if (timeSlots !== undefined && timeSlots.length > 0) {
      //   timeSlots.forEach(timeSlot => {
      //     this.serviceTimeSlotMap[timeSlot.slot_type] = [
      //       ...(this.serviceTimeSlotMap[timeSlot.slot_type] || []),
      //       {
      //         id: timeSlot.id,
      //         time: `${timeSlot.slot_start} - ${timeSlot.slot_end}`
      //       }
      //     ];
      //   });
      // }
      $(".service-time-timing-outer").show();
      $(".service-time-timing-out .pretty").hide();

      console.log(slot_type);
      // $.each(JSON.parse(slot_type), function (index, value) {
        // console.log(value);
        // $(".service-time-timing-out :input[value='" + value + "']")
          // .parent()
          // .css({display:'inline-block'});
      // });

      this.weightShow = service_type === 1;
      this.jobRequest.amount = price;
      this.servicePrice = price;
    },
    selectedTimePeriod: function(timePeriod) {
      this.slotsForPeriod = this.serviceTimeSlotMap[timePeriod];
    },
    "jobRequest.weight": function(weight) {
      this.jobRequest.amount = this.servicePrice * weight;
    },
    "jobRequest.farm_id": function (farmId) {
      const selectedFarm = _.filter(
        this.farmList,
        (farm) => farm.value === farmId
      );
      if (selectedFarm !== undefined && selectedFarm.length > 0) {
        if (this.coordinates.length == 0) {
          this.coordinates = [
            selectedFarm[0].longitude,
            selectedFarm[0].latitude,
          ];
          this.renderMap();
        } else {
          this.coordinates = [
            selectedFarm[0].longitude,
            selectedFarm[0].latitude,
          ];
          this.map.getSource("farm").setData({
            type: "Feature",
            properties: {},
            geometry: {
              type: "Point",
              coordinates: this.coordinates,
            },
          });
          this.map.flyTo({
            center: this.coordinates,
            speed: 1,
          });
        }
      }
    },
  },
  created: async function() {
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
        value: farm.id,
        latitude: farm.latitude,
        longitude: farm.longitude,
      };
    });

    const response = await JobService.get(this.$route.params.jobId);
    const job = response.data.data;

    this.jobRequest = {
      ...this.jobRequest,
      ...{
        farm_id: job.farm_id,
        service_id: job.service_id,
        notes: job.notes,
        amount: job.amount,
        weight: job.weight,
        gate_no: job.gate_no,
        job_providing_date: job.job_providing_date,
        is_repeating_job: job.is_repeating_job,
        repeating_days: job.repeating_days != null ? JSON.parse(job.repeating_days) : job.repeating_days,
        time_slots_id: job.time_slots_id
      }
    };
    this.selectedTimePeriod = 1;
  },
  methods: {
    formSubmit: async function() {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          const response = await JobService.update(
            this.$route.params.jobId,
            this.jobRequest
          );
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false
          });
          setTimeout(() => {
            router.push({ name: "jobsList" });
          }, 3000);
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false
          });
        }
      }
    },
    renderMap: function () {
      mapboxgl.accessToken =
        "pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA";
      var $this = this;
      this.map = new mapboxgl.Map({
        container: "farm_map",
        style: "mapbox://styles/mapbox/light-v9",
        center: $this.coordinates, // starting position
        zoom: 12,
      });
      this.map.on("load", function () {
        $this.map.addSource("farm", {
          type: "geojson",
          data: {
            type: "FeatureCollection",
            features: [
              {
                type: "Feature",
                properties: {},
                geometry: {
                  type: "Point",
                  coordinates: $this.coordinates,
                },
              },
            ],
          },
        });
        // Add starting point to the map
        $this.map.addLayer({
          id: "farm",
          type: "symbol",
          source: "farm",
          layout: {
            "icon-image": "town-hall-15",
          },
        });
      });
    }
  }
};
</script>
