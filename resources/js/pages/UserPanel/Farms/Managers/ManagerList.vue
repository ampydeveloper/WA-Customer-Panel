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
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="manager in managerList" :key="manager.id">
          <td>{{ manager.full_name }}</td>
          <td>{{ manager.email }}</td>
          <td>
            <button
              @click="deleteManager(manager.id)"
              class="btn btn-danger btn-sm"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import FarmService from "../../../../services/FarmService";
import JobService from "../../../../services/JobService";

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
  },
  methods: {
    deleteManager: async function(managerId) {
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
            const response = await FarmService.deleteManager(
              this.$route.params.farmId,
              managerId
            );
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false
            });
            const managerIndex = this.managerList.findIndex(
              manager => manager.id === managerId
            );
            this.managerList.splice(managerIndex, 1);
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
