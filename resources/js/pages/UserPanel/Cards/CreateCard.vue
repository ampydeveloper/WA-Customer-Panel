<template>
  <v-app class="loc-page logged-in-page">
    <section class="page-section-top" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>Create Card</h2>
          </div>
        </div>
      </div>
    </section>

    <section class="profile-outer center-content-outer" data-aos="">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 content-left-outer">
            <div class="each-profile-block">
              <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                class="slide-right"
                autocomplete="off"
              >
                <div class="row">
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Card Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="cardDetails.name"
                        required
                        :rules="[v => !!v || 'Card Name is required.']"
                        placeholder="Enter Card Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Card Number</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="cardDetails.card_number"
                        required
                        :rules="[
                          v => !!v || 'Card Number is required.',
                          v =>
                            !v ||
                            /^(?:(4[0-9]{12}(?:[0-9]{3})?)|(5[1-5][0-9]{14})|(6(?:011|5[0-9]{2})[0-9]{12})|(3[47][0-9]{13})|(3(?:0[0-5]|[68][0-9])[0-9]{11})|((?:2131|1800|35[0-9]{3})[0-9]{11}))$/.test(
                              v
                            ) ||
                            'Card Number must be valid'
                        ]"
                        placeholder="Enter Card Number"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Expiry Month</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-select
                        v-model="cardDetails.card_exp_month"
                        :items="expiryMonths"
                        placeholder="Expiry Month"
                        :rules="[v => !!v || 'Expiry Month is required.']"
                      ></v-select>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Expiry Year</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-select
                        v-model="cardDetails.card_exp_year"
                        :items="expiryYears"
                        placeholder="Expiry Year"
                        :rules="[v => !!v || 'Expiry Year is required.']"
                      ></v-select>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>CVV</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        v-model="cardDetails.cvv"
                        type="password"
                        required
                        :rules="[
                          v => !!v || 'CVV is required.',
                          v =>
                            !v || /^[0-9]{3,3}$/.test(v) || 'CVV must be valid'
                        ]"
                        placeholder="CVV"
                      ></v-text-field>
                    </div>
                  </v-col>
                </div>
              </v-form>
              <div class="form-group">
                <button
                  type="button"
                  class="btn btn-success btn-lg btn-block"
                  @click="formSubmit"
                >
                  Submit
                </button>
              </div>
            </div>
          </div>

          <div class="col-sm-6 content-right-outer"></div>
        </div>
      </div>
    </section>
  </v-app>
</template>

<script>
import CardService from "../../../services/CardService";
import router from "../../../router";
import moment from "moment";
import _ from "lodash";

export default {
  data() {
    return {
      valid: true,
      cardDetails: {
        name: "",
        card_number: "",
        card_exp_month: "",
        card_exp_year: "",
        cvv: ""
      },
      expiryMonths: _.range(1, 13),
      expiryYears: _.range(
        moment().format("YYYY"),
        moment()
          .add(21, "year")
          .format("YYYY")
      )
    };
  },
  methods: {
    formSubmit: async function() {
      const isValidated = this.$refs.form.validate();
      if (isValidated === true) {
        try {
          const response = await CardService.create(this.cardDetails);
          if (response !== undefined && response.data !== undefined) {
            this.$toast.open({
              message: response.data.message,
              type: "success",
              position: "top-right",
              dismissible: false
            });
            router.push({ name: "cardList" });
          }
        } catch (error) {
          this.$toast.open({
            message: error.response.data.message,
            type: "error",
            position: "bottom-right",
            dismissible: false
          });
        }
      }
    }
  },
  created() {
    this.expiryMonths = [...this.expiryMonths].map(month => {
      return month < 10 ? `0${month}` : month;
    });
  }
};
</script>
