<template>
  <div>
    <dashboard-header />
    <div class="main-wrapper" style="padding: 20px;">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import DashboardHeader from "../../../shared/components/DashboardHeader";

export default {
  components: {
    DashboardHeader
  }
};
</script>
