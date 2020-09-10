<template>
  <div>
    <app-header />
    <div class="main-wrapper" style="padding-top: 0;">
      <div class="sign-up-form-outer">
        <div class="sign-up-form-inner">
          <div class="row">
            <div class="col-md-6 signup-im-bg">
              <div class="im-text">
                <h2>
                  WELLINGTON
                  <small>Affordable solutions for smaller</small>
                </h2>
              </div>
            </div>

            <div class="col-md-6">
              <div class="new-user-signup">
                <h1>
                  Sign Up
                  <small>As Customer</small>
                </h1>

                <form method="post">
                  <div class="form-group">
                    <label>User Name</label>
                    <input
                      type="text"
                      class="form-control"
                      name="user_name"
                      placeholder
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <label>Full Name</label>
                    <input
                      type="text"
                      class="form-control"
                      name="full_name"
                      placeholder
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <label>Company Name</label>
                    <input
                      type="text"
                      class="form-control"
                      name="comapnu_name"
                      placeholder
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <label>Email Address</label>
                    <input
                      type="email"
                      class="form-control"
                      name="email"
                      placeholder
                      required="required"
                    />
                  </div>
                  <div class="form-group">
                    <label>Mobile Number</label>
                    <input
                      type="text"
                      class="form-control"
                      name="comapnu_name"
                      placeholder
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <label>Password</label>
                    <input
                      type="password"
                      class="form-control"
                      name="password"
                      placeholder
                      required="required"
                    />
                  </div>
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <input
                      type="password"
                      class="form-control"
                      name="confirm_password"
                      placeholder
                      required="required"
                    />
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Sign Up</button>
                  </div>
                </form>

                <p class="already-account">
                  Already have an Account
                  <a href="#">Signin</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AppHeader from "../shared/components/AppHeader";
export default {
  components: {
    AppHeader,
  },
};
</script>
