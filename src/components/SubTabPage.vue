<template>
  <v-card-text class="parent-tab-contents">
    <v-tabs v-model="tab" center-active class="child-tabs">
      <template v-for="(v1, index) in sub_library_list" 
      :key="index">
      <v-tab :value="index" @click="navigateData(v1)" :id="`tab_${index}`">{{ v1.title}}</v-tab>
    </template>
    </v-tabs>
    <v-card-text class="child-tabs-contents">
      <v-window v-model="childtab">
        <v-window-item value="one">
          <div class="tiles"> 
            <div v-if="$route.name == 'pray'" class="tiles-wrapper">
              <article class="tile" v-for="(v2, index1) in tileList"
                :key="index1"  @click="navigateData(v2)" >
                <div class="tile-inner" @click="navigateData(v2)">
                  <v-img :src="v2.banner_image"></v-img>
                </div>
              </article>
            </div>
            <div v-else-if="$route.name == 'meditate'" class="tiles-wrapper">
                <article v-for="(v2, i2) in finalArray" :key="i2" class="tile"> 
                  <div v-for="(v, i) in v2" :key="i" class="tile-inner"  @click="navigateData(v)">
                    <v-img :src="v.banner_image" style="margin-bottom: 9px;"></v-img>
                  </div>
                </article>
            </div>
          </div>
        </v-window-item>
      </v-window>
    </v-card-text>
    <VideoPage v-if="openVideo" :id="v_id" @close="closeVideo"/>
    <Pdf v-if="openPdf" :id="p_id" @close="closePdf"/>
    <AudioPage v-if="openAudio" :id="a_id"  @close="closeAudio"/>
  </v-card-text>
</template>
<script>
import Pdf from "../views/common/Pdf.vue";
import AudioPage from "../views/common/AudioPage.vue";
import VideoPage from "../views/common/VideoPage.vue";
import axios from "../router/axios.js";
export default {
  data(){
    return {
    openPdf:false,
    openAudio:false,
    tab: null,
    childtab: null,
    sub_library_list: [],
    tileList:[],
    openVideo:false,
    finalArray:[],
    };
  },
  components:{
    VideoPage,
    Pdf,
    AudioPage,
  },
  props:{
    id:{
      type:Number,
      default:()=>null,
    }
  },
  mounted() {
    this.prayListing();
  },
  methods: {
    closeVideo(){
       this.openVideo=false;
    },
    closePdf(){
      this.openPdf=false; 
    },
    closeAudio(){
      this.openAudio=false;
    },
    prayListing(){
      axios
        .post("lord-pray-content-list-web", {
          id: this.id,
          type: "pray",
        })
        .then((response) => {
          console.log("############", response);
          this.sub_library_list = response.data.data;
          if(this.$route.name=='meditate'){
//
          }else{
            this.sub_library_list.splice(0,2);
          this.sub_library_list.splice(1,1);
          }
          
          this.navigateData(this.sub_library_list[0]);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    navigateData(card)
    {
       if(card.content_type == 'library'){
        axios
        .post("lord-pray-content-list-web", {
          id: card.id,
          type: "pray",
        })
        .then((response) => {
          this.tileList = response.data.data;
          if(this.$route.name=='meditate'){
            const result = [];
                for (let i = 0; i < this.tileList.length; i += 6) {
                  result.push(this.tileList.slice(i, i + 6));
                }
           this.finalArray = result;
           // console.log(this.finalArray);
          }else{
          this.tileList.splice(0,1);
          }
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
       }
        else {
        if (card.media_type == "audio") {
          this.a_id=card.id;
          this.openAudio=true;
        }
        if (card.media_type == "video") {
         this.v_id=card.id;
         this.openVideo=true;
        }
        if (card.content_type == "pdf" ||card.media_type == "pdf") {
          this.p_id=card.id;
          this.openPdf=true;
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
      
    }
  },
};
</script>