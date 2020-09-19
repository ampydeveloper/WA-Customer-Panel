<template>
  <div>
    <div class="panel-heading">
      <div class="btn-group pull-right">
        <router-link
          :to="{
            name: 'createManager',
            params: { farmId: $route.params.farmId }
          }"
          class="btn btn-success btn-sm"
          >Create Manager</router-link
        >
      </div>
      <h4>Managers</h4>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="manager in managerList" :key="manager.id">
          <td>{{ manager.full_name }}</td>
          <td>{{ manager.email }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import FarmService from "../../../../services/FarmService";

export default {
  data() {
    return {
      managerList: []
    };
  },
  created() {
    FarmService.listManagers(this.$route.params.farmId).then(response => {
      this.managerList = response.data.data;
    });
  }
};
</script>
