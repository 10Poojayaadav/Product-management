<template>
  <!-- QR Image pPage -->
  <v-app>
    <div class="page qr-image">
      <div class="main">
        <div class="body bg-black-111">
          <div class="content">
            <h1 class="qr-image-heading">Invite to SQ</h1>
            <div class="details">
              <h2>Your SQ QR Code</h2>
              <div class="qr-info">
                <div class="qr-code-image mb-2">
                  <img :src="qrCodeUrl" alt="QR Code" />
                </div>
                <p class="mb-3">
                  Invite your friends to SQ with this QR Code. <br />Use this
                  code to add friends in the SQ Web.
                </p>
                <div class="your-code">
                  <input
                    v-model="qrCodeData"
                    @input="generateQRCode"
                    class="mb-3"
                  />
                  <button class="copy" @click="copyQRCode">
                    <img src="/src/assets/copy.svg" alt="" />
                  </button>
                </div>
                <p
                  class="mt-2 text-uppercase"
                  @click="resetQRCode"
                  style="cursor: pointer"
                >
                  Reset QR and Invite Code
                </p>
              </div>
              <div v-if="showCopyMessage" class="copy-message">
                <b>QR Code copied!</b>
              </div>
              <v-btn class="share-qr-code" @click="downloadQRCode"
                ><img src="/src/assets/download.svg" alt=""
              /></v-btn>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import QRCode from "qrcode";
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      qrCodeData: "",
      qrCodeUrl: "", // Define the qrCodeUrl data property here
      showCopyMessage: false,
    };
  },
  mounted() {
    this.fetchQRCodeData("no"); // Call the method to fetch the QR code data
    // Regenerate the QR code with the updated data
  },

  methods: {
    generateQRCode() {
      QRCode.toDataURL(this.qrCodeData, (error, url) => {
        if (error) {
          console.error(error);
          return;
        }

        this.qrCodeUrl = url;
      });
    },
    resetQRCode() {
      // Reset the QR code data
      this.qrCodeData = "";
      this.qrCodeUrl = "";

      // Fetch new QR code data after resetting
      this.fetchQRCodeData("yes");
    },

    fetchQRCodeData(key) {
      axios
        .post("generate-code-sqid", {
          is_generate: key,
        })
        .then((response) => {
          // Assuming the API response contains the QR code data in response.data
          this.qrCodeData = response?.data?.data;
          this.generateQRCode();
        })
        .catch((error) => {
          console.error("API error:", error);
        });
    },
    downloadQRCode() {
      const link = document.createElement("a");
      link.href = this.qrCodeUrl;
      link.setAttribute("download", "qr_code.png");
      link.click();
    },

    copyQRCode() {
      const textField = document.createElement("textarea");
      textField.innerText = this.qrCodeData;
      document.body.appendChild(textField);
      textField.select();
      document.execCommand("copy");
      textField.remove();
      this.showCopyMessage = true; // Show the message
      setTimeout(() => {
        this.showCopyMessage = false; // Hide the message after 2 seconds (adjust as needed)
      }, 2000);
    },
  },
};
</script>
