<template>
  <div class="loc-page logged-in-page">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Job Summary</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="job-details-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 content-left-outer">
              <div class="map-route-outer">
                <router-link
                      :to="{
                        name: 'MapBox',
                        params: { jobId: job.id },
                      }"
                      class="btn btn-info btn-sm pull-right"
                      >Track
                      </router-link>
              </div>

              <div class="job-summary-outer">
                <!-- <h5 class="heading2">Job Summary</h5> -->

                <div class="images-list">
                  <h6>Service Images</h6>
                  <div class="images-all row">
                    <div class="each-image col-sm-4" v-for="(image, index) in JSON.parse(job.images)">
                      <img
                        :src="image"
                        alt
                      />
                    </div>
                  </div>
                </div>

                <div class="details-list-outer row">
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job ID</p>
                    <p class="detail-item">#JOB{{job.id}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Service</p>
                    <p class="detail-item">{{job.service.service_name}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Cost</p>
                    <p class="detail-item">${{job.amount}}</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Manager</p>
                    <p class="detail-item">{{ (job.manage) ? job.manage.full_name : '-'}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Farm Location</p>
                    <p class="detail-item">{{ job.farm.full_address }}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Date / Est. Time</p>
                    <p class="detail-item">24, May 2020 at 9:30 AM</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Quantity</p>
                    <p class="detail-item">{{job.weight}} Ton</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Gate Number</p>
                    <p class="detail-item">{{ job.gate_no }}</p>
                  </div>
                  <!-- <div class="each-details">
                    <p class="head-item">Payment Status</p>
                    <p class="detail-item">Unpaid	</p>
                  </div> -->
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job Status</p>
                    <p class="detail-item green-sm-box">{{ job.job_status_name }}</p>
                  </div>

                  <div class="each-details-full col-sm-12">
                    <p class="head-item">Notes</p>
                    <p class="detail-item">{{ job.notes }}</p>
                  </div>
                  
                </div>
              </div>
            </div>

            <div class="col-sm-6 content-right-outer">
              <div class="map-route-outer">CHAT comes here</div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import AppSmallHeader from "../../../shared/components/AppSmallHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";
import JobService from "../../../services/JobService";
import router from "../../../router";
// import {
//   PlusIcon,
// } from "vue-feather-icons";
export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
    //  PlusIcon,
  },
  data() {
    return {
      job: {
        farm_id: "",
        service_id: "",
        time_slots_id: "",
        job_providing_date: "2020-01-01",
        weight: 1,
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: 0
      }
    };
  },
  created: async function() {
    try {
      const response = await JobService.get(this.$route.params.jobId);
      this.job = response.data.data;
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false
      });
    }
  },
};
</script>