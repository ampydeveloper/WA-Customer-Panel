<template>
  <v-app class="sign-up-form-outer">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
                <h2>
                Update a<br />
                <span class="bg-custom-thickness"> Pickup </span>
              </h2>
            </div>
              <div class="col-md-6">
              <div class="desc-details pickup-desc-details">
                 <h2>
                   Update <span class="bg-custom-thickness">Service</span>, 
                     <span class="bg-custom-thickness">Date</span>,  
                       <span class="bg-custom-thickness">Weight</span> & schedule a pickup.
                </h2>
              </div>
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
                  <v-col cols="12" md="12" v-if="isHauler">
                    <div class="label-align pt-0">
                      <label>Driver</label>
                    </div>
                    <div class="pt-0 pb-0 farm-conatiner">
                      <v-select
                        v-model="jobRequest.manager_id"
                        :items="driverList"
                        placeholder="Select Driver"
                        :rules="[(v) => !!v || 'Driver is required.']"
                      ></v-select>
                    </div>
                  </v-col>
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
                              :min="jobRequest.job_providing_date"
                            ></v-date-picker>
                          </v-menu>
                        </div>
                      </v-col>
                      <v-col
                        cols="6"
                        md="6"
                        class="pt-0 pb-0"
                        v-if="isHauler || isHaulerDriver"
                      >
                        <div class="label-align pt-0">
                          <label>Time</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-menu
                            ref="time"
                            v-model="timeMenu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            :return-value.sync="jobRequest.job_providing_time"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="290px"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                v-model="jobRequest.job_providing_time"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                :rules="[(v) => !!v || 'Farms is required.']"
                              ></v-text-field>
                            </template>
                            <v-time-picker
                              v-if="timeMenu"
                              v-model="jobRequest.job_providing_time"
                              full-width
                              format="24hr"
                              @click:minute="
                                $refs.time.save(jobRequest.job_providing_time)
                              "
                            ></v-time-picker>
                          </v-menu>
                        </div>
                      </v-col>

                      <v-col
                        cols="6"
                        md="6"
                        class="pt-0 pb-0"
                        v-if="weightShow || isHauler || isHaulerDriver"
                      >
                        <div class="label-align pt-0">
                          <label>Weight</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-select
                            required
                            v-model="jobRequest.weight"
                            :items="[5, 10, 15, 20, 25]"
                            :rules="[(v) => !!v || 'Weight is required.']"
                            hint="Tons"
                            persistent-hint
                          ></v-select>
                        </div>
                      </v-col>
                    </v-row>
                  </v-col>

                  <v-col
                    cols="12"
                    md="12"
                    class="t-s-inner pt-0 service-time-timing-outer"
                  >
                    <v-row>
                      <v-col
                        cols="8"
                        md="8"
                        class="pt-0 pb-0"
                      >
                        <div class="label-align pt-0" v-if='slotTypes.length > 0'>
                          <label>Service Time</label>
                        </div>
                        <div class="pt-0 pb-0 service-time-timing-out">
                          <div class="pretty p-default p-round">
                            <input
                              type="radio"
                              name="slot_type"
                              v-model="jobRequest.time_slots_id"
                              value="1"
                            />
                            <div class="state">
                              <label>Morning</label>
                            </div>
                          </div>
                          <div class="pretty p-default p-round">
                            <input
                              type="radio"
                              name="slot_type"
                              v-model="jobRequest.time_slots_id"
                              value="2"
                            />
                            <div class="state">
                              <label>Afternoon</label>
                            </div>
                          </div>
                          <div class="pretty p-default p-round">
                            <input
                              type="radio"
                              name="slot_type"
                              v-model="jobRequest.time_slots_id"
                              value="3"
                            />
                            <div class="state">
                              <label>Evening</label>
                            </div>
                          </div>
                        </div>
                      </v-col>
                    </v-row>
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
                          jobRequest.is_repeating_job == 2 ? 'Yes' : 'No'
                        }`"
                      ></v-switch>

                      <v-row v-if="jobRequest.is_repeating_job">
                        <v-col
                          cols="12"
                          sm="4"
                          md="4"
                          class="pt-0 pb-0"
                          style="height: 29px"
                        >
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Monday"
                            value="monday"
                          ></v-checkbox>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="4"
                          md="4"
                          class="pt-0 pb-0"
                          style="height: 29px"
                        >
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Tuesday"
                            value="tuesday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="4"
                          md="4"
                          class="pt-0 pb-0"
                          style="height: 29px"
                        >
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Wednesday"
                            value="wednesday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Thursday"
                            value="thursday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Friday"
                            value="friday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Saturday"
                            value="saturday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox
                            color="success"
                            v-model="jobRequest.repeating_days"
                            label="Sunday"
                            value="sunday"
                            hide-details
                          ></v-checkbox>
                        </v-col>
                      </v-row>
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

                  <v-col cols="12" md="12" class="job-p-inner pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label class="label_text">Job Photos</label>
                    </div>
                    <v-row
                      v-if="
                        jobRequest.existingImages != null &&
                        jobRequest.existingImages.length > 0
                      "
                    >
                      <v-col
                        v-for="(src, n) in jobRequest.existingImages"
                        :key="n"
                        class="d-flex child-flex"
                        cols="2"
                      >
                        <v-img
                          max-height="150"
                          max-width="150"
                          :src="
                            src.replace('/storage/', '/storage/user_images/')
                          "
                          aspect-ratio="1"
                          class="grey lighten-2"
                        >
                          <template v-slot:default>
                            <v-row
                              class="fill-height ma-0"
                              align="start"
                              justify="end"
                            >
                              <v-btn
                                @click="removeExisting(n)"
                                icon
                                color="error"
                                small
                                ><svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="24"
                                  height="24"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="feather feather-x-circle"
                                >
                                  <circle cx="12" cy="12" r="10"></circle>
                                  <line x1="15" y1="9" x2="9" y2="15"></line>
                                  <line
                                    x1="9"
                                    y1="9"
                                    x2="15"
                                    y2="15"
                                  ></line></svg
                              ></v-btn>
                            </v-row>
                          </template>
                          <template v-slot:placeholder>
                            <v-row
                              class="fill-height ma-0"
                              align="center"
                              justify="center"
                            >
                              <v-progress-circular
                                indeterminate
                                color="grey lighten-5"
                              ></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                      </v-col>
                    </v-row>
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
                  </v-col>

                  <v-col cols="6" md="6" v-if="isCustomer">
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
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" v-if="isCustomer">
                    <div class="label-align pt-0">
                      <label>Manager</label>
                    </div>
                    <div class="pt-0 pb-0 farm-conatiner">
                      <v-select
                        v-model="jobRequest.manager_id"
                        :items="managerList"
                        placeholder="Select Manager"
                        :rules="[(v) => !!v || 'Manager is required.']"
                      ></v-select>
                    </div>
                  </v-col>
                  <v-col
                    cols="12"
                    md="12"
                    class="pt-0 pb-0"
                    v-if="isCustomer || isManager"
                  >
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
                  </v-col>
                </div>
              </div>

              <div class="col-sm-6 content-right-outer content-right-outer-wid">
                <div class="reach-out">
                  <span><i data-feather="phone"></i></span>
                  React out to us at +91 (561) 790-2347 for any issue. We're all
                  ears!
                </div>

                <div class="info-box">
                  <span><i data-feather="info"></i></span>
                  Important administrative messages from Wellington Agricultural
                  Services.
                </div>

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
import DriverService from '../../../services/DriverService';
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
    subFooter,
  },
  data() {
    return {
      valid: true,
      coordinates: [],
      jobRequest: {
        farm_id: "",
        manager_id: "",
        service_id: "",
        payment_mode: null,
        time_slots_id: "",
        job_providing_date: "2020-01-01",
        job_providing_time: null,
        weight: 1,
        gate_no: "",
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: 0,
        existingImages: null,
      },
      requiredRules: [(v) => !!v || "This field is required."],
      submitted: false,
      loading: false,
      selectedTimePeriod: 0,
      returnUrl: "",
      serviceList: [],
      farmList: [],
      allServices: [],
      managerList: [],
      driverList: [],
      slotTypes: [],
      menu2: false,
      timeMenu: false,
      weightShow: false,
      overhead_cost: 0,
      servicePrice: 0,
      fileContainer: [],
      timePeriod: { 1: "Morning", 2: "Afternoon", 3: "Evening" },
      serviceTimeSlotMap: { 1: [{ id: 1, time: "12AM - 12AM" }] },
      slotsForPeriod: [{ id: 1, time: "12AM - 12AM" }],
      filePondServer: {
        process: (fieldName, file, metadata, load) => {
          this.fileContainer.push(file);
          load(Date.now());
        },
      },
    };
  },
  watch: {
    "jobRequest.service_id": function (serviceId) {
      const { timeSlots, service_type, slot_type, price, overhead_cost } = _.find(
        this.allServices,
        {
          id: serviceId,
        }
      );
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

      $.each(slot_type, function (index, value) {
        $(".service-time-timing-out :input[value='" + value + "']")
        .parent()
        .css({display:'inline-block'});
      });

      if(slot_type != null){ this.slotTypes = slot_type; }
      this.weightShow = service_type === 1;
      this.jobRequest.amount = parseInt(price) + parseInt(overhead_cost);
      this.servicePrice = price;
      this.overhead_cost = parseInt(overhead_cost);
      if(this.weightShow) this.jobRequest.amount *= parseInt(this.jobRequest.weight); 
    },
    selectedTimePeriod: function (timePeriod) {
      this.slotsForPeriod = this.serviceTimeSlotMap[timePeriod];
    },
    "jobRequest.weight": function (weight) {
      this.jobRequest.amount = (this.servicePrice + this.overhead_cost) * weight;
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
        let self = this;
        if(this.isCustomer){
          FarmService.listManagers(farmId).then(function (managers) {
            managers = managers.data.data;
            if (managers != undefined && managers.length > 0) {
              self.managerList = [...managers].map((manager) => {
                return {
                  text: manager.full_name,
                  value: manager.id,
                };
              });
            }
          });
        }
      }
    },
  },
  created: async function () {
    const user = JSON.parse(window.localStorage.getItem('user'));

    if(user.role_id == 4){
      this.jobRequest.payment_mode = user.payment_mode;
    }

    const {
      data: { data: serviceList },
    } = await JobService.listServices();

    /** Collection of all services */
    this.allServices = [...serviceList];

    /** Select Box Values */
    this.serviceList = [...serviceList].map((service) => {
      return {
        text: service.service_name,
        value: service.id,
      };
    });

    /** Collection of drivers */
    if(user.role_id == 6){
      DriverService.list().then((response) => {
        this.driverList = [...response.data.data].map((driver) => {
          return {
            text: driver.full_name,
            value: driver.id
          };
        });
      });
    }

    if(user.role_id == 4 || user.role_id == 5){
      /** Collection of farms */
      const {
        data: { farms },
      } = await FarmService.list();
      this.farmList = [...farms].map((farm) => {
        return {
          text: farm.farm_address,
          value: farm.id,
          latitude: farm.latitude,
          longitude: farm.longitude,
        };
      });
    }

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
        manager_id: job.manager_id,
        payment_mode: job.payment_mode,
        job_providing_date: job.job_providing_date,
        job_providing_time: job.job_providing_time,
        is_repeating_job: job.is_repeating_job == 1 ? false : true,
        repeating_days:
          job.repeating_days != null
            ? JSON.parse(job.repeating_days)
            : [],
        time_slots_id: job.time_slots_id,
        existingImages:
          job.images != null ? JSON.parse(job.images) : job.images,
      },
    };
    this.selectedTimePeriod = 1;

    $(document).ready(function () {
      feather.replace();
    });
  },
  methods: {
    formSubmit: async function () {
      const isValidated = this.$refs.form.validate();
      if(this.slotTypes.length > 0 && this.jobRequest.time_slots_id.length == 0){
        this.$toast.open({
          message: 'Service Time Required!',
          type: "error",
          position: "bottom-right",
          dismissible: false,
        });
        return false;
      }
      if (isValidated === true) {
        try {
          this.loading = true;
          var editJobRequest = new FormData();

          /**
           * Adding form values to Request
           * except of user_image
           */

          for (var key in this.jobRequest) {
            let val = this.jobRequest[key];
            if (key == "repeating_days") {
              val = this.jobRequest[key].join(",");
            }
            editJobRequest.append(key, this.jobRequest[key]);
          }
          /**
           * If user image is uploaded then add it to form data
           */
          if (this.fileContainer.length > 0) {
            this.fileContainer.forEach((file, ind) => {
              editJobRequest.append(`images[${ind}]`, file, file.name);
            });
          }
          const response = await JobService.update(
            this.$route.params.jobId,
            editJobRequest
          );
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false,
          });
          this.loading = false;
          setTimeout(() => {
            router.push({ name: "JobsDashboard" });
          }, 3000);
        } catch (error) {
          this.loading = false;
          this.$toast.open({
            message: 'Pickup cannot be saved due to an error, please re-check data or contact support!',
            type: "error",
            position: "bottom-right",
            dismissible: false,
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
    },
    removeExisting(n) {
      this.jobRequest.existingImages.splice(
        this.jobRequest.existingImages.indexOf(n),
        1
      );
    },
  },
};
</script>
