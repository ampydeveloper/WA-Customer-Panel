<template>
  <div>
    <div class="panel-heading">
      <div class="btn-group pull-right">
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
          <th scope="col">Managers</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="farm in farmList" :key="farm.id">
          <td>{{ farm.farm_address }}</td>
          <td>{{ farm.farm_city }}</td>
          <td>{{ farm.farm_province }}</td>
          <td>{{ farm.farm_zipcode }}</td>
          <td>
            <router-link
              :to="{ name: 'managerList', params: { farmId: farm.id } }"
              >Managers</router-link
            >
          </td>
          <td>
            <button class="btn btn-sm btn-info">Edit</button>
            <button class="btn btn-sm btn-danger">Delete</button>
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
  }
};
</script>
