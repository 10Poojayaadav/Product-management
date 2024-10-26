<template>
  <v-app class="app form">
    <div class="signup centered">
      <v-container>
        <div class="brand-logo">
          <v-img src="/src/assets/brand.png" height="200"></v-img>
          <h1 class="text-center text-white my-2">Spiritual Quotient</h1>
        </div>
        <v-form @submit.prevent="login()" method="post" class="text-center">
        <div class="field">
          <v-text-field
            v-model="email"
            placeholder="Email"
            name="email"
          />
        </div>
        <div class="field password">
          <v-text-field
            v-model="password"
            :type="showPassword == true ? 'text' : 'password'"
            placeholder="Password"
            name="password"
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
        <p class="mb-5">
          <router-link :to="{ name: 'forgot-password' }" 
          class="link text-decoration-none text-white"
            >Forgot Password?</router-link
          >
        </p>
        <v-btn type="submit">Let's go!</v-btn>
      </v-form>
      </v-container>
      
      </div>
      <footer>
        <p class="text-center text-white">©2024 Every Nation Singapore</p>
      </footer>
  </v-app>
</template>

<script>
if (window.location.href.includes("/")) {
  const state = { page_id: 1 };
  const url = window.location.href;
  history.pushState(state, "", url);
}
import axios from "../../router/axios.js";

export default {
  data() {
    return {
      email: "",
      password: "",
      showPassword: false,
    };
  },
  methods: {
    login() {
      axios
        .post("login", { email: this.email, password: this.password })
        .then(async (response) => {
          if (response.data.status == true) {
            if (response.data.data && response.data.data.token) {
              localStorage.setItem("token", response.data.data.token);
              await this.$store.dispatch("login");
              axios.defaults.headers.common["Authorization"] =
                "Bearer " + response.data.data.token;
              this.$router.push("messages");
              this.$toast.success("Login successful!", {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
            }
          }
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          console.error("Error fetching data:", error);
        });
    },
  },
};
</script>
