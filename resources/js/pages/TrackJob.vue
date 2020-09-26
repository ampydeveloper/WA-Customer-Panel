<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
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
              <div class="map-route-outer">MAP comes here</div>

              <div class="job-summary-outer">
                <!-- <h5 class="heading2">Job Summary</h5> -->

                <div class="images-list">
                  <h6>Service Images</h6>
                  <div class="images-all row">
                    <div class="each-image col-sm-4">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/content-img-1.228da091.jpg"
                        alt
                      />
                    </div>
                    <div class="each-image col-sm-4">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/content-img-1.228da091.jpg"
                        alt
                      />
                    </div>
                    <div class="each-image col-sm-4">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/content-img-1.228da091.jpg"
                        alt
                      />
                    </div>
                    <div class="each-image col-sm-4">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/content-img-1.228da091.jpg"
                        alt
                      />
                    </div>
                    <div class="each-image col-sm-4">
                      <img
                        src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/content-img-1.228da091.jpg"
                        alt
                      />
                    </div>
                  </div>
                </div>

                <div class="details-list-outer row">
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job ID</p>
                    <p class="detail-item">#JOB100</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Service</p>
                    <p class="detail-item">STANDARD LOAD</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Cost</p>
                    <p class="detail-item">$675</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Manager</p>
                    <p class="detail-item">Elijah Davis</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Farm Location</p>
                    <p class="detail-item">Wellington Te Aro Wellington 62365</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Date / Est. Time</p>
                    <p class="detail-item">24, May 2020 at 9:30 AM</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Quantity</p>
                    <p class="detail-item">30 Ton</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Gate Number</p>
                    <p class="detail-item">3045</p>
                  </div>
                  <!-- <div class="each-details">
                    <p class="head-item">Payment Status</p>
                    <p class="detail-item">Unpaid	</p>
                  </div> -->
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job Status</p>
                    <p class="detail-item green-sm-box">Open</p>
                  </div>

                  <div class="each-details-full col-sm-12">
                    <p class="head-item">Notes</p>
                    <p class="detail-item">Cake sesame snaps cupcake gingerbread danish I love gingerbread. Apple pie pie jujubes chupa chups muffin halvah lollipop.</p>
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
    <app-small-footer />
  </div>
</template>

<script>
import AppSmallHeader from "../shared/components/AppSmallHeader";
import AppSmallFooter from "../shared/components/AppSmallFooter";
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
    return {};
  },
};
</script>