<template>
  <v-app class="sign-up-form-outer">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2>
                Schedule a<br />
                <span class="bg-custom-thickness"> Pickup </span>
              </h2>
            </div>
            <div class="col-md-6">
              <div class="desc-details pickup-desc-details">
                 <h2>
                   Select a <span class="bg-custom-thickness">Service</span>, 
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
                              :min='tomorrowDate'
                            >
                            </v-date-picker>
                          </v-menu>
                        </div>
                      </v-col>
                      <v-col cols="6" md="6" class="pt-0 pb-0" v-if='isHauler || isHaulerDriver'>
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
                                :rules="[(v) => !!v || 'Time is required.']"
                              ></v-text-field>
                            </template>
                            <v-time-picker
                              v-if="timeMenu"
                              v-model="jobRequest.job_providing_time"
                              full-width
                              format="24hr"
                              @click:minute="$refs.time.save(jobRequest.job_providing_time)"
                            ></v-time-picker>
                          </v-menu>
                        </div>
                      </v-col>
                      <v-col
                        cols="6"
                        md="6"
                        class="pt-0 pb-0"
                        v-if='weightShow || isHauler || isHaulerDriver'
                      >
                        <div class="label-align pt-0">
                          <label>Weight</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-select 
                            required
                            v-model="jobRequest.weight" 
                            :items=[5,10,15,20,25] 
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
                    class="t-s-inner pt-0"
                  >
                    <v-row>
                      <v-col
                          cols="12"
                          md="12"
                          class="pt-0 pb-0 service-time-timing-outer"
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
                          jobRequest.is_repeating_job === true ? 'Yes' : 'No'
                        }`"
                      ></v-switch>

                      <v-row v-if="jobRequest.is_repeating_job" class="mb-3">
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Monday" value="monday"></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Tuesday" value="tuesday" hide-details></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Wednesday" value="wednesday" hide-details></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Thursday" value="thursday" hide-details></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Friday" value="friday" hide-details></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Saturday" value="saturday" hide-details></v-checkbox>
                        </v-col>
                        <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                          <v-checkbox color="success" v-model="jobRequest.repeating_days" label="Sunday" value="sunday" hide-details></v-checkbox>
                        </v-col>
                      </v-row>
                    </div>
                  </v-col>

                  <v-col cols="12" md="12" class="pt-0 pb-0" v-if='isCustomer || isManager'>
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
                      <label class="label_text">Important Photos</label>
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
                  </v-col>

                  <v-col cols="6" md="6" v-if="isCustomer">
                    <div class="label-align pt-0">
                      <label>Farms</label>
                    </div>
                    <div class="pt-0 pb-0 farm-conatiner">
                      <v-select
                        ref="farmSelect"
                        v-model="jobRequest.farm_id"
                        :items="farmList"
                        placeholder="Select Farm"
                        required
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
                          required
                          :rules="[(v) => !!v || 'Manager is required.']"
                        ></v-select>
                      </div>
                    </v-col>
                    <v-col cols="12" md="12" class="pt-0 pb-0" v-if="isCustomer || isManager">
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
                  Important administrative messages from Wellington Agricultural Services.
                </div>

                <div class="estimate-price">
                  <h5>Estimated Price</h5>

                  <v-card-text>
                    <h3><span>$</span> {{ jobRequest.amount }}</h3>
                  </v-card-text>
                </div>

                <div class="send-payment" v-if="isHauler">
                  <h5 class="heading2">Payment Mode</h5>
                  <div class="add-new-card" v-if='isHauler'>
                    <v-radio-group
                      v-model="jobRequest.payment_mode"
                      row
                      :rules="[(v) => !!v || 'Payment mode is required.']"
                    >
                      <v-radio
                        label="Cash"
                        color="success"
                        :value=1
                      ></v-radio>
                      <v-radio
                        label="Cheque"
                        color="success"
                        :value=2
                      ></v-radio>
                    </v-radio-group>
                  </div>
                </div>

                <div class="send-payment" v-if="isCustomer || isManager">
                  <h5 class="heading2">Payment Options</h5>
                  <!-- <v-radio-group
                    v-model="jobRequest.attach_card"
                    row
                    label="Use card for payment"
                  >
                    <v-radio label="Yes" :value="1"></v-radio>
                    <v-radio label="No" :value="0"></v-radio>
                  </v-radio-group> -->

                  <table
                    class="table payment-info-table simple-table-out"
                    v-if="!addNewCard"
                  >
                    <thead>
                      <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Card Number</th>
                        <th>Card Expiry</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(card, index) in cardList" :key="card.id">
                        <td>
                          <div class="pretty p-default p-round">
                            <!-- {{card.card_primary == 1 || index == 0 ? "checked":""}} -->
                            <input type="radio" name="radio1" v-model="jobRequest.card_id" v-bind:key='"cardRadio"+card.id' :value="card.id"/>
                            <div class="state">
                              <label></label>
                            </div>
                          </div>
                        </td>
                        <td>{{ card.name }}</td>
                        <td>**** **** **** {{ card.last_four }}</td>
                        <td>
                          {{ card.card_exp_month }} / {{ card.card_exp_year }}
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="add-n-c-outer clearfix">
                    <a
                      href="javascript:void(0);"
                      @click="showAddCard"
                      id="add-card-link"
                      class="btn-full-green btn-outline-green float-right"
                      v-if="!addNewCard"
                      >Add New Card <i data-feather="arrow-right"></i
                    ></a>
                  </div>

                  <div class="send-payment-form" v-if="addNewCard">
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
                      <div class="col-md-12 form-input pt-0 pb-0">
                        <div class="label-align pt-0">
                          <label>Name on card</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-text-field
                            v-model="jobRequest.card.name"
                            :rules="[(v) => !!v || 'Name on card is required.']"
                            placeholder="Enter Name on Card"
                            required
                          ></v-text-field>
                        </div>
                      </div>
                      <div class="col-md-12 form-input pt-0 pb-0">
                        <div class="label-align pt-0">
                          <label>Card Number</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-text-field
                            v-model="jobRequest.card.card_number"
                            size="20"
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
                      </div>
                      <div class="col-md-4 form-input pt-0 pb-0">
                        <div class="label-align pt-0">
                          <label>Expiry Month</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-select
                            v-model="jobRequest.card.card_exp_month"
                            data-stripe="exp_month"
                            id="exp_month"
                            :items="expiryMonths"
                            required
                            placeholder="Select Expiry Month"
                            :rules="[(v) => !!v || 'Expiry Month is required.']"
                          ></v-select>
                        </div>
                      </div>
                      <div class="col-md-4 form-input pt-0 pb-0">
                        <div class="label-align pt-0">
                          <label>Expiry Year</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-select
                            id="exp_year"
                            v-model="jobRequest.card.card_exp_year"
                            data-stripe="exp_year"
                            :items="expiryYears"
                            placeholder="Select Expiry Year"
                            required
                            :rules="[(v) => !!v || 'Expiry Year is required.']"
                          ></v-select>
                        </div>
                      </div>
                      <div class="col-md-4 form-input pt-0 pb-0">
                        <div class="label-align pt-0">
                          <label>CVV</label>
                        </div>
                        <div class="pt-0 pb-0">
                          <v-text-field
                            v-model="jobRequest.card.cvv"
                            type="password"
                            size="4"
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
                    </div>
                  </div>

                  <div class="add-n-c-outer clearfix" v-if='addNewCard'>
                    <v-btn
                      class="btn-outline-red float-right"
                      style="margin-top:17px;"
                      @click="addNewCard = false"
                      ><i data-feather="x"></i> Cancel</v-btn
                    >
                  </div>
                </div>

                <v-col class="pt-0 pb-0 create-job-but" cols="12" md="12">
                  <v-btn
                    type="button"
                    :loading="loading"
                    :disabled="loading"
                    class="btn-full-green"
                    @click="formSubmit"
                    >Create Job <i data-feather="arrow-right"></i
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
import moment from "moment";
import CardService from "../../../services/CardService";

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
      tomorrowDate: moment().add(1, 'days').format("YYYY-MM-DD"),
      map: null,
      coordinates: [],
      cardList: [],
      addNewCard: false,
      valid: true,
      expiryMonths: _.range(1, 13),
      expiryYears: _.range(
        moment().format("YYYY"),
        moment().add(21, "year").format("YYYY")
      ),
      jobRequest: {
        farm_id: "",
        card_id: null,
        service_id: "",
        job_providing_date: moment().add(1, 'days').format("YYYY-MM-DD"),
        job_providing_time: null,
        time_slots_id: "",
        weight: 5,
        gate_no: "",
        manager_id: null,
        payment_mode: null,
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: [],
        attach_card: 0,
        card: {
          name: "",
          card_number: "",
          card_exp_month: "",
          card_exp_year: "",
          cvv: "",
        },
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
      menu2: false,
      timeMenu: false,
      weightShow: false,
      slotTypes: [],
      servicePrice: 0,
      fileContainer: [],
      timePeriod: { 1: "Morning", 2: "Afternoon", 3: "Evening" },
      serviceTimeSlotMap: {},
      slotsForPeriod: [],
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

      const { timeSlots, service_type, slot_type, price,  overhead_cost } = _.find(
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
    },
    selectedTimePeriod: function (timePeriod) {
      this.slotsForPeriod = this.serviceTimeSlotMap[timePeriod];
    },
    "jobRequest.weight": function (weight) {
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
        let self = this;
        if(this.isCustomer){
          FarmService.listManagers(farmId).then(function(managers){
            managers = managers.data.data;
            if(managers != undefined && managers.length > 0){
              self.managerList = [...managers].map(manager => {
                return {
                  text: manager.full_name,
                  value: manager.id
                };
              });
            }
          });
        }
      }
    },
    "jobRequest.card.card_number": function (cardNum) {
      this.jobRequest.attach_card = 1;
    }
  },
  created: async function () {
    const user = JSON.parse(window.localStorage.getItem('user'));
    if(user.role_id == 4){
      this.jobRequest.payment_mode = user.payment_mode;
    }

    this.expiryMonths = [...this.expiryMonths].map((month) => {
      return month < 10 ? `0${month}` : month;
    });

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

    /** Collection of farms */
    if(user.role_id == 4 || user.role_id == 5){
      try {
        const {
          data: { farms },
        } = await FarmService.list();
        if(farms){
          this.farmList = [...farms].map((farm, i) => {
            if(i==0){
              this.jobRequest.farm_id=farm.id;
            }
            return {
              text: farm.farm_address,
              value: farm.id,
              latitude: farm.latitude,
              longitude: farm.longitude,
            };
          });
        } 
      } catch (error) {
        // No Farms
      }
    }

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
      CardService.list().then((response) => {
        this.cardList = response.data.data;
        if(this.cardList.length > 0){
          [...this.cardList].forEach((card, ind) => {
            if(ind == 0 || card.card_primary == 1){ this.jobRequest.card_id = card.id; }
          });
        }
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

    $(document).ready(function() {
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
                Object.keys(this.jobRequest.card).forEach((k) => {
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
            dismissible: false,
          });

          setTimeout(() => {
            this.loading = false;
            router.push({
              name: "ViewJob",
              params: { jobId: response.data.job_id },
            });
          }, 2000);
        } catch (error) {
          this.loading = false;
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false,
          });
        }
      }
    },
    showAddCard: function () {
      this.addNewCard = true;
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
  },
};
</script>