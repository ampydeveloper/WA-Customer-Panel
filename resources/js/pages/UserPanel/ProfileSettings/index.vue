<template>
  <div>
    <dashboard-header />
    <div class="main-wrapper" style="padding: 20px;">
      <router-view></router-view>
    </div>
    <app-small-footer />
  </div>
</template>

<script>
import DashboardHeader from "../../../shared/components/DashboardHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";

export default {
  components: {
    DashboardHeader,
    AppSmallFooter
  }
};
</script>
