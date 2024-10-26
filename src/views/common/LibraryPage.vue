<template>
  <v-app>
    <div class="page prayer-essentials">
      <div class="main">
        <div class="body">
          <div class="content-area" v-if="isLoading">
            <div class="loader"></div>
          </div>
          <div v-else class="content-area">
            <div class="container-sm">
              <div class="mb-2">
                <div class="video-image" @click="navigateData(first_row)">
                  <img class="image" :src="first_row.banner_image" />
                </div>
              </div>
              <div class="cards">
                <div class="card-wrapper">
                  <article
                    class="card"
                    v-for="(v1, index) in library_list"
                    :key="index"
                  >
                    <div
                      v-if="v1.banner_image"
                      class="card-inner"
                      style="--clr: #2aace2"
                      @click="navigateData(v1)"
                    >
                      <div class="icon">
                        <img :src="v1.banner_image" />
                      </div>
                    </div>
                  </article>
                </div>
              </div>
            </div>
            <span class="back-button-bottom" @click="backFuntion()">Back</span>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      library_list: [],
      first_row: [],
      isLoading: true,
    };
  },
  mounted() {

    this.library(this.$route.params.id);
  },

  methods: {
    library(id) {
      this.isLoading = true;
      axios
        .post("lord-pray-content-list", {
          id: id,
          type: "pray",
        })
        .then((response) => {
          console.log(response);
          this.library_list = response.data.data.data;
          if (this.library_list[0].banner_image == null) {
            this.first_row = this.library_list[0];
            console.log(this.first_row);
            this.library_list.splice(0, 1);
            this.library_list.splice(1, 1);
            this.isLoading = false;
          } else {
            this.first_row = this.library_list[0];
            console.log(this.first_row);
            this.library_list.splice(0, 1);
            this.isLoading = false;
          }
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    backFuntion() {
      this.$router.go(-1);
      // var route = this.$route.path.split("/");
      // if (route[1] == "home-page" || route[1] == "pray") {
      //   this.$router.push({ name: "home-page" });
      // } else if (route[1] == "meditate") {
      //   this.$router.push({ name: "meditate" });
      // } else if (route[1] == "fellowship") {
      //   this.$router.push({ name: "fellowship" });
      // } else {
      //   this.$router.push({ name: "proclaim" });
      // }
    },
    navigateData(card) {
      this.library_list = [];
      this.first_row = [];
      if (card.content_type == "hyperlink") {
        if(card.media!=null){ window.open(card.media,'_blank'); }
        else {  this.library(this.$route.params.id); }
      } else if (card.content_type == "library") {
        this.library(card.id);
        this.$router.push({
          name: "library",
          params: { id: card.id, section: "meditate" },
        });
        this.library(card.id);
      } else if (card.content_type == "slider") {
        this.$router.push({
          name: "slider",
          params: { id: card.id, section: "meditate" },
        });
      } else {
        if (card.media_type == "audio") {
          this.$router.push({
            name: "audio",
            params: { id: card.id, section: "meditate" },
          });
        }
        if (card.media_type == "video") {
          this.$router.push({
            name: "video",
            params: { id: card.id, section: "meditate" },
          });
        }
        if (card.media_type == "pdf") {
           this.$router.push({
            name: "pdf",
            params: { id: card.id, section: "meditate" },
          });
        }
        if (card.media_type == "slider") {
          this.$router.push({
            name: "slider",
            params: { id: card.id, section: "meditate" },
          });
        }
         if (card.media_type == "hyperlink") {
           if(card.media!=null){ window.open(card.media,'_blank'); }
           else {  this.library(this.$route.params.id); }
        }
      }
    },
  },
};
</script>
