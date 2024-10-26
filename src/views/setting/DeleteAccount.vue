<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="wrapper text-center text-white">
                <div class="user-profile mb-5"></div>

                <h4 class="text-center text-h5 name mb-4"></h4>
                <div class="info"></div>
              </div>
              <div style="color:white;">
                <p>Type in your SQID displayed below into the box below to delete your account</p>
                <h3 class="name">{{ userData.sqid }}</h3> 
               
                <div >
                 <input type="text" v-model="sqid" style="border: 2px solid white; color:white">
                 <hr>
                </div>
                <div >
                 <span style="padding:6px;">
                 <p class="name">It may take up to 30 days to completely delete all your account 
                  and user data. When your accountis deleted, the app will log out 
                  and go back to the sign-up screen. You will not be able to log in with this account
                  any more.
                 </p>
                 </span>
                 <h4> We're sad to see you go, but we'd like to know why and how we can help</h4>
                  <p>Type your comment/ suggestion here</p>
                  <textarea v-model="message" cols="80" rows="5" style="border: 2px solid white; color:white;"></textarea>
                </div>
              </div>
              <v-btn @click="deleteAccount()" :disabled="sqid.length ==0 && message.length== 0">DELETE MY ACCOUNT</v-btn>
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
     sqid:'',
     message:"",
     userData:[],
    };
  },
  methods: {
   user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
        })
    },
    deleteAccount() {
      const formData = new FormData();
      formData.append("sqid", this.sqid);
      formData.append("message", this.message);
      axios
        .post("account-delete", formData)
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.logout();
          this.$router.push('/');
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    logout() {
      axios
        .post("logout")
        .then(async (response) => {
          localStorage.removeItem("token");
          await this.$store.dispatch("logout");
          this.$router.push({ name: "login" });
        })
        .catch(async (error) => {
          await logout(error);
        });
    },
  },
  mounted() {
  this.user();
  },
};
</script>
