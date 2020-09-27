<template>
  <div>
    <div class="panel-heading">
      <div class="btn-group pull-right">
        <router-link
          :to="{
            name: 'createCard'
          }"
          class="btn btn-success btn-sm"
          >Create Card</router-link
        >
      </div>
      <h4>
        <credit-card-icon size="1.5x" class="custom-class"></credit-card-icon>
        Cards
      </h4>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Card Number</th>
          <th scope="col">Card Expiry</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(card, index) in cardList" :key="card.id">
          <td>{{ index + 1 }}</td>
          <td>{{ card.name }}</td>
          <td>{{ card.card_number }}</td>
          <td>{{ card.card_exp_month }} {{ card.card_exp_year }}</td>
          <td>
            <button class="btn btn-sm btn-danger">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import CardService from "../../../services/CardService";
import { CreditCardIcon } from "vue-feather-icons";

export default {
  components: { CreditCardIcon },
  data() {
    return {
      cardList: []
    };
  },
  created() {
    CardService.list().then(response => {
      this.cardList = response.data.data;
    });
  }
};
</script>
