<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Job Dashboard</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="job-dashboard-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <table
                id="all-jobs-table"
                class="table basic-table"
              >
                <thead>
                  <tr>
                    <th class="job-summ">Job Summary</th>
                    <th>Manager / Farm Location</th>
                    <th class="time-col">Date / Est. Time</th>
                    <th>Quantity</th>
                    <th>Payment Status</th>
                    <th>Job Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(job, index) in alljobs">
                    <td>
                      <span class="basic-big">#JOB100{{ job.id }}</span>
                      <span class="basic-info"
                        >${{ job.job_amount ? job.job_amount : 0 }}</span
                      >
                      <span class="basic-info">{{
                        job.service.service_name
                      }}</span>
                    </td>
                    <td>
                      <span class="basic-big">{{
                        job.customer.first_name
                      }}</span>
                      <span class="basic-info" v-if="job.manager"
                        >{{ job.manager.first_name }}</span
                      >
                      <span class="basic-info"
                        >{{ job.farm.farm_address }} {{ job.farm.farm_city }}
                        {{ job.farm.farm_province }} 
                        {{ job.farm.farm_zipcode }}</span
                      >
                    </td>
                    <td class="job-col-body">
                      <span class="basic-info">{{
                        job.job_providing_date
                      }}</span>
                    </td>
                    <td>30 Ton</td>
                    <td>
                      <template v-if="job.payment_status">
                        <span class="badge-tag">Paid</span>
                      </template>
                      <template v-if="!job.payment_status">
                        <span class="badge-tag">Unpaid</span>
                      </template>
                    </td>
                    <td>
                      <template v-if="job.job_status == 1">
                        <span class="badge-tag">Assigned</span>
                      </template>
                      <template v-if="job.job_status == 2">
                        <span class="badge-tag">Completed</span>
                      </template>
                      <template v-if="job.job_status == 3">
                        <span class="badge-tag">Closed</span>
                      </template>
                      <template v-if="job.job_status == 4">
                        <span class="badge-tag">Canceled</span>
                      </template>                     
                    </td>
                    <td>
                      <a class="btn btn-table-outline" v-if="job.job_status == 0" @click="cancelJob(job.id)">
                       Cancel
                      </a>
                      <a class="btn btn-table-outline">
                        View Details
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
    <span id="table-chevron-left" class="d-none">
      <i data-feather="chevron-left"></i>
    </span>
    <span id="table-chevron-right" class="d-none">
      <i data-feather="chevron-right"></i>
    </span>
    <span id="search-input-icon" class="d-none">
      <span class="search-input-outer">
        <i data-feather="search"></i>
      </span>
    </span>
  </div>
</template>

<script>
import AppSmallHeader from "../../../shared/components/AppSmallHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";
import JobService from "../../../services/JobService";

export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
  },
  data: () => ({
    dialog: false,
    jobList: {}, 
    alljobs: {
      0: {
        amount: 129600,
        customer: {
          id: 111,
          prefix: "Mr.",
          first_name: "Elijah",
          last_name: "Joffrion",
          email: "Elijah@yopmail.com",
        },
        farm: {
          farm_address: "Wellington",
          farm_city: "Te Aro",
          farm_province: "Wellington",
          farm_unit: "5/26 Marion Street",
          farm_zipcode: "62365",
        },
        farm_id: 17,
        gate_no: "56",
        job_created_by: 1,
        job_providing_date: "2020-09-24",
        job_status: 0,
        manager: {
          prefix: "Mr.",
          first_name: "Lucas",
          last_name: "Demeter",
          email: "Lucas@yopmail.com",
        },
        manager_id: 112,
        payment_notes: null,
        payment_status: 0,
        service: {
          service_name: "STANDARD LOAD",
        },
        skidsteer: null,
        skidsteer_driver: null,
        skidsteer_driver_id: null,
        skidsteer_id: null,
        start_time: null,
        truck: null,
        truck_driver: null,
      },
    },
  }),

  created() {
    const { name: routeName } = this.$route;
    JobService[routeName === "JobsDashboard" ? "myJobs" : "myUpcomingJobs"](
      this.$route.params.farmId
    ).then((response) => {
      this.alljobs = response.data.data;
      console.log(this.alljobs);
    });
  },

  methods: {
    cancelJob: async function (jobId) {
      try {
        const response = await JobService.cancel(jobId);
        this.$toast.open({
          message: response.data.message,
          type: "success",
          position: "top-right",
          dismissible: false,
        });
        const jobIndex = this.alljobs.findIndex((job) => job.id === managerId);
        this.alljobs.splice(jobIndex, 1);
      } catch (error) {
        this.$toast.open({
          message: error.response.data.message,
          type: "error",
          position: "bottom-right",
          dismissible: false,
        });
      }
    },
  },
  
};
</script>