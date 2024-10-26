<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="wrapper text-center text-white">
                
               
                 <div class="prayer-container">
                <header>
                 
                </header>
                <div class="prayers">
                  <div class="prayer">
                    <div class="group">
                        <p class="title">
                            occupation
                        </p>
                        <input type="text" style="color:white;" placeholder=" enter the occupation" v-model="occupation">
                    </div>
                 
                  </div>
                  <div class="prayer">
                    <div class="group" >
                         <p class="title">
                            Industry
                        </p>
                        <input type="text"  style="color:white;"  placeholder=" enter the industry" v-model="industry">

                    </div>                    
                  </div>
                  <div class="prayer">
                    <div class="group" >
                        <p class="title">
                            Interest
                        </p>
                        <input type="text" style="color:white;"  placeholder=" enter the intrest" v-model="interests">

                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                        <p class="title">
                            Hobbies
                        </p>
                        <input type="text" style="color:white;"  placeholder=" enter the hobbies" v-model="hobbies">

                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                        <p class="title">
                            Country
                        </p>
                        <select v-model="country">
                            <option value="c.id" v-for="(c, index) in country_list" :key="index">{{c.name}}</option>
                        </select>

                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group">
                          <p class="title">
                            Zip code
                        </p>
                        <input type="text" style="color:white;"  placeholder=" enter the Zip code" v-model="zip_code">
                      
                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group" >
                         <p class="title">
                            City
                        </p>
                        <input type="text" style="color:white;"  placeholder=" enter the city" v-model="city">
                                         
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group">
                         <p class="title">
                            Address
                        </p>
                      <input type="text"  style="color:white;"  placeholder=" enter the address" v-model="address">
             
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
      dob:"",
      occupation:"",
      industry:"",
      interests:"",
      hobbies:"",
      country:"",
      zip_code:"",
      city: "",
      address:"",
      userData:[],
      community_list:[],
      country_list:[],
    };
  },
   mounted(){
  this.countries();
    this.user();
 
  },
  methods:{
    user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
            this.occupation= this.userData.occupation;
            this.industry= this.userData.industry;
            this.interests= this.userData.interests;
            this.hobbies= this.userData.hobbies;
            this.country= this.userData.country;
            this.zip_code= this.userData.zip_code;
            this.city=this.userData.city;
            this.address=this.userData.address;
        })
    },
    updateProfile(){
      axios
      .post("update-addtional-details",{
        occupation:this.occupation,
        industry:this.industry,
        interests:this.interests,
        hobbies:this.hobbies,
        country:this.country,
        zip_code:this.zip_code,
        city:this.city,
        address:this.address,

      })
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
    countries(){
      axios.get("country")
      .then((response)=>{
        this.country_list= response.data.data;
      })
    },
    
  },
  
 
};
</script>