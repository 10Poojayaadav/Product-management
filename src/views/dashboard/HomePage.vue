<template>
  <v-app>
    <div class="page pray">
      <div class="main">
        <v-main class="body">
          <div class="content">
            <div class="content-area">
              <div v-if="isLoading" class="loading">
                <div class="loader"></div>
              </div>
              <div v-else class="container-sm">
                <div class="mb-2">
                  <div class="video-image" @click="navigateData(first_row)">
                    <img class="image" :src="first_row.web_card_image" />
                  </div>
                </div>
                <div class="cards">
                  <div class="card-wrapper">
                    <article
                      class="card"
                      v-for="(v1, index) in pray_list"
                      :key="index"
                    >
                      <div class="card-inner" @click="navigateData(v1)">
                        <div class="icon">
                          <img :src="v1.web_card_image" />
                        </div>
                      </div>
                    </article>
                  </div>
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
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      pray_list: [],
      first_row: {},
      isLoading: true,
    };
  },
  mounted() {
    this.prayListing();
  },
  methods: {
    prayListing() {
      axios
        .post("pray-list")
        .then((response) => {
          console.log(response);
          this.pray_list = response.data.data;
          this.first_row = this.pray_list[0];
          this.pray_list.splice(0, 1);
          this.isLoading = false;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
            axios
              .post("logout")
              .then(async (response) => {
                localStorage.removeItem("token");
                await this.$store.dispatch("logout");
                this.$router.push({ name: "login" });
              })
              .catch(async (error) => {
                localStorage.removeItem("token");
                await logout(error);
              });
        });
    },
    navigateData(card) {
      if (card.card_type == "create_prayer") {
        this.$router.push({ name: "add-prayers" });
      } else if (card.card_type == "my_prayer") {
        this.$router.push({
          name: "pray",
          params: { id: card.id, section: "pray" },
        });
      } else if (card.card_type == "answered_prayer") {
        this.$router.push({ name: "ansPrayer" });
      } else if (card.content_type == "slider") {
        this.$router.push({
          name: "slider",
          params: { id: card.id, section: "pray" },
        });
      } else if (card.content_type == "library") {
        this.$router.push({
          name: "library",
          params: { id: card.id, section: "pray" },
        });
      } 
      else if (card.content_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.prayListing(); }
        }
        else {
        if (card.media_type == "audio") {
          this.$router.push({
            name: "audio",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "video") {
          this.$router.push({
            name: "video",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.content_type == "pdf") {
          this.$router.push({
            name: "pdf",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "pray" },
          });
        }
        if (card.media_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.prayListing(); }
         }
      }
    },
  },
};
</script>
