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
              <div style="color: white">
                <h1 class="name">Set Your Password</h1>
                <p>Secure your account</p>
                <div>
                  <hr />

                  <div class="password">
                    <v-text-field
                      style="color: black"
                      v-model="old_password"
                      :type="oldshowPassword == true ? 'text' : 'password'"
                      label="Password"
                      name="password"
                      class="padding-left-0"
                    />
                    <button
                      @click="oldshowPassword = !oldshowPassword"
                      type="button"
                    >
                      <img
                        v-if="oldshowPassword"
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
                  <hr />
                </div>
                <div>
                  <span style="padding: 6px"> </span>
                  <hr />
                </div>
                <div>
                  <div class="password">
                    <v-text-field
                      style="color: black"
                      v-model="password"
                      :type="showPassword == true ? 'text' : 'password'"
                      label="Password"
                      name="password"
                      class="padding-left-0"
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
                  <hr />
                </div>
                <div>
                  <div class="password">
                    <v-text-field
                      style="color: black"
                      v-model="password_confirmation"
                      :type="conshowPassword == true ? 'text' : 'password'"
                      label="Password"
                      name="password"
                      class="padding-left-0"
                    />
                    <button
                      @click="conshowPassword = !conshowPassword"
                      type="button"
                    >
                      <img
                        v-if="conshowPassword"
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
                  <hr />
                </div>
              </div>
              <v-btn
                @click="changePassword()"
                :disabled="password.length == 0 && password_confirmation == 0"
                >SAVE</v-btn
              >
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
      old_password: "",
      password: "",
      password_confirmation: "",
      showPassword: false,
      conshowPassword: false,
      oldshowPassword: false,
    };
  },
  methods: {
    changePassword() {
      const formData = new FormData();
      formData.append("old_password", this.old_password);
      formData.append("password", this.password);
      formData.append("password_confirmation", this.password_confirmation);
      axios
        .post("change-password", formData)
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
  mounted() {},
};
</script>
