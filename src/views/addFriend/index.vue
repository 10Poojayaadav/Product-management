<template>
  <v-app>
    <div class="page add-friend">
      <div class="main">
        <v-main class="body bg-black">
          <div class="content">
            <div class="container-sm">
              <div class="content-area text-center">
                <div class="mb-5">
                  <h1 class="text-style">Scan your friend's SQ QR code.</h1>
                  <p class="text-white">Sign Up/Login to continue</p>
                </div>
                <div v-if="isTable" class="manage-scanner text-center">
                  <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded">
                  </StreamBarcodeReader>
                  <div class="flex upload-file">
                    <input type="file" id="file" @change="handleImageChange" />
                    <label for="file"
                      >Upload image<span class="plus">+</span></label
                    >
                  </div>
                </div>
                <div v-else class="upload-area mb-3">
                  <label for="upload-file">
                    <p class="upload">
                      <img src="/src/assets/upload-image.png" />Upload image
                    </p>
                  </label>
                  <input
                    type="file"
                    @change="handleImageChange"
                    name="upload-file"
                    id="upload-file"
                  />
                </div>

                <div
                  align="center"
                  style="margin-top: 30px; margin-bottom: 20px"
                >
                  <p class="text-white">Or</p>
                  <br />
                  <p class="text-white">Enter friend's SQ invite code</p>
                </div>

                <v-form autocomplete="off">
                  <v-text-field
                    v-model="inviteCode"
                    label="Enter code"
                    name="invite-field"
                    @keypress="typedData($event)"
                    class="padding-left-0"
                    id="invite-field"
                    validate-on-blur="no"
                    required
                    style="color: white; background-color: black"
                  ></v-text-field>
                </v-form>

                <div class="pagination mb-5">
                  <span class="active"></span>
                  <span></span>
                </div>

                <div class="navigation">
                  <v-btn class="theme-button fill" @click="checkInviteCode()"
                    >Next</v-btn
                  >
                </div>
              </div>
            </div>
          </div>
        </v-main>
      </div>
    </div>
  </v-app>
</template>
<script>
import { StreamBarcodeReader } from "vue-barcode-reader";
import QrScanner from "qr-scanner";
import axios from "../../router/axios.js";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

export default {
  data() {
    return {
      inviteCode: "",
      device: "computer",
      data: null,
      qrCode: null,
    };
  },
  components: {
    StreamBarcodeReader,
  },
  methods: {
    typedData(event){
      if (event.keyCode == 13) {
        this.checkInviteCode();
        event.stopPropagation();
        event.preventDefault();
      }
    },
    checkInviteCode() {
      axios
        .post("invite-code-sqid", {
          invite_code: this.inviteCode,
        })
        .then((response) => {
          console.log(response)
          this.data = response.data.data;
          if (response.data.status == true) {
            axios
              .post("send-request", {
                invite_code: this.inviteCode,
                other_id:this.data.id,
              })
              .then((response) => {
                console.log(response)
                this.data = response.data;
                this.$router.push({
                  name: "friend",
                  params: { code: btoa(this.inviteCode) },
                });
                
              })
              .catch((error) => {
                 this.$toast.error(error.response.data.message, {
                  duration: 4000, // Duration in milliseconds
                  position: "top-right", // Toast position
                  theme: "sugar", // Toast theme
                });
              });
          }
        })
        .catch((error) => {
          console.log(error)
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    handleImageChange(event) {
      const file = event.target.files[0];
      var input = event.target;
      var image = document.getElementById("user-image");
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          image.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
      if (file) {
        const reader = new FileReader();
        reader.onload = async () => {
          try {
            const result = await QrScanner.scanImage(reader.result);
            this.inviteCode = result;
          } catch (error) {
            console.error("Error scanning QR code:", error);
            this.inviteCode = null;
          }
        };
        reader.readAsDataURL(file);
      }
    },
    onDecode(text) {
      this.inviteCode = text;
      this.checkInviteCode();
    },
    onLoaded() {
      console.log(`Ready to start scanning barcodes`);
    },
  },
  computed: {
    isLaptop() {
      return window.matchMedia("(min-width: 1024px)").matches;
    },
    isTablet() {
      return window.matchMedia("(min-width: 768px) and (max-width: 1023px)")
        .matches;
    },
  },
  created() {
    this.inviteCode = this.$route.query.code
      ? atob(this.$route.query.code)
      : "";
  },
};
</script>
