<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="wrapper text-center text-white">
                <div class="user-profile mb-5">
                  <div v-if="userData.profile_pic != null">
                    <v-img
                    v-if="userData.profile_pic != null"
                    :width="30"
                    aspect-ratio="6/3"
                    cover
                    :src="userData.profile_pic"
                  ></v-img>
                  </div>
                  <div v-else>
                    <h2 >

                    </h2>
                    <!-- <h2>
                    {{ userData.first_name.charAt(0).toUpperCase()
                    }}{{ userData.last_name.charAt(0).toUpperCase() }}
                            </h2> -->
                  </div>
                  

                </div>

                <h4 class="text-center text-h4 name mb-4">
                  {{ userData.first_name ?? "" }}
                  {{ userData.last_name ?? "" }}
                </h4>
                <div class="info">
                  <p class="mb-2">
                   SQID:-  <strong>{{ userData.sqid }}</strong>
                  </p>
                  <v-btn @click="$router.push({
                    name:'edit-profile',
                    params:{section:'setting'}})"
                  >Edit</v-btn>
                </div> 
              </div>
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
                  <div class="prayer">
                    <div class="group" @click="$router.push('notification')">
                      <p class="name">Notification</p>
                    </div>
                 
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push('askSq')" >
                      <p class="name">Ask SQ</p>
                    </div>                    
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push('faq')">
                      <p class="name">FAQ</p>
                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                      <a href="http://13.214.111.235/public/contents/terms" target="_blank"><p class="name">Terms of Use</p></a> 
                    </div>
                  </div>
                   <div class="prayer">
                    <div class="group">
                     <a href="https://spiritualquotient.org/give/issue" target="_blank"><p class="name">Submit an Issue</p></a> 
                    </div>
                  </div>
                  <div class="prayer">
                    <div class="group" @click="$router.push('privacy')">
                      <p class="name">Privacy Setting</p>
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
      
      type: "",
      userData:[],
    };
  },
  methods:{
    user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
        })
    },
  },
  mounted(){
    this.user();
  }
};
</script>