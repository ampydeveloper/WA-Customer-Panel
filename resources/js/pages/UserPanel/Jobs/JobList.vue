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
              <table id="all-jobs-table" class="table basic-table">
                <thead>
                  <tr>
                    <th class="job-summ">Job Summary</th>
                    <th>Manager / Farm Location</th>

                    <th class="time-col">Date / Est. Time</th>
                    <th>Quantity</th>
                    <th>Payment Status</th>

                    <th>Job Status1</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="job in jobList" :key="job.id">
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
                      <span class="basic-info">{{
                        job.manager.first_name
                      }}</span>
                      <span class="basic-info"
                        >{{ job.farm.farm_address }} {{ job.farm.farm_city }}
                        {{ job.farm.farm_province }}
                        {{ job.farm.farm_zipcode }}</span
                      >
                    </td>

                    <td class="job-col-body">
                      <span class="basic-info"
                        >{{ job.start_date }} at 9:30 pm</span
                      >
                    </td>
                    <td>{{ job.weight }} Ton</td>
                    <td>
                      <template v-if="job.payment_status">
                        <span class="badges-item">Paid</span>
                      </template>
                      <template v-if="!job.payment_status">
                        <span class="badges-item">Unpaid</span>
                      </template>
                    </td>
                    <td>
                      <span class="badges-item">{{
                        jobStatusList[job.job_status]
                      }}</span>
                    </td>
                    <td>
                      <router-link
                        :to="{
                          name: 'editJob',
                          params: { jobId: job.id },
                        }"
                        class="btn-outline-green"
                        >Edit</router-link
                      >
                      <button
                        class="btn-outline-green"
                        @click="cancelJob(job.id)"
                      >
                        Cancel Job
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
    <app-small-footer />
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
  data() {
    return {
      jobList: [],
      jobStatusList: ["Yet to Start", "In Progress"],
    };
  },
  created() {
    const { name: routeName } = this.$route;
    JobService[routeName === "jobsList" ? "list" : "upcomingJobsList"](
      this.$route.params.farmId
    ).then((response) => {
      this.jobList = response.data.data;
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
        const jobIndex = this.jobList.findIndex((job) => job.id === managerId);
        this.jobList.splice(jobIndex, 1);
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
