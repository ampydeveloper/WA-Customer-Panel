
<template>
  <div>
    <app-header />
    <div class="main-wrapper">
      <introduction />
      <removal-service />
      <think-about-this />
      <benefits-transfer-station />
    </div>
    <app-footer />
  </div>
</template>

<script>
import AppHeader from "../../shared/components/AppHeader";
import AppFooter from "../../shared/components/AppFooter";

import Introduction from "./Introduction";
import BenefitsTransferStation from "./BenefitsTransferStation";
import RemovalService from "./RemovalService";
import ThinkAboutThis from "./ThinkAboutThis";

export default {
  components: {
    AppHeader,
    Introduction,
    RemovalService,
    ThinkAboutThis,
    BenefitsTransferStation,
    AppFooter,
  },
};
</script>
