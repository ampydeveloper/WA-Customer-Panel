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
                                    <small
                                        >Affordable solutions for smaller</small
                                    >
                                </h2>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="new-user-signup">
                                <h1>
                                    Sign Up
                                    <small>As Customer</small>
                                </h1>

                                <form
                                    action=""
                                    @submit.prevent="submitUserDetails"
                                >
                                    <vue-form-generator
                                        tag="div"
                                        :schema="schema"
                                        :options="formOptions"
                                        :model="model"
                                    />
                                    <div class="form-group">
                                        <button
                                            type="submit"
                                            class="btn btn-success btn-lg btn-block"
                                            :disabled="isSaving"
                                        >
                                            {{
                                                isSaving
                                                    ? "Saving..."
                                                    : "Submit"
                                            }}
                                        </button>
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
import signUpFormSchema from "../forms/signUpFormSchema";

export default {
    components: {
        AppHeader
    },
    data() {
        return {
            model: {
                first_name: "",
                last_name: "",
                gender: "",
                skills: ["Javascript", "VueJS"],
                email: "",
                age: "",
                phone_number: "",
                city: "",
                about: "",
                username: "",
                password: ""
            },
            schema: signUpFormSchema,
            formOptions: {
                validateAfterChanged: true
            },
            isSaving: false
        };
    },
    methods: {
        async submitUserDetails() {
            console.log(this.model);
            // try {
            //     await axios.post(
            //         "https://user-management-api.enkaypeter.repl.co/users",
            //         {
            //             ...this.model
            //         }
            //     );
            //     alert("Saved Successfully");
            // } catch (e) {
            //     console.log(e);
            //     alert(e.message);
            // }
        }
    }
};
</script>
