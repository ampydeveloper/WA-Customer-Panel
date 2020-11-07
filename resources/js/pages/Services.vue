<template>
  <div>
    <app-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="fade-down">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2>
                Our
                <br />Services
              </h2>
            </div>

            <div class="col-md-6">
              <p>
                For removal, we use our rubber track loader and a dump truck with a container. We load the container with manure and haul it to the
                nearest certified compost center. We continue until we have taken the number of loads specified by you.
              </p>
            </div>
          </div>
        </div>
      </section>

      <div class="services-main">
        <div class="container">
          <div class="row">

            <div class="col-md-6 service-block-custom" data-aos="" v-for="item in services">
              <div class="service-block-img">
              <img :src="item.service_image" alt />
            </div>
              <div class="service-block-custom-inner">
                <h4>{{ item.service_name }}</h4>
                <p>{{ item.description }}</p>
                <ul>
                  <li>Year Round Service</li>
                  <li>Weekly or Daily Schedule</li>
                  <li>Scale Back In The Summer</li>
                  <li>Auto Pick Up</li>
                  <li>Largest Volume</li>
                </ul>
                <router-link class="schedule-now" to="/jobs/create">
                  <i class="fa fa-angle-right" aria-hidden="true"></i> Schedule Now
               </router-link>
            </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <app-footer />
  </div>
</template>

<script>
import AppHeader from "../shared/components/AppHeader";
import AppFooter from "../shared/components/AppFooter";
import JobService from "../services/JobService";
export default {
  components: {
    AppHeader,
    AppFooter,
  },
   data() {
    return {
      services: [],
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
      JobService.servicesForAll().then((response) => {
        if (response.status) {
          this.services = response.data.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
  }
};
</script>
