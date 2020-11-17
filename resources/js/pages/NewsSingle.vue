<template>
  <div>
    <app-header />
    <div class="main-wrapper">
      <section class="page-section-top page-top-title" data-aos="fade-down">
        <div class="container">
          <div class="row">
            <div class="col-md-10 news-single-marg">
              <h2>
                {{ news.heading }}
              </h2>
            </div>
          </div>
        </div>
      </section>
      <section class="ask-question-tabs">
        <div class="container">
          <div class="row">
            <div class="col-md-10 news-single-marg">
              <div class="news-single-image" v-bind:style="{ backgroundImage: 'url(' + news.image + ')' }" ></div>
             
              <div v-html="news.description"></div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <app-footer />
  </div>
</template>

<script>
import AppHeader from "../shared/components/AppHeader";
import AppFooter from "../shared/components/AppFooter";
import JobService from "../services/JobService";
export default {
  components: {
    AppHeader,
    AppFooter,
  },
  data() {
    return {
      news: [],
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
        
      JobService.newsSingle(this.$route.params.newsId).then((response) => {
        if (response.status) {
          this.news = response.data.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
  },
};
</script>