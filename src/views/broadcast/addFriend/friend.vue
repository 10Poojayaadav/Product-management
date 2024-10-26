<template>
  <v-app class="my-app">
    <div class="page myfriend">
      <div class="main">
        <SideBar />
        <v-main class="body bg-black-111">
          <div class="content">
            <div class="content-area">
              <div class="wrapper text-center text-white">
                <div class="display-pic mb-5">
                  <v-img
                    v-if="content.profile_pic != null"
                    :width="100"
                    aspect-ratio="6/3"
                    cover
                    :src="content.profile_pic"
                  ></v-img>
                  <h3 v-else class="text-h3 text-center text-white">
                    {{ abb }}
                  </h3>
                </div>

                <h4 class="text-center text-h4 name mb-4">
                  {{ content.first_name ?? "" }}
                  {{ content.last_name ?? "" }}
                </h4>
                <div class="info">
                  <p class="mb-2">
                    <strong>{{ data.message }}</strong>
                  </p>
                  <p>you are now friend!!:)</p>
                </div>

                <div class="pagination">
                  <span></span>
                  <span class="active"></span>
                </div>
              </div>
              <button class="back-button-bottom" @click="backToScanPage">
                Back
              </button>
            </div>
          </div>
        </v-main>
      </div>
    </div>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
import SideBar from "../layouts/SideBar.vue";

export default {
  data() {
    return {
      content: {},
      data: {},
      abb: "",
    };
  },
  components: {
    SideBar,
  },
  methods: {
    getData() {},
    backToScanPage() {
      this.$router.push({
        path: "/addFriend",
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
            axios
              .post("send-request", {
                invite_code: atob(this.$route.params.code),
                other_id:this.content.id,
              })
              .then((response) => {
                console.log(response)
                this.data = response.data;
               
                
              })
              .catch((error) => {
                console.log(error.response.data.message)
                this.data = error;
              });

      })
      .catch((error) => {
        this.data = error;
      });
  },
};
</script>
