<template>
  <v-container class="container py-0">
    <v-app class="my-app scan-qr">
      <div class="wrapper">
        <h2 class="text-center font-weight-medium dark-49 mb-2">
          Scan your friend's SQ QR code.
        </h2>

        <!-- <p class="text-center mb-5 dark-49">Sign Up/Login to continue</p> -->
        <div v-if="isTablet" class="manage-scanner text-center">
          <StreamBarcodeReader
            @decode="onDecode"
            @loaded="onLoaded"
          ></StreamBarcodeReader>
          <div class="flex upload-file">
            <input type="file" id="file" @change="handleImageChange" />
            <label for="file">Upload Image<span class="plus">+</span></label>
          </div>
        </div>

        <div v-else class="upload-area mt-5 mb-3 px-5">
          <label for="upload-file">
            <p class="upload m-0">
              <img src="/src/assets/upload-image.png" />Upload Image
            </p>
          </label>
          <input
            type="file"
            @change="handleImageChange"
            name="upload-file"
            id="upload-file"
          />
        </div>

        <div align="center" class="my-5">
          <p class="dark-49">Or</p>
        </div>
        <p class="text-center mb-10 dark-49">Enter friend's SQ invite code</p>
        <v-form class="px-5 mb-10">
          <v-text-field
            v-model="inviteCode"
            label="Enter code"
            name="invite-field"
            id="invite-field"
            class="padding-left-0"
            validate-on-blur="no"
            required
          ></v-text-field>
        </v-form>
        <div class="pagination mb-5">
          <span class="active"></span>
          <span></span>
          <span></span>
        </div>
        <div class="navigation">
          <v-btn class="theme-button bordered" :to="{ name: 'SignUp' }"
            >Back</v-btn
          >
          <v-btn class="theme-button fill" @click="checkInviteCode()"
            >Next</v-btn
          >
        </div>
      </div>
    </v-app>
  </v-container>
</template>
<script>
import axios from "../../router/axios.js";
import { StreamBarcodeReader } from "vue-barcode-reader";
import QrScanner from "qr-scanner";
import ToastPlugin from "vue-toast-notification"; //toast
import "vue-toast-notification/dist/theme-bootstrap.css"; //toast
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
    checkInviteCode() {
      axios
        .post("invite-code-sqid", {
          invite_code: this.inviteCode,
        })
        .then((response) => {
          this.data = response.data.data;
          if (response.data.status == true) {
            // alert(response.data.message);
            this.$router.push({
              name: "invite-friend",
              params: { code: btoa(this.inviteCode) },
            });
          }
        })
        .catch((error) => {
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
            // console.log(this.qrCode);
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
