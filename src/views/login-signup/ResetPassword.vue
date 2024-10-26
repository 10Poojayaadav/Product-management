<template>
  <v-app class="app form">
    <div class="signup centered">
      <v-container>
        <div class="brand-logo">
          <v-img src="/src/assets/brand.png" height="200"></v-img>
          <h1 class="text-center text-white my-2">Spiritual Quotient</h1>
        </div>
        <v-form @submit.prevent="login()" method="post" class="text-center">
         <div class="field password">
          <v-text-field
            v-model="new_password"
            :type="showPassword == true ? 'text' : 'password'"
            placeholder="Type new password"
            name="new_password"
          />
          <button @click="showPassword = !showPassword" type="button">
            <img
              v-if="showPassword"
              src="@/assets/eye.png"
              alt="Hide Password"
              class="hide"
            />
            <img
              v-else
              src="@/assets/hide.png"
              alt="Show Password"
              class="show"
            />
          </button>
        </div>
        <div class="field password">
          <v-text-field
            v-model="confirm_password"
            :type="showPassword == true ? 'text' : 'password'"
            placeholder="Confirm new password"
            name="confirm_password"
          />
          <button @click="showPassword = !showPassword" type="button">
            <img
              v-if="showPassword"
              src="@/assets/eye.png"
              alt="Hide Password"
              class="hide"
            />
            <img
              v-else
              src="@/assets/hide.png"
              alt="Show Password"
              class="show"
            />
          </button>
        </div>
        <v-btn type="submit">Let's do this!</v-btn>
      </v-form>
      </v-container>
    </div>
    <footer>
      <p class="text-center text-white">©2024 Every Nation Singapore</p>
    </footer>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";

export default {
  data() {
    return {
      new_password: "",
      confirm_password: "",
    };
  },
};
</script>
