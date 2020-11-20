<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
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
              <div class="new-user-signup mt-5">
                <h1>Change Password</h1>

                <form action novalidate>
                  <vue-form-generator
                    tag="div"
                    :schema="schema"
                    :options="formOptions"
                    :model="model"
                  />
                </form>
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
import changePasswordFormSchema from "../forms/changePasswordFormSchema";
import AuthService from "../services/AuthService";
import AppSmallHeader from "../shared/components/AppSmallHeader";

export default {
  components: {
    AppHeader,
    AppSmallHeader
  },
  data() {
    return {
      model: {
        password: "",
        password_confirmation: "",
        token: this.$route.params.token,
      },
      schema: {
        fields: [
          ...changePasswordFormSchema.fields,
          {
            type: "submit",
            styleClasses: "submit-button",
            label: "Forgot Password",
            caption: "Forgot Password In form",
            validateBeforeSubmit: true,
            disabled: () => this.isSubmitting,
            onSubmit: (model, schema) => {
              this.isSubmitting = true;
              AuthService.changePassword(model)
                .then(
                  (response) => {
                    this.$toast.open({
                      message: response.data.message,
                      type: "success",
                      position: "bottom-right",
                      dismissible: false,
                    });
                    setTimeout(() => {
                      window.location.href = "/sign-in";
                    }, 500);
                  },
                  (error) => {
                    this.$toast.open({
                      message: error.response.data.message,
                      type: "error",
                      position: "bottom-right",
                      dismissible: false,
                    });
                  }
                )
                .finally((_) => {
                  this.isSubmitting = false;
                });
            },
          },
        ],
      },
      formOptions: {
        validateAfterChanged: true,
      },
      isSubmitting: false,
    };
  },
  created(){
    const currentUser = JSON.parse(window.localStorage.getItem("user"));
    if((currentUser.role_id == 5 || currentUser.role_id == 7) && currentUser.password_changed_at == null){
      this.$toast.open({
        message: `Hi ${currentUser.full_name}, Please change your password to proceed!`,
        type: "success",
        position: "top-right",
        dismissible: false,
      });
    }
  }
};
</script>
