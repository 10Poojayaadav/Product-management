<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
             
              <div class="prayer-container" v-if="isLoading">
                <div class="loader"></div>
              </div>
              <div v-else class="prayer-container">
                <header>
                  <span>
                    <img src="/src/assets/refresh-arrow.svg" alt="refresh" />
                  </span>
                </header>
                <!-- <div class="prayers">
                  <div class="prayer" :id="faq.id"
                        v-for="(faq, index) in faq_category"
                        :key="index">
                    <div class="group"
                    @click="$router.push({
                        name:'faqDetails',
                        params: { id:faq.id}

                    })" 
                    >
                      <p class="name">{{ faq.name}}</p>
                    </div>                   
                  </div>
                </div> -->

  <div class="prayers">
                  <div class="prayer" :id="faq.id"
                        v-for="(faq, index) in faq_category"
                        :key="index">
                    <div class="group"
                    @click="gotobackpage(faq.id)" 
                    >
                      <p class="name">{{ faq.name}}</p>
                    </div>                   
                  </div>
                </div>








              </div>
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
      faq_category: [],
    };
  },
  methods:{
      faqCategory(){
          axios
          .post("faq-category")
          .then((response)=>{
              console.log(response)
               this.faq_category = response.data.data;
          })
           .catch((error) => {
          console.error(error);
        });
      },
      gotobackpage(f_id){
          this.$router.push({
             name:'faqDetails',
             params:{id:btoa(f_id)} 
          })
      }
      
  },
    mounted() {
    this.faqCategory();
  },
  }
  </script>