<template>
    <v-app v-if="forgotPage" class="app form">
      <div class="reset-password centered">
        <v-container >
          <div class="brand-logo">
            <v-img src="/src/assets/brand.png" height="200"></v-img>
            <h1 class="text-center text-white mb-4">Spiritual Quotient</h1>
          </div>
          <v-form class="text-center">
            <div class="field">
              <v-text-field v-model="email" placeholder="Email" name="email" />
            </div>
            <p class="text-white mb-5">We will send a link to this email address to reset your password.</p>
            <v-btn @click="forgotPassword">Reset my password</v-btn>
          </v-form>
        </v-container>
      </div>
      <footer>
        <p class="text-center text-white">©2024 Every Nation Singapore</p>
      </footer>
    </v-app>
    <v-app v-else class="app form">
      <div class="check-email centered">
        <v-container >
          <div class="brand-logo">
            <v-img src="/src/assets/brand.png" height="200"></v-img>
            <h1 class="text-center text-white my-2">Spiritual Quotient</h1>
          </div>
        <div class="text-center">
          <div class="contents">
            <p class="text-white">Sorry, we couldn’t find an account with that email address. Please check and try again.</p>
            </div>
          <v-btn @click="bringBack()">OK, bring me back.</v-btn>
        </div>
        </v-container>        
    </div>
    <footer>
      <p class="text-center text-white text-decoration-underline">©2024 Every Nation Singapore</p>
    </footer>
  </v-app>
</template>

<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      email: "",
      data: "",
      forgotPage:true,
      // emailRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Email field is required.";
      //     } else if (
      //       !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
      //         value
      //       )
      //     ) {
      //       return "Email is not valid.";
      //     } else return true;
      //   },
      // ],
    };
  },
  methods: {
    forgotPassword() {
      axios
        .post("password/send", {
          email: this.email,
        })
        .then((response) => {
          this.data = response.data;
          if(response.data.status == true)
          {
            this.$toast.success(response.data.message, {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
              theme: "sugar", // Toast theme
            });
            this.$router.push({
              name: "check-your-email",
              params: { email: btoa(this.email) },
            });
          }
          else
          {
            this.forgotPage = false;
          }
          
        })
        .catch((error) => {
          if (error.response.data.errors.email[0]) {
            this.$toast.error(error.response.data.errors.email[0], {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
            });
          } else {
            this.$toast.error(error.response.data.message, {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
            });
          }
         
        });
    },
    bringBack(){
      this.forgotPage = true;
    }
  },
  computed: {
    //
  },
  created() {
    // this.forgotPassword();
  },
};
</script>
