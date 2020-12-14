<template>
  <div>
    <div class="main-wrapper">
      <section class="page-section-top">
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
              <div class="img" v-bind:style="{ backgroundImage: 'url(' + item.service_image + ')' }" ></div>
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
                <router-link class="schedule-now" to="/pickups/create">
                  <i class="fa fa-angle-right" aria-hidden="true"></i> Schedule Now
               </router-link>
            </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JobService from "../services/JobService";
export default {
  components: {
    
  },
   data() {
    return {
      services: [],
    };
  },
  mounted() {
    this.getResults();
    $(document).ready(function() {
               feather.replace();
  });
  },
  methods: {
    getResults() {
      JobService.listServices().then((response) => {
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
