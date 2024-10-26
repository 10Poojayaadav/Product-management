<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
                <v-img src="/src/assets/office-push-pin.svg" style="width:50px;"></v-img>
                 <v-img src="/src/assets/office-push-pin.svg" style="width:50px;"></v-img>
              <div class="wrapper text-center text-white">
                <div class="user-profile mb-5">
                  <div v-if="userData.profile_pic != null">
                    <v-img
                    v-if="userData.profile_pic != null"
                    :width="30"
                    aspect-ratio="6/3"
                    cover
                    :src="userData.profile_pic"
                  ></v-img>
                  </div>
                  <div v-else>
                    <h2 >

                    </h2>
                    <!-- <h2>
                    {{ userData.first_name.charAt(0).toUpperCase()
                    }}{{ userData.last_name.charAt(0).toUpperCase() }}
                            </h2> -->
                  </div>
                  

                </div>

                <h4 class="text-center text-h4 name mb-4">
                  {{ userData.first_name ?? "" }}
                 
                </h4>
                <div class="info">
                  <p class="mb-2">
                  {{userData.dob}}
                  </p>
                  <v-btn>occupation</v-btn>
                </div> 
                <textarea v-model="details" id="" cols="80" rows="5" style="border:2px solid white; color:white;"></textarea>
                <v-btn @click="bioData()">Done</v-btn>
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
      details:"",
      type: "",
      userData:[],
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
            this.details=this.userData.bio;
        })
    },
     bioData(){
        axios
        .post("contact-list-bio",{
          other_id:this.$route.params.id,
          bio:this.details
        })
        .then((response)=>{
            console.log("++++++++++++++++++",response)
            this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push({
            name: "friend-info",
            query: { id: this.$route.params.id, },
          });
            
        })
    },
  },
  mounted(){
    this.user();

  }
};
</script>