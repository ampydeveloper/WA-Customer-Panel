<template>
  <header class="site-header">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
          <img src="img/main-logo.png" alt />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Services</a>
            </li>

            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                Jobs
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Job Dashboard</a>
                <a class="dropdown-item" href="#">Create Job</a>
              </div>
            </li>
            <li class="nav-item dropdown user-name-link">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdown"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <img
                  src="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/demo-1/img/avatar-s-5.99691e54.jpg"
                  alt="name"
                />
                John Dane
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Profile</a>
                <a class="dropdown-item" href="#">Payments</a>
                <a class="dropdown-item" href="#">Notifications</a>
                <a class="dropdown-item" href="#">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  data: () => {
    return {};
  },
  methods: {},
  computed: {
    // a computed getter
    isLoggedIn: function () {
      const token = window.localStorage.getItem("token");
      return token !== undefined && token !== null;
    },
  },
};
</script>
