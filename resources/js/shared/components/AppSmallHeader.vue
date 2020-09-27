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
                <a class="dropdown-item" href="#">
                  <i data-feather="grid"></i> Job Dashboard</a
                >
                <a class="dropdown-item" href="#">
                  <i data-feather="briefcase"></i> Create Job</a
                >
              </div>
            </li>
            <li class="nav-item active">
              <a class="nav-link all-noti-link" href="#">
                <span class="bell-outer"> <i data-feather="bell"></i></span>
                <div class="all-noti-div">
                  <div class="each-noti clearfix">
                    <div class="details-one">
                      <p class="title-item">New Message</p>
                      <p class="desc-item">You got new order of goods</p>
                    </div>
                    <div class="details-two">
                      <p class="date-item">10 min ago</p>
                    </div>
                  </div>
                  <div class="each-noti clearfix">
                    <div class="details-one">
                      <p class="title-item">New Message</p>
                      <p class="desc-item">You got new order of goods</p>
                    </div>
                    <div class="details-two">
                      <p class="date-item">10 min ago</p>
                    </div>
                  </div>
                </div>
              </a>
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
                <!-- <a class="dropdown-item" href="#"> <i data-feather="user"></i> Profile</a> -->
                <router-link
                  :to="{ name: 'profileSettings' }"
                  class="dropdown-item"
                  ><i data-feather="user"></i> Profile</router-link
                >

                <a class="dropdown-item" href="#">
                  <i data-feather="credit-card"></i> Payments</a
                >
                <a class="dropdown-item" href="#">
                  <i data-feather="bell"></i> Notifications</a
                >

                <!-- <a class="dropdown-item" href="#"> <i data-feather="log-out"></i> Logout</a> -->
                <a
                  class="dropdown-item"
                  href="javascript:void()"
                  @click="logout"
                  ><i data-feather="log-out"></i> Logout</a
                >
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
  methods: {
    logout: () => {
      window.localStorage.removeItem("token");
      window.localStorage.removeItem("user");
      window.location.href = "/";
    },
  },
};
</script>
