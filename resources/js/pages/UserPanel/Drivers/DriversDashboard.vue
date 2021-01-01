<template>
  <div class="loc-page logged-in-page">
    <!-- <app-small-header /> -->
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Driver Dashboard</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="job-dashboard-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <table
                id="all-drivers-table"
                class="table basic-table"
              >
                <thead>
                  <tr>
                    <th class="job-summ">Driver Name</th>
                    <th>Email</th>
                    <th class="time-col">Phone</th>
                    <th>Active</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(driver, index) in alldrivers">
                    <td>
                      <span class="basic-big">{{ driver.full_name }}</span>
                    </td>
                    <td>
                      <span class="basic-big">{{ driver.email }}</span>
                    </td>
                    <td>
                      <span class="basic-big">{{ driver.phone }}</span>
                    </td>
                    <td>
                      <span class="basic-big">
                        <span v-if='driver.is_active == 1'><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                        <span v-else><svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>
                      </span>
                    </td>
                    <td>
                      <router-link :to="{ name: 'editDriver', params: { driverId: driver.id }}" class="btn btn-table-outline">Edit</router-link>
                      <button @click="deleteDriver(driver.id)" class="btn btn-table-outline">  Delete</button>
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
import DriverService from "../../../services/DriverService";

export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
      subFooter,
  },
  data: () => ({
    dialog: false,
    jobList: {}, 
    alldrivers: []
  }),

  created() {
    const { name: routeName } = this.$route;
    DriverService["list"]().then((response) => {
      if ($.fn.dataTable.isDataTable(".basic-table")) {
        $(".basic-table").DataTable().destroy();
      }
      this.alldrivers = response.data.data;
      this.initDt();
    });
  },

  methods: {
    deleteDriver: async function (driverId) {
      try {
        const response = await DriverService.delete(driverId);
        this.$toast.open({
          message: response.data.message,
          type: "success",
          position: "top-right",
          dismissible: false,
        });
        const { name: routeName } = this.$route;
        DriverService["list"]().then((response) => {
          this.alldrivers = response.data.data;
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
    initDt: () => {
      setTimeout(function() {
        $(document).ready(function() {
          if (!$.fn.dataTable.isDataTable(".basic-table")) {
            $(".basic-table").DataTable({
                "bSort": false,
                oLanguage: {
                    sSearch: "",
                    "sEmptyTable": "No data available."
                },
                drawCallback: function(settings) {
                    $(".dataTables_paginate .paginate_button.previous").html(
                        $("#table-chevron-left").html()
                    );
                    $(".dataTables_paginate .paginate_button.next").html(
                        $("#table-chevron-right").html()
                    );
                },
            });
            $(".dataTables_filter").append($("#search-input-icon").html());
            $(".dataTables_filter input").attr(
                "placeholder",
                "Search Farms by Farm Location / Manager"
            );
            $(".dataTables_paginate .paginate_button.previous").html(
                $("#table-chevron-left").html()
            );
            $(".dataTables_paginate .paginate_button.next").html(
                $("#table-chevron-right").html()
            );
          }
          $(".basic-table").css({
            opacity: 1
          });
      });
    }, 100);
    }
  },
  
};
</script>