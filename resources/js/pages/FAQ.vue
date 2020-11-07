<template>
  <div>
    <app-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="fade-down">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2>
                Frequently
                <br />Asked
                <br />
                <span>Questions</span>
              </h2>
            </div>
            <div class="col-md-6">
              <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas auguae, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</p>
            </div>
          </div>
        </div>
      </section>
      <section class="ask-question-tabs">
        <div class="container">
          <div class="row">

            <div class="col-md-6 mb-3 tabs-title-custom" data-aos="fade-down">
              <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
              
               <li class="nav-item" v-for="(item, index) in faqs">
                  <a
                    class="nav-link active"
                    id="home-tab"
                    data-toggle="tab"
                    :href="'#faq-'+index"
                    role="tab"
                    aria-controls="home"
                    aria-selected="true"
                  >{{ item.question }}</a>
                </li>
               
              </ul>
            </div>
           
            <div class="col-md-6 tabs-content-custom" data-aos="fade-up">
              <div class="tab-content" id="myTabContent">

                <div
                  class="tab-pane fade show active"
                  :id="'#faq-'+index"
                  role="tabpanel"
                  aria-labelledby="home-tab"  v-for="(item, index) in faqs"
                >
                  <p>{{ item.answer }}</p>
                </div>
               
              </div>
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
      faqs: [],
    };
  },
  mounted() {
    this.getResults();
  },
  methods: {
    getResults() {
      JobService.faqForAll().then((response) => {
        if (response.status) {
          this.faqs = response.data.data;
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
