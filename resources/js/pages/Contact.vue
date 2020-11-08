<template>
  <div>
    <app-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="fade-down">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2>Contact Us</h2>
            </div>

            <div class="col-md-6">
              <p>
                If you have any questions regarding our service or terms for
                work and payment, or comments. Call, mail or drop at our office.
              </p>

              <ul class="contact-apge-addss">
                <li>
                  <h5>Address</h5>
                  <p>
                    132 Nottingham Road
                    <br />Royal Palm Beach, FL 33411 <br />USA
                  </p>
                </li>

                <li>
                  <h5>General</h5>
                  <p>
                    <a href="mailto:info@wellingtonagricultural.com"
                      >info@wellingtonagricultural.com</a
                    >
                    <br />
                    <a href="tel:+5617902347">(+561)-790-2347</a>
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <div class="contact-form-section" data-aos="fade-down">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="con-im-ratio">
                <img src="img/contact-form-im.jpg" alt />
              </div>
            </div>

            <div class="col-md-6">
              <!-- <form
                class="shake"
                role="form"
                method="post"
                id="contactForm"
                name="contact-form"
                data-toggle="validator"
              > -->
              <v-form
                ref="form"
                v-model="valid"
                data-toggle="validator"
                class=""
                id="contactForm"
                lazy-validation
              >
                <!-- Name -->
                <div class="form-group label-floating">
                  <label class="control-label" for="name">First Name</label>
                  <!-- <input
                    class="form-control"
                    id="name"
                    type="text"
                    placeholder="Fill in your first name"
                    name="name"
                    required
                    data-error="Please enter your name"
                  /> -->
                  <v-text-field
                    v-model="addForm.first_name"
                    required
                    :rules="[(v) => !!v || 'Please enter your first name']"
                    placeholder="Fill in your first name"
                    class="form-control"
                  ></v-text-field>
                </div>

                <div class="form-group label-floating">
                  <label class="control-label" for="name">Last Name</label>
                  <!-- <input
                    class="form-control"
                    id="name"
                    type="text"
                    placeholder="Fill in your last name"
                    name="name"
                    required
                    data-error="Please enter your name"
                  /> -->
                  <v-text-field
                    v-model="addForm.last_name"
                    required
                    :rules="[(v) => !!v || 'Please enter your last name']"
                    placeholder="Fill in your last name"
                    class="form-control"
                  ></v-text-field>
                </div>
                <!-- email -->
                <div class="form-group label-floating">
                  <label class="control-label" for="email">Email</label>
                  <!-- <input
                    class="form-control"
                    id="email"
                    type="email"
                    placeholder="Fill in your email address"
                    name="email"
                    required
                    data-error="Please enter your Email"
                  /> -->
                  <v-text-field
                    v-model="addForm.email"
                    required
                    :rules="[(v) => !!v || 'Please enter your email']"
                    placeholder="Fill in your email address"
                    class="form-control"
                  ></v-text-field>
                </div>

                <!-- Message -->
                <div class="form-group label-floating">
                  <label for="message" class="control-label">Message</label>
                  <!-- <textarea
                    class="form-control"
                    rows="3"
                    id="message"
                    placeholder="Fill your message"
                    name="message"
                    required
                    data-error="Write your message"
                  ></textarea> -->
                  <v-textarea
                    rows="3"
                    auto-grow
                    v-model="addForm.message"
                    :rules="[(v) => !!v || 'Write your message']"
                    placeholder="Fill your message"
                    class="form-control"
                    required
                  ></v-textarea>
                </div>

                <div class="form-submit">
                  <!-- <button class="btn btn-common" type="submit" id="form-submit">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>Send Message
                  </button> -->
                  <v-btn
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                    class="btn btn-common"
                    @click="submit"
                    id="form-submit"
                  >
                    <i class="fa fa-angle-right" aria-hidden="true"></i>Send
                    Message</v-btn
                  >
                  <div class="clearfix"></div>
                </div>
              </v-form>
            </div>
          </div>
        </div>
      </div>
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
  data() {
    return {
      valid: true,
      loading: false,
      addForm: {
        first_name: "",
        last_name: "",
        email: "",
        message: "",
      },
    };
  },
  methods: {
    submit() {
      // this.loading = true;
      if (this.$refs.form.validate()) {
        jobService.addContact(this.addForm).then((response) => {
          // this.loading = false;

          if (response.status) {
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
      }
    },
  },
};
</script>
