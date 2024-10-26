<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <!-- <v-img src="/src/assets/office-push-pin.svg" style="width:50px;" @click="pinned()"></v-img> -->
              <v-img
                src="/src/assets/pen.png" height="20"
                style="width: 50px"
                @click="
                  $router.push({
                    name: 'edit-friend-info',
                    params: { id: $route.params.id },
                  })
                "
              ></v-img>
              <div class="wrapper text-center text-white">
                <div class="user-profile ">
                  <div v-if="userData.profile_pic != null">
                    <v-img
                      v-if="userData.profile_pic != null"
                      :width="50"
                      aspect-ratio="6/3"
                      cover
                      :src="userData.profile_pic"
                      
                    ></v-img>
                  </div>
                  <div v-else>
                   
                    <h1>{{username(userData.first_name+" "+userData.last_name)}}
                            </h1>
                           
                  </div>
                </div>

                <h4 class="text-center text-h4 name mb-5 mt-5">
                  {{ userData.first_name ?? "" }} {{ userData.last_name ?? "" }}
                </h4>
                 <p>{{userData.bio}}</p>
                <div class="info" style="display: flex;">
                <img src="/src/assets/cake-white.png" height="20" alt="">
                  <p style="margin-left:4px; margin-right:auto;">
                    {{ userData.dob }}
                  </p>
                  <img src="/src/assets/info.svg" height="20" alt="">
                  <p v-if="userData.occupation == null">N/A</p>
                  <p v-else>{{userData.occupation }}</p>
                </div>
              </div>
              <hr />
              <div class="info-btn">
                 <v-btn v-if="userData.block =='Already Blocked'">{{userData.block }}</v-btn>
                <v-btn v-else @click="launchMyModal()">Block User</v-btn> <br />
                <v-btn
                  @click="
                    $router.push({
                      name: 'report-user',
                      params: { id: $route.params.id },
                    })
                  "
                  >Report User</v-btn
                >
              </div>
            </div>
            <!-- block user pop up -->
            <div class="mymodal" id="mymodal">
              <div class="myform">
                <div class="form">
                  <h2>Block User?</h2>
                  <p>
                    You are about to block this user. Blocked user will no
                    longer be able to pray with you or send you message
                  </p>
                </div>
                <v-btn @click="closeMyModal()"> CANCEL </v-btn>
                <v-btn @click="blocked()"> BLOCK </v-btn>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      type: "",
      userData: [],
    };
  },
  methods: {
      username(groupName) {
      var group = groupName?.split(" ");
      let a = group[0]?.charAt(0);
      let b = group[1]?.charAt(0);
      return a?.toUpperCase() + "" + b?.toUpperCase();
    },
    launchMyModal() {
      document.querySelector("#mymodal").classList.add("open");
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
    },
    user() {
      axios
        .post("contact-list-profile", {
          id: this.$route.params.id,
        })
        .then((response) => {
          console.log(response.data.data);
          this.userData = response.data.data;
        });
    },
    blocked() {
      axios
        .post("block-user", {
          block_id: this.$route.params.id,
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push(-1);
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
  },
  mounted() {
    this.user();
  },
};
</script>
