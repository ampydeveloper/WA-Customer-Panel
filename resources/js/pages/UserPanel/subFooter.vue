<template>
  <section class="footer-extra-details">
    <div class="store-app-outer">
      <div class="container">
        <div class="row btn-store-app">
          <div class="col-md-6">
            <h2>Make your life easier. Download our apps.</h2>
          </div>
          <div class="col-md-6">
            <div class="footer-download-btn">
              <a href="#">
                <img src="img/app-stor-icon.png" alt />
              </a>
              <a href="#">
                <img src="img/google-play-icon.png" alt />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="news-footer-outer col-sm-7">
          <h2>
            Our
            <br />
            <span class="bg-custom-thickness">latest news</span>
          </h2>
          <div class="tabs-get">
            <div class="tab-content">
              <div class="tab-pane fade active show">
                <div class="products-inner row">
                  <div class="col-md-12" v-for="item in news">
                    <div class="news-latest-cols">
                      <div class="nes-im">
                        <a href="#">
                          <div
                            class="img"
                            v-bind:style="{
                              backgroundImage:
                                'url(http://wellington.leagueofclicks.com/' +
                                item.image +
                                ')',
                            }"
                          ></div>
                        </a>
                      </div>
                      <span class="vendor">News</span>
                      <div class="news-right">
                        <h3>
                          <a href="#">
                            {{ item.heading }}
                          </a>
                        </h3>
                        <p>
                          {{ item.description }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="social-footer-outer col-sm-5">
          <h2>
            Our
            <br />
            <span class="bg-custom-thickness">social</span> media
          </h2>

          <div class="row">
            <div class="insta-post">
              <a href="#">
                <div class="insta-im">
                  <img src="img/instagram-1.jpg" alt />
                </div>
                <div class="insta-overlay">
                  <h4>Donec nec justo eget</h4>
                </div>
              </a>
            </div>
            <!-------------post-end------------>

            <div class="insta-post">
              <a href="#">
                <div class="insta-im">
                  <img src="img/instagram-2.jpg" alt />
                </div>
                <div class="insta-overlay">
                  <h4>Donec nec justo eget</h4>
                </div>
              </a>
            </div>
            <!-------------post-end------------>

            <div class="insta-post">
              <a href="#">
                <div class="insta-im">
                  <img src="img/instagram-3.jpg" alt />
                </div>
                <div class="insta-overlay">
                  <h4>Donec nec justo eget</h4>
                </div>
              </a>
            </div>
            <!-------------post-end------------>

            <div class="insta-post">
              <a href="#">
                <div class="insta-im">
                  <img src="img/instagram-6.jpg" alt />
                </div>
                <div class="insta-overlay">
                  <h4>Donec nec justo eget</h4>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import JobService from "../../services/JobService";
export default {
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
      JobService.newsTwo().then((response) => {
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