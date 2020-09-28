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
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      <a href="/about">About</a>
      <a href="/services">Services</a>
      <a href="/faq">FAQ</a>
      <a href="/contact">Contact</a>
      <a v-if="!isLoggedIn" href="/sign-in">Sign In</a>
      <a v-if="!isLoggedIn" href="/sign-up">Sign Up</a>
      <a v-if="isLoggedIn" href="javascript:void()" @click="logout()">Logout</a>
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
