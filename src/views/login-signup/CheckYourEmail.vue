<template>
  <v-app class="app form">
    <div class="check-email centered">
      <v-container>
        <div class="brand-logo">
          <v-img src="/src/assets/brand.png" height="200"></v-img>
          <h1 class="text-center text-white my-2">Spiritual Quotient</h1>
        </div>
        <div class="text-center">
          <div class="contents">
            <p class="text-white">Password reset instructions have been sent to your email!
            Make sure to check your spam folder too :)</p>
          </div>
          <v-btn @click="openEmail">OK, got it!</v-btn>
        </div>
      </v-container>
    </div>
    <footer>
      <p class="text-center text-white">©2024 Every Nation Singapore</p>
    </footer>
  </v-app>
  <h1></h1>
</template>
<script>
export default {
  data() {
    return {
      email: atob(this.$route.params.email),
    };
  },
  methods: {
    openEmail() {
      window.open("https://mail.google.com/", "_blank");
    },
  },
};
</script>
