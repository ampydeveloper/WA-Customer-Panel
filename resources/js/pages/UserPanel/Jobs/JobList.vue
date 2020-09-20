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
        <!-- <tr v-for="job in jobList" :key="job.id">
          <td>{{ job.farm_address }}</td>
          <td>{{ job.farm_city }}</td>
          <td>{{ job.farm_province }}</td>
          <td>{{ job.farm_zipcode }}</td>
          <td>
            <router-link
              :to="{
                name: 'editJob',
                params: { jobId: job.id }
              }"
              class="btn btn-info btn-sm"
              >Edit</router-link
            >
          </td>
        </tr> -->
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
      jobList: []
    };
  },
  created() {
    JobService.list().then(response => {
      //   this.jobList = response.data.jobs;
    });
  }
};
</script>
