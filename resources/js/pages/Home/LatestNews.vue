<template>
  <section class="news-latest" data-aos="fade-up">
    <div class="container">
      <h2>
        Check out
        <br />our
        <span class="bg-custom-thickness">latest news</span>
      </h2>
      <div class="tabs-get">
        <!-- <div class="news-tabs">
          <h5>Work</h5>
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#menu1" aria-expanded="false">news</a>
            </li>
            <li class>
              <a data-toggle="tab" href="#menu2" aria-expanded="false">pulvinar</a>
            </li>
            <li>
              <a data-toggle="tab" href="#menu3" aria-expanded="true">risus</a>
            </li>
            <li>
              <a data-toggle="tab" href="#menu4" aria-expanded="true">amet</a>
            </li>
          </ul>
        </div> -->
        <div class="tab-content">
          <div id="menu1" class="tab-pane fade active show">
            <div class="products-inner row">
              
              <div class="col-md-4" v-for="item in news">
                <div class="news-latest-cols">
                  <div class="nes-im">
                    <a href="#">
                      <img :src="item.image" alt />
                    </a>
                  </div>
                  <span class="vendor">News</span>
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
      JobService.newsForAll().then((response) => {
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
  }
};
</script>
