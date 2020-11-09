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
              <router-link class="nav-link" to="/services"
                >Services</router-link
              >
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
                <router-link class="dropdown-item" to="/jobs"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Job Dashboard</router-link>
                <router-link class="dropdown-item" to="/jobs/create"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg> Create Job</router-link>
                <router-link class="dropdown-item" v-if="!isHauler && !this.isHaulerDriver" to="/farms"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg> Farm Dashboard</router-link>
                <router-link class="dropdown-item" v-if="!isHauler && !isManager && !this.isHaulerDriver" to="/farms/create"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> Create Farm</router-link>
                <router-link class="dropdown-item" v-if="isHauler" to="/drivers"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Driver Dashboard</router-link>
                <router-link class="dropdown-item" v-if="isHauler" to="/drivers/create"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> Create Driver</router-link>
                <router-link class="dropdown-item" v-if="isCustomer || isHauler" to="/managers"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Manager Dashboard</router-link>
                <router-link class="dropdown-item" v-if="isCustomer || isHauler" to="/managers/create"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg> Create Manager</router-link>
                <!-- <a class="dropdown-item" href="/jobs">
                  <i data-feather="grid"></i> Job Dashboard</a
                > -->
                 <!-- <a class="dropdown-item" href="/jobs/create">
                  <i data-feather="briefcase"></i> Create Job</a
                >
                <router-link
                  class="dropdown-item"
                  v-if="!isHauler"
                  to="/farms/create"
                  ><i data-feather="pie-chart"></i> 
                  Create Farm</router-link
                > -->
                <router-link class="dropdown-item" v-if="isHauler" to="/drivers"
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-users"
                  >
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                  Driver Dashboard</router-link
                >
                <router-link
                  class="dropdown-item"
                  v-if="isHauler"
                  to="/drivers/create"
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-user-plus"
                  >
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                  </svg>
                  Create Driver</router-link
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
                <img :src="user.image_url" alt="name" class="user_header_img" />
                <span class="full_name">{{ user ? user.full_name : "" }}</span>
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
      showNoti: false,
      user: null,
    };
  },
  created: function () {
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
