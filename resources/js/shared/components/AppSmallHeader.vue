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
          <!-- <span class="navbar-toggler-icon"></span> -->
          <i data-feather="menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active" v-if="isCustomer">
              <a class="nav-link" href="javascript:void(0);">Services</a>
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
                <a class="dropdown-item" href="/jobs">
                  <i data-feather="grid"></i> Job Dashboard</a
                >
                <a class="dropdown-item" href="/jobs/create">
                  <i data-feather="briefcase"></i> Create Job</a
                >
                <a class="dropdown-item" href="/farms" v-if="isCustomer">
                  <i data-feather="circle"></i> Farm Dashboard</a
                >
                <a class="dropdown-item" href="/farms/create" v-if="isCustomer">
                  <i data-feather="pie-chart"></i> Create Farm</a
                >
              </div>
            </li>
            <li class="nav-item active">
              <a class="nav-link all-noti-link" href="#" @click="showNotiList">
                <span class="bell-outer"> <i data-feather="bell"></i></span>
                <div class="all-noti-div" v-if="!showNoti">
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
                  :src="user.image_url"
                  alt="name"
                  class="user_header_img"
                />
                <span class="full_name">{{ (user) ? user.full_name : '' }}</span>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <router-link
                  :to="{ name: 'profileSettings' }"
                  class="dropdown-item"
                  ><i data-feather="user"></i> Profile</router-link
                >
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
    return {
      showNoti:false,
      user: null
    };
  },
  created: function(){
    this.user = JSON.parse(window.localStorage.getItem("user"));
  },
  methods: {
    logout: () => {
      window.localStorage.removeItem("token");
      window.localStorage.removeItem("user");
      window.location.href = "/";
    },
    showNotiList: function () {
      this.showNoti = true;
    },
  },
};
</script>
