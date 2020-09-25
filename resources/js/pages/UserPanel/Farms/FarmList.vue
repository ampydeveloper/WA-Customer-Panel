<template>
  <div>
    <div class="panel-heading">
      <div class="btn-group pull-right" v-if="isCustomer || isHauler">
        <a href="/farms/create" class="btn btn-success btn-sm">Create Farm</a>
      </div>
      <h4>Farms</h4>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Address</th>
          <th scope="col">City</th>
          <th scope="col">Province</th>
          <th scope="col">Zipcode</th>
          <th scope="col" v-if="isCustomer || isHauler">Managers</th>
          <th scope="col">Jobs</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="farm in farmList" :key="farm.id">
          <td>{{ farm.farm_address }}</td>
          <td>{{ farm.farm_city }}</td>
          <td>{{ farm.farm_province }}</td>
          <td>{{ farm.farm_zipcode }}</td>
          <td v-if="isCustomer || isHauler">
            <router-link
              :to="{ name: 'managerList', params: { farmId: farm.id } }"
              >Managers</router-link
            >
          </td>
          <td>
            <router-link :to="{ name: 'jobsList', params: { farmId: farm.id } }"
              >Jobs</router-link
            >
            |
            <router-link
              :to="{ name: 'upcomingJobsList', params: { farmId: farm.id } }"
              >Upcoming Jobs</router-link
            >
          </td>
          <td>
            <router-link
              :to="{
                name: 'editFarm',
                params: { farmId: farm.id }
              }"
              class="btn btn-info btn-sm"
              >Edit</router-link
            >
            <button @click="deleteFarm(farm.id)" class="btn btn-danger btn-sm">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import DashboardHeader from "../../../shared/components/DashboardHeader";
import FarmService from "../../../services/FarmService";

export default {
  components: {
    DashboardHeader
  },
  data() {
    return {
      farmList: []
    };
  },
  created() {
    FarmService.list().then(response => {
      this.farmList = response.data.farms;
    });
  },
  methods: {
    deleteFarm: async function(farmId) {
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1ec285",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(async result => {
        if (result.isConfirmed) {
          try {
            const response = await FarmService.delete(farmId);
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false
            });
            const farmIndex = this.farmList.findIndex(
              farm => farm.id === farmId
            );
            this.farmList.splice(farmIndex, 1);
          } catch (error) {
            this.$toast.open({
              message: error.response.data.message,
              type: "error",
              position: "bottom-right",
              dismissible: false
            });
          }
        }
      });
    }
  }
};
</script>
