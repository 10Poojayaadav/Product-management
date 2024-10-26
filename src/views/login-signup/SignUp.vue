<template>
  <v-app>
    <div class="welcome-screen">
      <div class="wrapper">
        <div class="graphics">
          <v-img src="@/assets/landing-page-logo.png" alt="Brand Logo"></v-img>
        </div>
        <h2 class="text-h4 font-weight-medium mb-1">Welcome Back,</h2>
        <p>Sign Up/Login to continue</p>
        <v-btn
          :to="{ name: 'scan' }"
          class="sign-up-btn mt-8 mb-4"
          color="white"
          text
          rounded
          elevation="2"
        >
          Sign Up
        </v-btn>
        <p class="mt-5 login-link">
          Already have an account?
          <b><router-link :to="{ name: 'login' }">LOGIN</router-link></b>
        </p>
      </div>
    </div>
  </v-app>
</template>

<script>
export default {
  name: "App",
};
</script>
