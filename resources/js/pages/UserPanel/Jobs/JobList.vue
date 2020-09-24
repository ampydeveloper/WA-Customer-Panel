<template>
  <div>
    <div class="panel-heading">
      <div class="btn-group pull-right">
        <router-link
          :to="{
            name: 'createJob'
          }"
          class="btn btn-success btn-sm"
          >Create Job</router-link
        >
      </div>
      <h4>Jobs</h4>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Image</th>
          <th scope="col">Job Summary</th>
          <th scope="col">Duration</th>
          <th scope="col">Quantity</th>
          <th scope="col">Price</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="job in jobList" :key="job.id">
          <td></td>
          <td>{{ job.notes }}</td>
          <td></td>
          <td>{{ job.weight }} Ton</td>
          <td>${{ job.amount }}</td>
          <td>{{ jobStatusList[job.job_status] }}</td>
          <td>
            <router-link
              :to="{
                name: 'editJob',
                params: { jobId: job.id }
              }"
              class="btn btn-info btn-sm"
              >Edit</router-link
            >
            <button class="btn btn-sm btn-danger" @click="cancelJob(job.id)">
              Cancel Job
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import DashboardHeader from "../../../shared/components/DashboardHeader";
import JobService from "../../../services/JobService";

export default {
  components: {
    DashboardHeader
  },
  data() {
    return {
      jobList: [],
      jobStatusList: ["Yet to Start", "In Progress"]
    };
  },
  created() {
    const { name: routeName } = this.$route;
    JobService[routeName === "jobsList" ? "list" : "upcomingJobsList"](
      this.$route.params.farmId
    ).then(response => {
      this.jobList = response.data.data;
    });
  },
  methods: {
    cancelJob: async function(jobId) {
      try {
        const response = await JobService.cancel(jobId);
        this.$toast.open({
          message: response.data.message,
          type: "success",
          position: "top-right",
          dismissible: false
        });
        const jobIndex = this.jobList.findIndex(job => job.id === managerId);
        this.jobList.splice(jobIndex, 1);
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
};
</script>
