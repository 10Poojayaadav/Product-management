<template>
  <v-app class="my-app invite">
    <v-container class="container py-0">
      <div class="wrapper text-center">
        <div class="display-pic mb-8">
          <v-img
            v-if="content.profile_pic != null"
            :width="100"
            aspect-ratio="6/3"
            cover
            :src="content.profile_pic"
          ></v-img>
          <h2 v-else class="text-h3 text-center text-white">{{ abb }}</h2>
        </div>

        <div class="details">
          <h4 class="text-center text-h5 name mb-4">
            {{ content.first_name ?? "" }} {{ content.last_name ?? "" }}
          </h4>
          <b><p class="mb-5">Your friend has invited you!</p></b>
          <p>
            Almost there! Let's get your account created so you can start
            connecting.
          </p>
        </div>

        <div class="pagination">
          <span></span>
          <span class="active"></span>
          <span></span>
        </div>

        <div class="navigation">
          <v-btn class="theme-button bordered go-back" @click="backToScanPage"
            >Back</v-btn
          >
          <v-btn
            class="theme-button fill go-next"
            :to="{
              name: 'registration',
              params: { code: $route.params.code },
            }"
            >Next</v-btn
          >
        </div>
      </div>
    </v-container>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      content: {},
      im: false,
      abb: "",
    };
  },
  methods: {
    getData() {},
    backToScanPage() {
      this.$router.push({
        path: "/scan-qr",
        query: { code: this.$route.params.code },
      });
    },
  },
  created() {
    axios
      .post("invite-code-sqid", {
        invite_code: atob(this.$route.params.code),
      })
      .then((response) => {
        this.content = response.data.data;
        this.abb =
          this.content.first_name.charAt(0).toUpperCase() +
          "" +
          this.content.last_name.charAt(0).toUpperCase();
      })
      .catch((error) => {
        this.data = error;
      });
  },
};
</script>
