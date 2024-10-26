<template>
  <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body">
          <div class="content">
            <Header />
            <div class="page new-library">
              <div class="back_button" @click="backPage">
                <h3>
                  <img
                    src="/src/assets/back-arrow.svg"
                    style="margin-right: 10px"
                  />{{ title }}
                </h3>
              </div>
              <div class="library-container">
                <div
                  v-for="(v1, index) in library_list"
                  :key="index"
                  class="library"
                >
                  <h2 class="library-title">{{ v1.title }}</h2>
                  <!-- Vue Swiper -->
                  <swiper
                    ref="librarySwiper"
                    :slides-per-view="getSlidesPerView(v1)"
                    :space-between="getSpaceBetween(v1)"
                    :centeredSlides="false"
                    :autoplay="{
                      delay: 2500,
                      disableOnInteraction: false,
                    }"
                    :loop="false"
                    @slideChange="onSlideChange"
                    class="librarySwiper"
                  >
                    <swiper-slide
                      v-for="(item, itemIndex) in sortedForChilds(v1.for_childs)"
                      :key="itemIndex"
                      @click="navigateData(item)"
                    >
                      <img
                        :src="item.banner_image"
                        alt=""
                        :data-size="v1.image_type"
                      />
                    </swiper-slide>
                  </swiper>
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
import Header from "../layout/Header.vue";
import axios from "../../router/axios.js";
import Pdf from "../common/Pdf.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";

export default {
  data: () => ({
    v1: {
      for_childs: [],
    },
    pdfPage: false,
    openVideo: false,
    tab: null,
    childtab: null,
    first_row: {},
    pray_list: [],
    grid_list: [],
    library_list: [],
    type: "pray-list",
    cardId: null,
    pdfId: null,
    title: "",
    grid: false,
    gridTitle: "",
    libraryPage: 1,
    library_last_page: "",
    section:null,
  }),
  components: {
    Header,
    Pdf,
    Swiper,
    SwiperSlide,
  },
  mounted() {
    this.title = this.$route.query.title;
    this.section = this.$route.params.section;
    this.library();
     const previousUrl = localStorage.getItem('previousUrl');
    if (previousUrl) {
      const url = new URL(previousUrl);
      const id = url.searchParams.get('id');
    }
  },
  props: {
    id: {
      type: String,
      default: () => "",
    },
  },
  methods: {
    saveCurrentUrl() {
    localStorage.setItem('previoussUrl', window.location.href);
  },
    backPage() {
      if(this.section == "pray"){
        this.$router.push({
          name:"pray"
        })
      }
     else if (this.section == "proclaim"){
      this.$router.push({
          name:"proclaim"
        })
     }
     else if (this.section == "meditate"){
      this.$router.push({
          name:"meditate"
        })
     }
   else{
      this.$router.push({
          name:"fellowship"
        })
     }

    },
    closeVideo() {
      this.openVideo = false;
    },
    library() {
      axios
        .post("lord-pray-content-list", {
          id: this.$route.params.id,
          type: "pray",
          page: this.libraryPage,
        })
        .then((response) => {
          console.log(response, "??????????????????????/////////");
          this.library_list = response.data.data.data;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card, source) {
      console.log("item", card);
      this.saveCurrentUrl();
      if (card.content_type == "library") {
        this.gridTitle = card.title;
        this.grid = true;
        this.pdfPage = false;
        this.sliderPage = false;
        this.cardId = card.id;
        if (card.library_type == "old" || card.library_type == null) {
          this.newLibrary = false;
           this.$router.push({
            name: "library",
            params: { id: card.library_id, section: "new-library" },
            query: { title:card.title},
          });
          // axios
          //   .post("lord-pray-content-list", {
          //     id: card.id,
          //     type: "pray",
          //   })
          //   .then((response) => {
          //     console.log("response>>>>>>>>>>>>>>>>>>.", response);
          //     this.grid_list = response.data.data.data;
          //     this.first_row = this.grid_list[0];
          //     this.grid_list.splice(0, 1);
          //   })
          //   .catch((error) => {
          //     console.error("Error fetching data:", error);
          //   });
        } else {
          this.newLibrary = true;
          this.sliderPage = false;
          axios
            .post("lord-pray-content-list", {
              id: card.id,
              type: "pray",
            })
            .then((response) => {
              console.log("response", response);
              this.new_library_list = response.data.data.data;
            })
            .catch((error) => {
              console.error("Error fetching data:", error);
            });
        }
      } else {
        if (card.media_type == "audio") {
          this.a_id = card.id;
          this.openAudio = true;
        }
        if (card.media_type == "video") {
          this.v_id = card.id;
          this.openVideo = true;
        }
        if (card.media_type == "image") {
          console.log("Image media:", card.media);
          this.media = card.media;
          this.imagePage = true;
          this.imageId = card.id;
          this.grid = true;
          this.pdfPage = false;
        }
        if (card.media_type == "pdf" && source === "l_list") {
          this.grid = true;
          this.pdfPage = true;
          this.pdfId = card.id;
        }
        if (card.media_type == "pdf" && source !== "l_list") {
          this.pdfId = card.id;
          this.$router.push({
            name: "library2",
            params: { id: card.library_id, section:this.section },
            query: { pdfId: this.pdfId, gridTitle: card.library2.title },
          });
        }
        if (card.content_type == "slider") {
          this.sliderId = card.id;
          this.$router.push({
            name: "library2",
            params: { id: card.library_id, section: this.section},
            query: { sliderId: this.sliderId, gridTitle: card.library2.title },
          });
        }
        if (card.media_type == "hyperlink") {
          if (card.media != null) {
            window.open(card.media, "_blank");
          }
        }
      }
    },
    sortedForChilds(for_childs) {
      return for_childs.sort((a, b) => a.priority - b.priority);
    },
    getSlidesPerView(library) {
      return 3;
    },
    getSpaceBetween(library) {
      return 20;
    },
    onSlideChange(swiper) {
      console.log(swiper);
      const currentIndex = swiper.realIndex;
      const totalSlides = swiper.slides.length - swiper.params.slidesPerView;
      console.log(currentIndex, totalSlides );
      if (currentIndex >= totalSlides - 10) {
        swiper.allowSlideNext = false;
      } else {
        swiper.allowSlideNext = true;
      }
      if (currentIndex === 0) {
        swiper.allowSlideNext = true;
        swiper.allowSlidePrev = false;
      } else {
        swiper.allowSlidePrev = true;
      }
    },

  },
};
</script>
