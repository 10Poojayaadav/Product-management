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
                <div class="prayers">
                  <div class="prayer" :id="faq.id"
                        v-for="(faq, index) in faq_details"
                        :key="index">
                    <div class="group"
                    @click="$router.push('faqDeatils')" 
                    >
                      <p class="name">{{ faq.question}}</p>
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
    
      faq_details:[]
    };
  },
  methods:{
  
      faqDetails(){
        axios
        // .post("faqs?category_id="+this.$route.params.id)
         .post("faqs?category_id="+atob(this.$route.params.id))
        .then((response)=>{
           console.log(response.data.data.faq)
               this.faq_details = response.data.data.faq; 
        })
      }
  },
    mounted() {
    // this.faqCategory();
     this.faqDetails();
  },
  }
  </script>