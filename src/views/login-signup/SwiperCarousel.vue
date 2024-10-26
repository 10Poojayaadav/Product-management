<template>
  <swiper
    :modules="modules"
    :slides-per-view="1"
    :space-between="50"
    navigation
    :pagination="{ clickable: true }"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
    <swiper-slide>
      <div class="slide-details text-center">
        <div class="slide-image">
          <img src="src/assets/slide-image.png" />
        </div>
      </div>
    </swiper-slide>
   <!--  <swiper-slide>
      <div class="slide-details text-center">
        <div class="slide-image">
          <img src="src/assets/slide-image.png" />
        </div>
      </div>
    </swiper-slide> -->
    <!-- <swiper-slide>
      <div class="slide-details text-center">
        <div class="slide-image">
          <img src="src/assets/slide-image.png" />
        </div>
      </div>
    </swiper-slide> -->
    ...
  </swiper>
</template>
<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
// import "swiper/css/pagination";
import { Navigation, Pagination } from "swiper/modules";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    const onSwiper = (swiper) => {
      console.log(swiper);
    };
    const onSlideChange = () => {
      console.log("slide change");
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Navigation,
       // Pagination
       ],
    };
  },
};
</script>
