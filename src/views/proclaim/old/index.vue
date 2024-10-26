<template>
  <!-- proclaim page -->
  <v-app>
    <div class="page pray">
      <div class="main">
        <v-main class="body">
          <div class="content">
            <div class="content-area" v-if="isLoading">
              <div class="loader"></div>
            </div>
            <div v-else class="content-area">
              <div class="container-sm mb-5">
                <div class="video-image" @click="navigateData(first_row)">
                  <img class="image" :src="first_row.card_image" />
                </div>
              </div>

              <div class="container-sm cards">
                <div class="card-wrapper">
                  <article
                    class="card"
                    v-for="(v1, index) in proclaim_list"
                    :key="index"
                  >
                    <div class="card-inner" @click="navigateData(v1)">
                      <div class="icon">
                        <img :src="v1.card_image" />
                      </div>
                    </div>
                  </article>
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
      proclaim_list: [],
      first_row: {},
      isLoading: true,
    };
  },
  mounted() {
    this.proclaim();
  },
  methods: {
    proclaim() {
      axios
        .post("proclaim-list")
        .then((response) => {
          this.proclaim_list = response.data.data;
          this.first_row = this.proclaim_list[0];
          this.proclaim_list.splice(0, 1);
          this.isLoading = false;
          console.log(this.first_row, this.proclaim_list);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card) {
      if (card.content_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.proclaim(); }
        }
     else if (card.content_type == "library") {
        this.$router.push({
          name: "library",
          params: { id: card.id, section: "proclaim" },
        });
      } else if (card.content_type == "slider") {
        this.$router.push({
          name: "slider",
          params: { id: card.id, section: "proclaim" },
        });
      } else {
        if (card.media_type == "audio") {
          this.$router.push({
            name: "audio",
            params: { id: card.id, section: "proclaim" },
          });
        }
        if (card.media_type == "video") {
          this.$router.push({
            name: "video",
            params: { id: card.id, section: "proclaim" },
          });
        }
        if (card.media_type == "pdf") {
          this.$router.push({ name: "pdf", params: { id: card.id, section:'proclaim' } });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "proclaim" },
          });
        }
        if (card.media_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.proclaim(); }
        }
      }
    },
  },
};
</script>
