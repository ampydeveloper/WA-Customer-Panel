<template>
  <div>
    <app-header />

    <banner />

    <get-started />

    <how-we-work />

    <our-services />

    <testimonials />

    <latest-news />

    <our-social-media />

    <app-footer />
  </div>
  <!-----------------banner-section---------------->
</template>

<script>
import Banner from "./Banner";
import GetStarted from "./GetStarted";
import HowWeWork from "./HowWeWork";
import OurServices from "./OurServices";
import Testimonials from "./Testimonials";
import LatestNews from "./LatestNews";
import OurSocialMedia from "./OurSocialMedia";

import AppHeader from "../../shared/components/AppHeader";
import AppFooter from "../../shared/components/AppFooter";

export default {
  components: {
    AppHeader,
    Banner,
    GetStarted,
    HowWeWork,
    OurServices,
    Testimonials,
    LatestNews,
    AppFooter,
    OurSocialMedia,
  },
};
</script>
