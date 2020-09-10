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
              <p>If you have any questions regarding our service or terms for work and payment, or comments. Call, mail or drop at our office.</p>

              <ul class="contact-apge-addss">
                <li>
                  <h5>Address</h5>
                  <p>
                    132 Nottingham Road
                    <br />Royal Palm Beach, FL 33411
                    <br />USA
                  </p>
                </li>

                <li>
                  <h5>General</h5>
                  <p>
                    <a href="mailto:info@wellingtonagricultural.com">info@wellingtonagricultural.com</a>
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
              <form
                class="shake"
                role="form"
                method="post"
                id="contactForm"
                name="contact-form"
                data-toggle="validator"
              >
                <!-- Name -->
                <div class="form-group label-floating">
                  <label class="control-label" for="name">First Name</label>
                  <input
                    class="form-control"
                    id="name"
                    type="text"
                    placeholder="Fill in your first name"
                    name="name"
                    required
                    data-error="Please enter your name"
                  />
                  <div class="help-block with-errors"></div>
                </div>

                <div class="form-group label-floating">
                  <label class="control-label" for="name">Last Name</label>
                  <input
                    class="form-control"
                    id="name"
                    type="text"
                    placeholder="Fill in your last name"
                    name="name"
                    required
                    data-error="Please enter your name"
                  />
                  <div class="help-block with-errors"></div>
                </div>
                <!-- email -->
                <div class="form-group label-floating">
                  <label class="control-label" for="email">Email</label>
                  <input
                    class="form-control"
                    id="email"
                    type="email"
                    placeholder="Fill in your email address"
                    name="email"
                    required
                    data-error="Please enter your Email"
                  />
                  <div class="help-block with-errors"></div>
                </div>

                <!-- Message -->
                <div class="form-group label-floating">
                  <label for="message" class="control-label">Message</label>
                  <textarea
                    class="form-control"
                    rows="3"
                    id="message"
                    placeholder="Fill your message"
                    name="message"
                    required
                    data-error="Write your message"
                  ></textarea>
                  <div class="help-block with-errors"></div>
                </div>
                <!-- Form Submit -->
                <div class="form-submit">
                  <button class="btn btn-common" type="submit" id="form-submit">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>Send Message
                  </button>
                  <div class="clearfix"></div>
                </div>
              </form>
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
};
</script>
