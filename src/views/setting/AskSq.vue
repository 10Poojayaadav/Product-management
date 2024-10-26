<template>
 <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
                 <v-form method="post">
          <div class="imageBox">
            <img src="/src/assets/sq-logo.png" alt="" />
          </div>
          <v-text-field v-model="userData.first_name" label="Name" name="name" type="text" />
          <v-text-field
            v-model="userData.email"
            label="Email"
            name="email"
            type="email"
          />
          <div class="mobile-field">
            <v-select
              v-model="countryCode"
              :items="countries"
              item-title="code"
              item-value="code"
            ></v-select>
            <v-text-field
              v-model="userData.mobile_no"
              label="Mobile Number"
              name="mobile_no"
              type="mobile_no"
            />
          </div>
          <v-textarea
            v-model="message"
            label="Message"
            name="message"
            rows="3"
          ></v-textarea>
          <v-btn type="submit" class="contact theme-button fill"
            >Contact Us</v-btn
          >
        </v-form>
            </div>
          </div>
        </div>
      </div>
    </div>
 </div>
 


  <!-- <v-app class="my-app contact">
    <v-container class="container py-0">
      <div class="wrapper text-center">
        <v-form method="post">
          <div class="imageBox">
            <img src="src/assets/sq-logo.png" alt="" />
          </div>
          <v-text-field v-model="name" label="Name" name="name" type="text" />
          <v-text-field
            v-model="email"
            label="Email"
            name="email"
            type="email"
          />
          <div class="mobile-field">
            <v-select
              v-model="countryCode"
              :items="countries"
              item-title="code"
              item-value="code"
            ></v-select>
            <v-text-field
              v-model="mobile_no"
              label="Mobile Number"
              name="mobile_no"
              type="mobile_no"
            />
          </div>
          <v-textarea
            v-model="message"
            label="Message"
            name="message"
            rows="3"
          ></v-textarea>
          <v-btn type="submit" class="contact theme-button fill"
            >Contact Us</v-btn
          >
        </v-form>
      </div>
    </v-container>
  </v-app> -->
</template>

<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      countries: [],
      contactus: [],
      user_profile:[],
      countryCode: "+65",
      name: "",
      email: "",
      userData:[],
      mobile_no: "",
      message: "",
    };
  },
  mounted() {
    this.getCountry();
    this.user();
  },
  methods: {
    getCountry() {
      axios
        .get("country")
        .then((response) => {
          this.countries = response.data.data;
          console.log(this.countries);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
        })
    },
    contactUs() {
      axios
        .post("contact-us", {
          name: this.name,
          email: this.email,
          mobile_no: this.mobile_no,
          message: this.message,
        })
        .then((response) => {
          // console.log("Response Data:", response.data);
          this.contactus = response.data;
          this.$router.push("check-your-email");
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
          // console.error("Error fetching data:", error);
        });
    },
    handleSubmit() {
      //
    },
  },
};
</script>
