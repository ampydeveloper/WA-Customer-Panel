<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- <a class="navbar-brand" href="#">WELLINGTON</a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div
      class="collapse navbar-collapse"
      id="navbarNavDropdown"
      style="justify-content: space-between;"
    >
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="navbarDropdownMenuLink"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Farms
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="/farms">List</a>
            <a
              v-if="isCustomer || isHauler"
              class="dropdown-item"
              href="/farms/create"
              >Create</a
            >
          </div>
        </li>
        <li class="nav-item dropdown" v-if="isCustomer || isHauler">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="navbarDropdownMenuLink"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Cards
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="/cards">List</a>
            <a
              v-if="isCustomer || isHauler"
              class="dropdown-item"
              href="/cards/create"
              >Create</a
            >
          </div>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="navbarDropdownMenuLink"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            {{ currentUser.full_name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <router-link :to="{ name: 'profileSettings' }" class="dropdown-item"
              >Profile</router-link
            >
            <a class="dropdown-item" href="javascript:void()" @click="logout"
              >Logout</a
            >
          </div>
        </li>
      </ul>
    </div> -->
  </nav>
</template>

<script>
export default {
  methods: {
    logout: () => {
      window.localStorage.removeItem("token");
      window.localStorage.removeItem("user");
      window.location.href = "/";
    }
  }
};
</script>
