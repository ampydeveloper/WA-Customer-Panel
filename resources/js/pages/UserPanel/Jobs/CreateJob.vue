<template>
  <v-app class="sign-up-form-outer">
    <div class="sign-up-form-inner">
      <div class="row">
        <div class="col-md-12">
          <div class="new-user-signup">
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              class="slide-right"
              autocomplete="off"
            >
              <h1>Start Job</h1>
              <div class="form-group">
                <v-select
                  v-model="jobRequest.farm_id"
                  :items="farmList"
                  label="Select Farm"
                  :rules="requiredRules"
                ></v-select>

                <v-select
                  v-model="jobRequest.service_id"
                  :items="serviceList"
                  label="Select Service"
                  :rules="requiredRules"
                ></v-select>

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
                      label="Date"
                      placeholder
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="jobRequest.job_providing_date"
                    @input="menu2 = false"
                  ></v-date-picker>
                </v-menu>

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

                <v-switch
                  v-model="jobRequest.is_repeating_job"
                  :label="
                    `${
                      jobRequest.is_repeating_job === true
                        ? 'Repeating Job'
                        : 'Not Repeating Job'
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

                <v-text-field
                  v-model="jobRequest.weight"
                  required
                  type="number"
                  :rules="requiredRules"
                  v-if="weightShow"
                  label="Weight - in Ton(s)"
                ></v-text-field>

                <v-textarea
                  name="notes"
                  label="Notes"
                  v-model="jobRequest.notes"
                  value=""
                  :rules="requiredRules"
                ></v-textarea>

                <v-card max-width="344">
                  <v-card-text>
                    <div>Total Amount</div>
                    <p class="display-1 text--primary">
                      ${{ jobRequest.amount }}
                    </p>
                  </v-card-text>
                </v-card>

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
            </v-form>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import jobFormSchema from "../../../forms/jobFormSchema";
import JobService from "../../../services/JobService";
import FarmService from "../../../services/FarmService";
import router from "../../../router";
import _ from "lodash";

export default {
  data() {
    return {
      valid: true,
      jobRequest: {
        farm_id: "",
        service_id: "",
        time_slots_id: "",
        job_providing_date: "2020-01-01",
        weight: 1,
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

      timePeriod: { 1: "Morning", 2: "Afternoon", 3: "Evening" },
      serviceTimeSlotMap: { 1: [{ id: 1, time: "12AM - 12AM" }] },
      slotsForPeriod: [{ id: 1, time: "12AM - 12AM" }]
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
          const response = await JobService.create(this.jobRequest);
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
    }
  }
};
</script>
