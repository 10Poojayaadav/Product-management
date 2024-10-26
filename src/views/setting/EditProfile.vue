<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="wrapper text-center text-white">
                <div class="user-profile mb-5">
                  <div v-if="userData.profile_pic != null">
                    <v-img 
                    :width="50"
                    aspect-ratio="6/3"
                    cover
                    :src="userData.profile_pic"
                  ></v-img>
                  
                  </div>
                  <div v-else>
                    <h2>aa</h2>
                   
                    <!-- <h2>
                    {{ userData.first_name.charAt(0).toUpperCase()
                    }}{{ userData.last_name.charAt(0).toUpperCase() }}
                            </h2> -->
                  </div>
                </div>
                   <input type="file" @change="uploadImage" />

                <h4 class="text-center text-h4 name mb-4">
                  {{ userData.first_name ?? "" }}
                  {{ userData.last_name ?? "" }}
                </h4>
                <div class="info">
                  <p class="mb-2">
                   SQID:-  <strong>{{ userData.sqid }}</strong>
                  </p>
                </div> 
                 <div class="prayer-container">
                <header>
                 
                </header>
                <div class="prayers">
                  <div class="prayer">
                    <div class="group">
                      <input type="text" v-model="first_name" placeholder="first" style="color:white;">
                    </div>
                 
                  </div>
                  <div class="prayer">
                    <div class="group" >
                     <input type="text" v-model="last_name" placeholder="Last" style="color:white;">
                    </div>                    
                  </div>
                  <div class="prayer">
                    <div class="group" >
                      <input type="text" v-model="email" placeholder="Email" style="color:white;">

                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                      <input type="text" v-model="mobile_no" placeholder="Mobile" style="color:white;">

                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                      <input type="text" v-model="birthday" placeholder="Birthday" style="color:white;">

                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group">
                      <select v-model="community"> 
                        <option v-for="(c1, index) in community_list" :key="index" :value="c1.id">{{c1.name}}</option>
                      </select>
                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group" >
                      <select v-model="gender" id="" >
                        <option value="male">male</option>
                        <option value="female" >female</option>
                      </select>                     
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push({
                      name:'additional'
                    })">
                      <p class="name">Additional Details</p>                  
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push({
                      name:'emergency'
                    })" >
                     <p class="name">Emergency contact</p>                   
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push({
                      name:'change-password'
                    })" >
                      <p class="name">Change Password</p>                    
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push({
                      name:'delete-account'
                    })" >
                     <p class="name">Delete Account</p>                   
                    </div>
                  </div>
                  <v-btn @click="updateProfile()">done</v-btn>
                </div>
              </div>
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
      first_name:"",
      last_name:"",
      email:"",
      mobile_no:"",
      community:"",
      birthday:"",
      type: "",
      gender:"",
      profile_pic: null,
      selectedFile: null,
      userData:[],
      community_list:[],
    };
  },
   mounted(){
   this.communityData();
    this.user();
 
  },
  methods:{
    user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
            this.first_name= this.userData.first_name;
            this.last_name= this.userData.last_name;
            this.email= this.userData.email;
            this.mobile_no= this.userData.mobile_no;
            this.birthday= this.userData.dob;
            this.community= this.userData.community_id;
            this.gender=this.userData.gender;

        })
    },
    updateProfile(){
      const formData = new FormData();
      formData.append('first_name', this.first_name);
      formData.append('last_name', this.last_name);
      formData.append('profile_pic', this.selectedFile);
      formData.append('email', this.email);
      formData.append('mobile_no', this.mobile_no);
      formData.append('dob', this.birthday);
      formData.append('community', this.community);
      formData.append('gender', this.gender);
      axios
      .post("update-user-profile", formData)
      .then((response)=>{
        console.log(response);
        this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        this.user();
      })
    },
     uploadImage(e) {
      const profile_pic = e.target.files[0];
      this.selectedFile = profile_pic;
      const reader = new FileReader();
      reader.onload = (e) => {
        this.profile_pic = e.target.result; 
      };
      if (profile_pic) {
        reader.readAsDataURL(profile_pic);
      }
    },
    communityData(){
      axios.get("get-community")
      .then((response)=>{
        console.log(response);
        this.community_list=response.data.data
      })
      .catch((error)=>{

       })
      },
  },
  
 
};
</script>