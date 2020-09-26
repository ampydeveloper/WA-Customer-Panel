<template>
  <div class="loc-page logged-in-page">
    <app-small-header />
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Profile</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="profile-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 content-left-outer">
              <div class="each-profile-block">
                <div class="row">
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>First Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'First Name is required.']"
                        placeholder="Enter First Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
<v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Email</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>
                  <v-col cols="6" md="6" class="pt-0 pb-0">
                    <div class="label-align pt-0">
                      <label>Last Name</label>
                    </div>
                    <div class="pt-0 pb-0">
                      <v-text-field
                        required
                        :rules="[(v) => !!v || 'Last Name is required.']"
                        placeholder="Enter Last Name"
                      ></v-text-field>
                    </div>
                  </v-col>


                </div>
              </div>
            </div>

            <div class="col-sm-6 content-right-outer"></div>
          </div>
        </div>
      </section>
    </div>
    <app-small-footer />
  </div>
</template>

<script>
import AppSmallHeader from "../shared/components/AppSmallHeader";
import AppSmallFooter from "../shared/components/AppSmallFooter";
// import {
//   PlusIcon,
// } from "vue-feather-icons";
export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
    //  PlusIcon,
  },
  data() {
    return {};
  },
};
</script>