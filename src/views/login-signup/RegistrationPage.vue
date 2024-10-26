<template>
  <v-app class="my-app registration">
    <v-container class="container py-0">
      <div class="wrapper text-center">
        <v-form @submit.prevent="handleSubmit()">
          <h2 class="text-center mb-2">Create SQ Account</h2>
          <p class="text-light mb-3 fs-18">Tell us more about yourself</p>
          <div class="row">
            <v-text-field
              label="First Name"
              name="first-name"
              type="text"
              v-model="first_name"
            />
            <!-- <span>{{ first_name }}</span> -->
            <v-text-field
              label="Last Name"
              name="last-name"
              type="text"
              v-model="last_name"
            />
          </div>
          <div class="row">
            <v-text-field
              label="Email Address"
              name="email"
              type="email"
              v-model="email"
            />
            <div class="v-input mobile-field">
              <v-select
                :items="countries"
                item-title="code"
                item-value="code"
                v-model="country_code"
              ></v-select>
              <v-text-field
                label="Mobile Number"
                name="mobile_no"
                type="text"
                v-model="mobile_no"
              />
            </div>
          </div>

          <div class="row">
            <v-text-field
              label="Birthday"
              name="date"
              type="date"
              class="bday date"
              v-model="dob"
              :max="maxDate"
            />
            <v-select
              :items="communities"
              item-title="name"
              item-value="id"
              label="Community"
              outlined
              v-model="community"
            ></v-select>
          </div>

          <div class="password">
            <v-text-field
              v-model="password"
              :type="showPassword == true ? 'text' : 'password'"
              label="New Password"
              name="password"
            />
            <button @click="showPassword = !showPassword" type="button">
              <img
                v-if="showPassword"
                src="@/assets/view.png"
                alt="Hide Password"
                class="hide"
              />
              <img
                v-else
                src="@/assets/hide.png"
                alt="Show Password"
                class="show"
              />
            </button>
          </div>

          <div class="mb-10">
            <p>By creating an account you agree to</p>
            <a href="#" style="color: #56b8b0; text-decoration: none"
              >Our Terms and Conditions</a
            >
          </div>

          <div class="pagination mb-2">
            <span></span>
            <span></span>
            <span class="active"></span>
          </div>
          <div class="navigation">
            <v-btn
              class="theme-button bordered"
              :to="{
                name: 'invite-friend',
                params: { code: $route.params.code },
              }"
              >Back</v-btn
            >
            <v-btn class="theme-button fill" type="submit">Create</v-btn>
          </div>
        </v-form>
      </div>
    </v-container>
  </v-app>
</template>

<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      showPassword: false,
      communities: [],
      countries: [],
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      dob: "",
      community: "",
      country_code: "+65",
      mobile_no: "",
      invite_code: atob(this.$route.params.code),
      dob: null,
      maxDate: "YYYY-MM-DD",
      //rules
      // firstNameRules: [
      //   (value) => {
      //     if (!value) {
      //       return "First name field is required.";
      //     } else if (!/[^0-9]/.test(value)) {
      //       return "Last name can not contain digits.";
      //     } else if (value?.length < 3) {
      //       return "First name must be at least 3 characters.";
      //     } else if (value?.length > 30) {
      //       return "First name must not be greater than 30 characters.";
      //     } else return true;
      //   },
      // ],
      // lastNameRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Last name field is required.";
      //     } else if (!/[^0-9]/.test(value)) {
      //       return "Last name can not contain digits.";
      //     } else if (value?.length < 3) {
      //       return "Last name must be at least 3 characters.";
      //     } else if (value?.length > 30) {
      //       return "Last name must not be greater than 30 characters.";
      //     } else return true;
      //   },
      // ],
      // emailRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Email field is required.";
      //     } else if (
      //       !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
      //         value
      //       )
      //     ) {
      //       return "Email is not valid.";
      //     } else return true;
      //   },
      // ],
      // passwordRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Password field is required.";
      //     } else if (value?.length < 8 || value?.length > 45) {
      //       return "Password must be between 8 to 45";
      //     } else return true;
      //   },
      // ],
      // dobRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Date of Birth field is required.";
      //     } else return true;
      //   },
      // ],
      // commRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Community field is required.";
      //     } else return true;
      //   },
      // ],
      // mobileRules: [
      //   (value) => {
      //     if (!value) {
      //       return "Mobile number field is required.";
      //     } else if (value?.length < 8 && value?.length > 15) {
      //       return "Mobile number must be between 8 to 15";
      //     } else if (/^(?!0{8})[1-9][0-9]{7}$/.test(value)) {
      //       return "Invalid mobile number format";
      //     } else {
      //       return true;
      //     }
      //   },
      // ],
    };
  },
  computed: {
    customPlaceholder() {
      return "DD/MM/YYYY"; // Set your desired placeholder format here
    },
  },
  
  mounted() {
    this.getCommunity();
    this.getCountry();
  },
  methods: {
    getCommunity() {
      axios
        .get("get-community")
        .then((response) => {
          this.communities = response.data.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    getCountry() {
      axios
        .get("country")
        .then((response) => {
          this.countries = response.data.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },

    handleSubmit() {
      axios
        .post("store-user-detail", {
          invite_code: this.invite_code,
          first_name: this.first_name,
          last_name: this.last_name,
          email: this.email,
          password: this.password,
          dob: this.dob,
          community: this.community,
          country_code: this.country_code,
          mobile_no: this.mobile_no,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.login();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    login() {
      axios
        .post("login", { email: this.email, password: this.password })
        .then(async (response) => {
          if (response.data.data && response.data.data.token) {
            localStorage.setItem("token", response.data.data.token);
            await this.$store.dispatch("login");
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + response.data.data.token;
          }
          this.$router.push({ name: "welcome-screen" });
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
  },
  created() {
    // Set the maximum date to today's date
    const today = new Date();
    const yyyy = today.getFullYear();
    let mm = today.getMonth() + 1; // Months are 0-indexed
    let dd = today.getDate();

    // Add leading zeros to months and days if necessary
    if (mm < 10) {
      mm = "0" + mm;
    }
    if (dd < 10) {
      dd = "0" + dd;
    }

    // Format the date as YYYY-MM-DD
    this.maxDate = `${yyyy}-${mm}-${dd}`;
  },
};
</script>
