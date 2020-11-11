<template>
  <div class="loc-page logged-in-page">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>
                Manager
                <br />
                <span class="bg-custom-thickness"> Dashboard </span>
              </h2>
            </div>
          </div>
        </div>
      </section>

      <section class="job-dashboard-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <table class="table basic-table" id="all-managers-table">
                <thead>
                  <tr>
                    <th class="job-summ">Manager Name</th>
                    <th>Email</th>
                    <th class="time-col">Phone</th>
                    <th>Address</th>
                    <th>Farm</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(manager, index) in managerList" :key="manager.id">
                    <td>{{ manager.full_name }}</td>
                    <td>{{ manager.email }}</td>
                    <td>{{ manager.phone }}</td>
                    <td>{{ manager.address }}</td>
                    <td>
                      <select
                        class="form-control"
                        v-model="manager.farm_id"
                        @change="updateFarm(manager.id, $event)"
                      >
                        <option
                          :value="farm.value"
                          v-bind:key="farm.value"
                          :selected="manager.farm_id == farm.value"
                          v-for="farm in farms"
                          v-text="farm.text"
                        ></option>
                      </select>
                    </td>
                    <td>
                      <router-link
                        :to="{
                          name: 'editManager',
                          params: { managerId: manager.id },
                        }"
                        class="btn btn-table-outline"
                        >Edit</router-link
                      >
                      <button
                        @click="deleteManager(manager.id)"
                        class="btn btn-table-outline"
                      >
                        Delete
                      </button>
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
  </div>
</template>

<script>
import subFooter from "../../subFooter";
import FarmService from "../../../../services/FarmService";
import JobService from "../../../../services/JobService";

export default {
  components: {
    subFooter,
  },
  data() {
    return {
      managerList: [],
      farms: [],
    };
  },
  created() {
    this.getManagers();
  },
  methods: {
    getManagers(){
      FarmService.listManagers().then((response) => {
        // console.log(response.data);
        this.managerList = response.data.data;
      });
      FarmService.list().then((response) => {
        this.farms = [
          ...response.data.farms.map((farm) => {
            return {
              text: farm.farm_address,
              value: farm.id,
            };
          }),
        ];
      });
    },
    updateFarm(managerId, $event) {
      FarmService.changeManager($event.target.value, managerId).then(
        (response) => {
          this.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top-right",
            dismissible: false,
          });
        },
        (error) => {
          this.getManagers();
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false,
          });
        }
      );
    },
    deleteManager: async function (managerId) {
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1ec285",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then(async (result) => {
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
              dismissible: false,
            });
            const managerIndex = this.managerList.findIndex(
              (manager) => manager.id === managerId
            );
            this.managerList.splice(managerIndex, 1);
          } catch (error) {
            this.$toast.open({
              message: error.response.data.message,
              type: "error",
              position: "bottom-right",
              dismissible: false,
            });
          }
        }
      });
    },
  },
};
</script>
