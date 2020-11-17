<template>
  <div class="logged-in-page">
    <footer class="site-footer" style="padding: 100px 0 0 0 !important">
      <div class="container">
        <div class="row">
          <div class="col-md-12 foo-top-text">
            <h3>
              Wellington Agricultural services has been a premium agricultural
              service provider in the industry for over 30 years. We are one of
              the largest agricultural & manure removal companies in the palm
              beach area.
            </h3>
          </div>
          <div class="footer-middle-part row">
            <div class="col-md-6 footr-links-upper">
              <div class="row">
                <div class="col-md-6">
                  <h4>Resources</h4>
                  <ul class="footer-links">
                    <li>
                      <a href="#how-it-work">How it works</a>
                    </li>
                    <li>
                      <router-link to="/faq">FAQ</router-link>
                    </li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <h4>Company</h4>
                  <ul class="footer-links">
                    <li>
                      <router-link to="/about"> About</router-link>
                    </li>
                    <li>
                      <router-link to="/contact">Contact</router-link>
                    </li>
                    <li>
                      <router-link to="/terms">Terms & Conditions</router-link>
                    </li>
                    <li>
                      <router-link to="/privacy-policy">Privacy Policy</router-link>
                    </li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <h4>Get in touch</h4>
                  <div class="footer-address">
                    <p>132 Nottingham Rd, Royal Palm Beach, FL 33411</p>
                    <p>
                      <a href="mailto:info@wellingtonagricultural.com"
                        >info@wellingtonagricultural.com</a
                      >
                    </p>
                    <p>
                      <a href="tel:56179023472">(561) 790-2347</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 btn-store-app">
              <h2>
                Make your life easier. Create your account now or download our
                apps.
              </h2>
              <div class="footer-download-btn">
                <!-- <a href="/sign-up" class="btn btn-account">Create Account</a> -->
                <router-link to="/sign-up" class="btn btn-account"
                  >Create Account</router-link
                >
                <a href="#">
                  <img src="img/app-stor-icon-gray.png" alt />
                </a>
                <a href="#">
                  <img src="img/google-play-icon-gray.png" alt />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-boottom">
        <div class="container">
          <div class="row">
            <div class="bootm-social-links col-md-6">
              <ul>
                <li>
                  <a href="https://www.facebook.com/wellingtonagricultural/">
                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/wellingtonagricultural/">
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://twitter.com/wellingtonAg1">
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.youtube.com/channel/UCy1HGr7-iLfV8tLqs6dvMxw">
                    <i class="fa fa-youtube" aria-hidden="true"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/in/jose-gomez-32741b84/">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                  </a>
                </li>
              </ul>
            </div>
            <div class="copy-right-text col-md-6">
              <p>2020 &copy; Wellington Agricultural Services Inc.</p>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {};
</script>