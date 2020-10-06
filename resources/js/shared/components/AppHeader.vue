<template>
  <header class="site-header">
    <div class="container-fluid">
      <div class="logo-area">
        <a href="/">
          <img src="img/main-logo.png" alt />
        </a>
      </div>
      <span class="menu-toggle" onclick="openNav()">
        <i data-feather="menu"></i>
      </span>
    </div>
    <div id="mySidenav" class="sidenav">
      <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <a href="/about">About</a>
      <a href="/services">Services</a>
      <a href="/faq">FAQ</a>
      <a href="/contact">Contact</a>
      <a v-if="!isLoggedIn" href="/sign-in">Sign In</a>
      <a v-if="!isLoggedIn" href="/sign-up">Sign Up</a>
      <a v-if="isLoggedIn" href="javascript:void()" @click="logout()">Logout</a> -->

      <div class="nav-topfull">
        <div class="close-but" onclick="closeNav()">
          <i data-feather="x"></i>
        </div>

        <div class="row">
          <div class="col-sm-12 col-md-7 col-lg-7 head-left">
            <div class="nav-list1">
              <ul class="list-unstyled">
                <li>
                  <a href="/about">About</a>
                </li>
                <li>
                  <a href="/services">Services</a>
                </li>
                <li>
                  <a href="/faq">FAQ</a>
                </li>
                <li>
                  <a href="/contact">Contact</a>
                </li>
              </ul>
            </div>
            <div class="nav-list2" v-if="!isLoggedIn">
              <ul class="list-unstyled">
                <li>
                  <a href="/sign-in">Sign In</a>
                </li>
                <li>
                  <a href="/sign-up">Sign Up</a>
                </li>
              </ul>
            </div>
            <div class="btn-store-app">
              <h2>Create your account now or download our apps.</h2>
              <div class="footer-download-btn clearfix">
                <a href="/sign-up" class="btn btn-account">Create Account</a>
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
                  <a href="#">
                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-pinterest" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-5 services-slider d-none d-lg-block">
            <div class="item">
              <div class="service-block-img">
                <img src="/img/signup-bg-im.jpg" alt />
              </div>
              <div class="service-block-custom-inner">
                <h4>20 YARD SERVICE</h4>
                <p>
                  This service is most commonly used by our customers. It
                  includes 20 yard service of your farm including manure pickup
                  and taking it to our dumping station.
                </p>

                <ul>
                  <li>Year Round Service</li>
                  <li>Weekly or Daily Schedule</li>
                  <li>Scale Back In The Summer</li>
                  <li>Auto Pick Up</li>
                  <li>Largest Volume</li>
                </ul>

                <a href="#" class="schedule-now">
                  <i class="fa fa-angle-right" aria-hidden="true"></i>
                  Schedule Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  data: () => {
    return {};
  },
  methods: {
    logout: () => {
      window.localStorage.removeItem("token");
      window.localStorage.removeItem("user");
      window.location.href = "/";
    },
  },
  computed: {
    // a computed getter
    isLoggedIn: function () {
      const token = window.localStorage.getItem("token");
      return token !== undefined && token !== null;
    },
  },
};
</script>
