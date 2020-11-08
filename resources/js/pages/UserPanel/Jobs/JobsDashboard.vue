<template>
  <div class="loc-page logged-in-page">
    <!-- <app-small-header /> -->
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
              <a :href='(routeName === "JobsDashboard" ? "/jobs" : "/jobs/upcoming")' class="btn btn-table-outline" v-text='routeName === "JobsDashboard" ? "All Jobs" : "Upcoming Jobs"'></a>
              <table
                id="all-jobs-table"
                class="table basic-table"
              >
                <thead>
                  <tr>
                    <th class="job-summ">Job Summary</th>
                    <th>Manager / Farm Location</th>
                    <th class="time-col">Date / Est. Time</th>
                    <th>Weight</th>
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
                        >${{ job.amount ? job.amount : 0 }}</span
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
                      <span class="basic-info" v-if="job.farm">
                        {{ job.farm.farm_address }} {{ job.farm.farm_city }}
                        {{ job.farm.farm_province }} 
                        {{ job.farm.farm_zipcode }}</span
                      >
                      <span class="basic-info" v-if="!job.farm">N/A</span>
                    </td>
                    <td class="job-col-body">
                      <span class="basic-info">{{
                        job.job_providing_date
                      }}</span>
                    </td>
                    <td v-if="job.weight">{{ job.weight }} Ton</td>
                    <td v-if="!job.weight">N/A</td>
                    <td>
                      <template v-if="job.payment_status">
                        <span class="badge-tag">Paid</span>
                      </template>
                      <template v-if="!job.payment_status">
                        <span class="badge-tag">Unpaid</span>
                      </template>
                    </td>
                    <td>
                     <template>
                        <span class="badge-tag">{{ job.job_status_name }}</span>
                      </template>                  
                    </td>
                    <td>
                      <router-link v-if="job.job_status == 0" :to="{ name: 'editJob', params: { jobId: job.id }}" class="btn btn-table-outline">Edit</router-link>
                      <a class="btn btn-table-outline" v-if="job.job_status == 0" @click="cancelJob(job.id)">
                       Cancel
                      </a>
                      <router-link v-if="job.job_status == 1" :to="{ name: 'ViewJob', params: { jobId: job.id }}" class="btn btn-table-outline"> Track</router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

         <sub-footer />
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
import subFooter from "../subFooter";
import AppSmallHeader from "../../../shared/components/AppSmallHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";
import JobService from "../../../services/JobService";

export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
      subFooter,
  },
  data: () => ({
    dialog: false,
    routeName: 'myJobs',
    jobList: {}, 
    alljobs: {
    },
  }),

  created() {
    const { name: routeName } = this.$route;
    this.routeName = (routeName === "JobsDashboard" ? "upcomingJobsDashboard" : "JobsDashboard");
    let getRouteName = (routeName === "JobsDashboard" ? "myJobs" : "myUpcomingJobs");
    if(typeof(this.$route.params.farmId) !== "undefined"){
      this.routeName = (routeName === "FarmJobsDashboard" ? "upcomingJobsDashboard" : "JobsDashboard");
      getRouteName = (routeName === "FarmJobsDashboard" ? "list" : "upcomingJobsList");
    }
    JobService[getRouteName](
      this.$route.params.farmId
    ).then((response) => {
      this.alljobs = response.data.data;
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
        const { name: routeName } = this.$route;
        let getRouteName = (routeName === "JobsDashboard" ? "myJobs" : "myUpcomingJobs");
        if(this.$route.params.farmId === undefined){
            getRouteName = (routeName === "JobsDashboard" ? "list" : "upcomingJobsList");
        }
        JobService[getRouteName](
          this.$route.params.farmId
        ).then((response) => {
          this.alljobs = response.data.data;
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
  },
  
};
</script>