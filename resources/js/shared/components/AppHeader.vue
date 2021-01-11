<template>
  <header class="site-header">
    <div class="container-fluid">
      <div class="logo-area">
        <router-link class="navbar-brand" to="/">
          <img src="img/main-logo.png" alt />
        </router-link>
      </div>
      <span class="menu-toggle" onclick="openNav()">
        <i data-feather="menu"></i>
      </span>
    </div>
    <div id="mySidenav" class="sidenav">
      <div class="nav-topfull">
        <div class="close-but" onclick="closeNav()">
          <i data-feather="x"></i>
        </div>

        <div class="row">
          <div class="col-sm-12 col-md-7 col-lg-7 head-left">
            <div class="nav-list1">
              <ul class="list-unstyled">
                <li>
                  <router-link to="/about"> About</router-link>
                </li>
                <li>
                  <router-link to="/services">Services</router-link>
                </li>
                <li>
                  <router-link to="/faq">FAQ</router-link>
                </li>
                <li>
                  <router-link to="/contact">Contact</router-link>
                </li>
              </ul>
            </div>
            <div class="nav-list2" v-if="!isLoggedIn">
              <ul class="list-unstyled">
                <li>
                  <router-link to="/sign-in">Sign In</router-link>
                </li>
                <li>
                  <router-link to="/sign-up">Sign Up</router-link>
                </li>
              </ul>
            </div>
            <div class="btn-store-app">
              <h2>Create your account now or download our apps.</h2>
              <div class="footer-download-btn clearfix">
                <router-link to="/sign-up" class="btn btn-account"
                  >Create Account</router-link
                >
                <a href="#" class="btn-link">
                  <img src="img/app-stor-icon-gray.png" alt />
                </a>
                <a href="#" class="btn-link">
                  <img src="img/google-play-icon-gray.png" alt />
                </a>
              </div>
            </div>
            <div class="bootm-social-links">
              <ul>
                <li>
                  <a href="https://www.facebook.com/wellingtonagricultural/">
                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/wellingtonagricultural/">
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://twitter.com/wellingtonAg1">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a
                    href="https://www.youtube.com/channel/UCy1HGr7-iLfV8tLqs6dvMxw"
                  >
                    <i class="fa fa-youtube" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/in/jose-gomez-32741b84/">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-5 services-slider d-none d-lg-block">
            <div class="item" v-for="item in services">
              <div class="service-block-img">
                <div
                  class="img"
                  v-bind:style="{
                    backgroundImage: 'url(' + item.service_image + ')',
                  }"
                ></div>
              </div>
              <div class="service-block-custom-inner">
                <h4>{{ item.service_name }}</h4>
                <p>
                  {{ item.description }}
                </p>
                <!-- <ul>
                  <li>Year Round Service</li>
                  <li>Weekly or Daily Schedule</li>
                  <li>Scale Back In The Summer</li>
                  <li>Auto Pick Up</li>
                  <li>Largest Volume</li>
                </ul> -->
                <router-link class="schedule-now" to="/pickups/create">
                  <i class="fa fa-angle-right" aria-hidden="true"></i>
                  Schedule Now
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import JobService from "../../services/JobService";
export default {
  data: () => {
    return {
      services: [],
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    logout: () => {
      window.localStorage.removeItem("token");
      window.localStorage.removeItem("user");
      window.location.href = "/";
    },
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
  computed: {
    // a computed getter
    isLoggedIn: function () {
      const token = window.localStorage.getItem("token");
      return token !== undefined && token !== null;
    },
  },
  mounted() {
    setTimeout(function(){
    if (typeof AOS.init == "function") {
      AOS.init({
        duration: 1200,
      });
    }
    $(document).ready(function() {
               feather.replace();
  });
    }, 1000);
  },
};
</script>
