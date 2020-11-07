<template>
  <section class="our-services-home" data-aos="fade-up">
    <div class="container">
      <h2>
        Our
        <br />Services
      </h2>

      <div class="services-slider">
        <div class="owl-carousel owl-theme" id="service-slide-home">
          <div class="item" v-for="item in services">
            <div class="service-block-img">
              <img :src="item.service_image" alt />
            </div>
            <div class="service-block-custom-inner">
              <h4>{{ item.service_name }}</h4>
              <p>
                {{ item.description }}
              </p>
              <ul>
                <li>Year Round Service</li>
                <li>Weekly or Daily Schedule</li>
                <li>Scale Back In The Summer</li>
                <li>Auto Pick Up</li>
                <li>Largest Volume</li>
              </ul>
                <router-link class="schedule-now" to="/jobs/create">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
                Schedule Now
                </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import JobService from "../../services/JobService";
export default {
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
  },
};
</script>
