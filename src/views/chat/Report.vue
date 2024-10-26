<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="wrapper text-center text-white">
               
                  <div>
                      <p class="name" style="font-size:30px;"><b>Report</b> {{userData.first_name}}</p>
                  </div>
                  <div>
                     <p class="name">
                         You are about to report this user to SQ-web. Please provide
                      us with information to abusive or inappropriate behaviour 
                      or content in this space below.

                         </p> 
                  </div>
                  <div style=" margin-right:30px;">
                      <textarea v-model="message" id="" cols="80" rows="5" style="border:2px solid white; color:white;"></textarea>
                  </div>
                  <div>
                     <p class="name">
                         We will review your request and will take the appropiate actions 
                         within 24 hours.

                         </p> 
                  </div>
                  <v-btn @click="reportGroup()"> Report </v-btn>
                     
                    
                  </div>
                  <v-btn @click="$router.go(-1)">Back</v-btn>
                 

               

                
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
      userData:[],
      message:""
    };
  },
  methods:{
     
    user(){
        axios
        .post("contact-list-profile",{
            id:this.$route.params.id,
        })
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
        })
    },
     reportGroup() {
      axios
        .post("report-user", {
            type: "user",
         message:this.message,
         report_id:this.$route.params.id
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push({
            name: "friend-info",
            params: { id: this.$route.params.id},
          });
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
  mounted(){
    this.user();
  }
};
</script>