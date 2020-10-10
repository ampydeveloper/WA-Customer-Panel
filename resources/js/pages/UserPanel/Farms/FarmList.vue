<template>
  <div class="main-wrapper">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>Farms Dashboard</h2>
          </div>
        </div>
      </div>
    </section>

    <section class="job-dashboard-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <table id="all-farms-table" class="table basic-table">
              <thead>
                <tr>
                  <th scope="col">Farm Location</th>
                  <th scope="col">Primary Manager / Phone / Email</th>
                  <th scope="col">Est. Jobs</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="farm in farmList" :key="farm.id">
                  <td>
                    {{ farm.farm_address }} {{ farm.farm_city }} <br />
                    {{ farm.farm_province }} {{ farm.farm_zipcode }}
                  </td>
                  <td v-if="!farm.primary_manager"> N/A </td>
                  <td v-if="farm.primary_manager"> {{ farm.primary_manager.full_name }} / {{ farm.primary_manager.phone }} / {{ farm.primary_manager.email }}</td>
                  <td>
                    <span class="badge-tag">{{ farm.total_jobs }}</span>
                  </td>
                  <td>
                    <router-link
                      :to="{
                        name: 'editFarm',
                        params: { farmId: farm.id },
                      }"
                      class="btn btn-table-outline"
                      v-if="isCustomer || isHauler"
                      > <i data-feather="edit-3"></i> Edit</router-link
                    >
                    <!-- <router-link
                      :to="{
                        name: 'managerList',
                        params: { farmId: farm.id },
                      }"
                      class="btn btn-info btn-sm"
                      v-if="isCustomer || isHauler"
                      >Managers</router-link
                    > -->
                    <button
                      @click="deleteFarm(farm.id)"
                      class="btn btn-table-outline"
                    > <i data-feather="x"></i>  Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    
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
import DashboardHeader from "../../../shared/components/DashboardHeader";
import FarmService from "../../../services/FarmService";

export default {
  components: {
    DashboardHeader,
  },
  data() {
    return {
      farmList: [],
    };
  },
  created() {
    FarmService.list().then((response) => {
      this.farmList = response.data.farms;
    });
  },
  methods: {
    deleteFarm: async function (farmId) {
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
            const response = await FarmService.delete(farmId);
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false,
            });
            const farmIndex = this.farmList.findIndex(
              (farm) => farm.id === farmId
            );
            this.farmList.splice(farmIndex, 1);
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
