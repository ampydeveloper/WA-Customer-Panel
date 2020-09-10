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
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    id="home-tab"
                    data-toggle="tab"
                    href="#faq-one"
                    role="tab"
                    aria-controls="home"
                    aria-selected="true"
                  >How does manure removal works?</a>
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="profile-tab"
                    data-toggle="tab"
                    href="#faq-two"
                    role="tab"
                    aria-controls="profile"
                    aria-selected="false"
                  >How does manure buckets are dropped?</a>
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="contact-tab"
                    data-toggle="tab"
                    href="#faq-three"
                    role="tab"
                    aria-controls="contact"
                    aria-selected="false"
                  >Vestibulum commodo felis quis tortor?</a>
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="tempor-tab"
                    data-toggle="tab"
                    href="#faq-four"
                    role="tab"
                    aria-controls="tempor"
                    aria-selected="false"
                  >Donec quis dui at dolor tempor interdum?</a>
                </li>
              </ul>
            </div>
            <!-- /.col-md-4 -->
            <div class="col-md-6 tabs-content-custom" data-aos="fade-up">
              <div class="tab-content" id="myTabContent">
                <div
                  class="tab-pane fade show active"
                  id="faq-one"
                  role="tabpanel"
                  aria-labelledby="home-tab"
                >
                  <p>Wellington agricultural services has been a premium agriculturalservice provider in the industry for over 30 years. we excel in delivering environmentally friendly, dependable, and outstanding customer service with optimal efficiency in everything we do! we are one of the largest agricultural & manure removal companies in the palm beach area, and provide the convenience of a recurring schedule, the value of competitive pricing, and the reliability to know we will take care of your needs.</p>
                </div>
                <div
                  class="tab-pane fade"
                  id="faq-two"
                  role="tabpanel"
                  aria-labelledby="profile-tab"
                >
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum! Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum!</p>
                </div>
                <div
                  class="tab-pane fade"
                  id="faq-three"
                  role="tabpanel"
                  aria-labelledby="contact-tab"
                >
                  <p>Wellington agricultural services has been a premium agriculturalservice provider in the industry for over 30 years. we excel in delivering environmentally friendly, dependable, and outstanding customer service with optimal efficiency in everything we do! we are one of the largest agricultural & manure removal companies in the palm beach area, and provide the convenience of a recurring schedule, the value of competitive pricing, and the reliability to know we will take care of your needs.</p>
                </div>
                <div
                  class="tab-pane fade"
                  id="faq-four"
                  role="tabpanel"
                  aria-labelledby="tempor-tab"
                >
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum! Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, eveniet earum. Sed accusantium eligendi molestiae quo hic velit nobis et, tempora placeat ratione rem blanditiis voluptates vel ipsam? Facilis, earum!</p>
                </div>
              </div>
            </div>
            <!-- /.col-md-8 -->
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
export default {
  components: {
    AppHeader,
    AppFooter,
  },
};
</script>
