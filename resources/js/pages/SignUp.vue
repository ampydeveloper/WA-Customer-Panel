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
                  Sign Up
                  <small>As Customer</small>
                </h1>

                <form action="" novalidate>
                  <vue-form-generator
                    tag="div"
                    :schema="schema"
                    :options="formOptions"
                    :model="model"
                  />
                </form>

                <p class="already-account">
                  Already have an account?
                  <router-link to="sign-in">Sign In</router-link>
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
import signUpFormSchema from "../forms/signUpFormSchema";
import AuthService from "../services/AuthService";

export default {
  components: {
    AppHeader,
  },
  data() {
    return {
      model: {
        first_name: undefined,
        last_name: "",
        email: "",
        password: "",
        password_confirmation: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        zipcode: "",
        role_id: 4,
      },
      schema: {
        fields: [
          ...signUpFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Submit",
            caption: "Submit form",
            validateBeforeSubmit: true,
            disabled: () => this.isRegistering,
            onSubmit: (model, schema) => {
              this.isRegistering = true;
              AuthService.register(model)
                .then(
                  (response) => {
                    const { access_token, user } = response.data.data;
                    window.localStorage.setItem("token", access_token);
                    window.localStorage.setItem("user", JSON.stringify(user));
                    window.localStorage.setItem("verifyEmail", response.data.message);
                    window.location.href = "/pickups";
                  },
                  (error) => {
                    for (const errorKey in error.response.data.errors) {
                      this.$toast.open({
                        message: error.response.data.errors[errorKey].join(
                          "<br/>"
                        ),
                        type: "error",
                        position: "top-right",
                        dismissible: false,
                      });
                    }
                  }
                )
                .finally((_) => {
                  this.isRegistering = false;
                });
            },
          },
        ],
      },
      formOptions: {
        validateAfterChanged: true,
      },
      isRegistering: false,
    };
  },
  created: async function () {
    feather.replace();
  }
};
</script>
