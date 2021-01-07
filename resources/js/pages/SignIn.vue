<template>
  <div>
    <app-header />
    <div class="main-wrapper" style="padding-top: 0">
      <div class="sign-up-form-outer">
        <div class="sign-up-form-inner">
          <div class="row">
            <div class="col-md-6 signup-im-bg">
              <div class="im-text">
                <h2>
                  WELLINGTON AGRICULTURAL SERVICES
                  <small>Affordable solutions for smaller farms.</small>
                </h2>
              </div>
            </div>

            <div class="col-md-6">
              <div class="new-user-signup">
                <h1>
                  Sign In
                  <small>As Customer</small>
                </h1>

                <form action novalidate>
                  <vue-form-generator
                    tag="div"
                    :schema="schema"
                    :options="formOptions"
                    :model="model"
                  />
                </form>

                <p class="already-account">
                  Don't have an account?
                  <router-link to="/sign-up">Sign Up</router-link>
                  <router-link class="for-pass-link" to="/forgot-password">Forgot Password?</router-link>
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
import signInFormSchema from "../forms/signInFormSchema";
import AuthService from "../services/AuthService";

export default {
  components: {
    AppHeader,
  },
  data() {
    return {
      model: {
        email: "",
        password: "",
      },
      schema: {
        fields: [
          ...signInFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Sign In",
            caption: "Sign In form",
            validateBeforeSubmit: true,
            disabled: () => this.isSigningIn,
            onSubmit: (model, schema) => {
              this.isSigningIn = true;
              AuthService.signIn(model)
                .then(
                  (response) => {
                    const { access_token, user } = response.data.data;
                    window.localStorage.setItem("token", access_token);
                    window.localStorage.setItem("user", JSON.stringify(user));

                    let cpToken = btoa(user.email);
                    if (
                      (user.role_id == 5 || user.role_id == 7) &&
                      user.password_changed_at == null
                    ) {
                      window.location.href = "/change-password/" + cpToken;
                    } else {
                      window.location.href = "/pickups";
                    }
                  },
                  (error) => {
                    this.$toast.open({
                      message: error.response.data.message,
                      type: "error",
                      position: "top-right",
                      dismissible: false,
                    });
                  }
                )
                .finally((_) => {
                  this.isSigningIn = false;
                });
            },
          },
        ],
      },
      formOptions: {
        validateAfterChanged: true,
      },
      isSigningIn: false,
    };
  },
  created: async function () {
    feather.replace();
  },
  mounted() {
   
      feather.replace();
 
  },
};
</script>
