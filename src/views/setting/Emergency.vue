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
              <div>
                <div>
                  <h4 style="color: white">
                    First Name
                    <input type="text" v-model="first_name" style="border: 2px solid white" />
                  </h4>
                  <hr />
                  <h4 style="color: white">
                    Last Name
                    <input type="text" v-model="last_name" style="border: 2px solid white" />
                  </h4>
                  <hr />
                  <h4 style="color: white">
                    Email <input type="text" v-model="email" style="border: 2px solid white" />
                  </h4>
                  <hr />
                  <h4 style="color: white">
                    Relation
                    <input type="text" v-model="relationship" style="border: 2px solid white" />
                  </h4>
                  <hr />
                  <h4 style="color: white">
                    Mobile <select v-model="countryCode">
                     <option></option>
                    </select> 
                    <input type="text" v-model="mobile" style="border: 2px solid white" />
                  </h4>
                  <hr />
                </div>
              </div>
              <v-btn @click="emergency()">SAVE</v-btn>
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
      countryCode:'',
      mobile:'',
      relationship:"",
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
            this.first_name= this.userData.e_first_name;
            this.last_name= this.userData.e_last_name;
            this.email= this.userData.e_email;
            this.countryCode= this.userData.e_country_code;
            this.mobile= this.userData.e_mobile_no;
            this.relationship= this.userData.e_relationship;
            
        })
    },
    emergency() {
      const formData = new FormData();
      formData.append("e_first_name", this.first_name);
      formData.append("e_last_name", this.last_name);
      formData.append("e_email", this.email);
      formData.append("e_country_code", this.countryCode);
      formData.append("e_mobile_no", this.mobile);
      formData.append("e_relationship", this.relationship);
      axios
        .post("update-emergency-details", formData)
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
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
  mounted() {
   this.user();
    // this.createGroup();
  },
};
</script>
