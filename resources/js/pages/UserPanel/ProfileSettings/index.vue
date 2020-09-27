<template>
  <div>
    <div class="loc-page logged-in-page">
      <app-small-header />

      <router-view></router-view>
      <app-small-footer />
    </div>
  </div>
</template>

<script>
import AppSmallHeader from "../../../shared/components/AppSmallHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";

export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
  },
};
</script>
