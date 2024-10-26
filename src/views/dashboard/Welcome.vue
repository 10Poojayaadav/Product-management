<template>
  <v-app class="my-app">
    <div class="welcome-screen">
      <v-container class="container py-0">
        <div class="wrapper text-center">
          <div class="graphics imgBox mb-5">
            <img src="/src/assets/accountSetupComplete.png" />
          </div>
          <h3 class="fs-35">You're All Set!</h3>
          <p class="mb-15">You have successfully created your SQ account.</p>
          <v-btn class="theme-button fill start-sq" :to="{ name: 'home-page' }"
            >Start SQ</v-btn
          >
        </div>
      </v-container>
    </div>
  </v-app>
</template>

<script>
if (window.location.href.includes("welcome-screen")) {
  const state = { page_id: 1 };
  const url = window.location.href;
  history.pushState(state, "", url);
}
</script>
