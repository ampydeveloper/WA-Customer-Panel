<template>
    <div>
        <app-header />
        <div class="main-wrapper" style="padding-top: 0;">
            <div class="sign-up-form-outer">
                <div class="sign-up-form-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="new-user-signup">
                                <h1>
                                    New Farm
                                </h1>

                                <form action="" novalidate>
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
import AppHeader from "../../../shared/components/AppHeader";
import farmFormSchema from "../../../forms/farmFormSchema";
import FarmService from "../../../services/FarmService";

const emptyFarmRequest = {
    farm_address: "",
    farm_city: "",
    farm_province: "",
    farm_zipcode: "",
    farm_active: 1,
    latitude: "",
    longitude: "",
    manager_details: [
        {
            manager_first_name: "customer",
            manager_last_name: "customer",
            email: `test${Date.now()}@g.com`,
            manager_phone: "1234567890",
            manager_address: "customer",
            manager_city: "customer",
            manager_province: "customer",
            manager_zipcode: "123456",
            manager_card_image: "customer",
            manager_id_card: "customer",
            salary: "20000"
        }
    ]
};

export default {
    components: {
        AppHeader
    },
    data() {
        return {
            model: emptyFarmRequest,
            schema: {
                fields: [
                    ...farmFormSchema.fields,
                    {
                        type: "submit",
                        styleClasses: "submit-button",
                        label: "Create Farm",
                        caption: "Create Farm form",
                        validateBeforeSubmit: true,
                        disabled: () => this.isCreatingFarm,
                        onSubmit: (model, schema) => {
                            this.isCreatingFarm = true;
                            FarmService.create(model)
                                .then(
                                    response => {
                                        this.$toast.open({
                                            message: response.data.message,
                                            type: "success",
                                            position: "top-right",
                                            dismissible: false
                                        });
                                        this.model = emptyFarmRequest;
                                    },
                                    error => {
                                        this.$toast.open({
                                            message:
                                                error.response.data.message,
                                            type: "error",
                                            position: "bottom-right",
                                            dismissible: false
                                        });
                                    }
                                )
                                .finally(_ => {
                                    this.isCreatingFarm = false;
                                });
                        }
                    }
                ]
            },
            formOptions: {
                validateAfterChanged: true
            },
            isCreatingFarm: false
        };
    }
};
</script>
