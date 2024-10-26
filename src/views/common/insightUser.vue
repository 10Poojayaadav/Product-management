<template>
  <div class="page users">
    <div class="main">
      <div class="body">
        <div class="container-sm">
          <div class="users-container">
            <div class="search-pray">
              <input
                v-model="searchQuery"
                type="search"
                placeholder="Search a friend"
              />
            </div>
            <div class="listings">
              <!-- <div v-if="paginatedList.length === 0">
                <p style="color: white; text-align: center">No data found</p>
              </div> 
              <div v-else>-->
                <div class="user"  v-for="(user, index) in user_list" :key="index">
                  <div v-if="media">
                    <div v-if="user.type=='user'">
                         <div class="profile"  @click="sendMedia(user.other_id, user.type)">
                            <div class="profile-pic" v-if="user.other_data.profile_pic != null">
                                 <v-img :src="user.other_data.profile_pic" alt=""></v-img>
                            </div>
                            <div class="profile-pic" v-else>
                            <h2>
                                {{ user.other_data.first_name.charAt(0).toUpperCase()
                                }}{{ user.other_data.last_name.charAt(0).toUpperCase() }}
                            </h2>
                              </div>
                            <p class="name">{{user.other_data.first_name}} {{user.other_data.last_name}}</p>
                         </div>

                    </div>
                     <div v-else>
                         <div class="profile" @click="sendMedia(user.group_data.group_id, user.type)">
                            <div class="profile-pic" v-if="user.group_data.profile_pic != null">
                                 <v-img :src="user.group_data.profile_pic" alt=""></v-img>
                            </div>
                            <div class="profile-pic" v-else>
                            <h2>
                                {{ user.group_data.name.charAt(0).toUpperCase()
                                }}
                            </h2>
                              </div>
                            <p class="name">{{user.group_data.name}}</p>
                         </div>
                      </div>
                  </div>
                  <div v-else>
                      <div v-if="user.type=='user'">
                           <div class="profile"  @click="forwordMessage(user.other_id)">
                              <div class="profile-pic" v-if="user.other_data.profile_pic != null">
                                   <v-img :src="user.other_data.profile_pic" alt=""></v-img>
                              </div>
                              <div class="profile-pic" v-else>
                              <h2>
                                  {{ user.other_data.first_name.charAt(0).toUpperCase()
                                  }}{{ user.other_data.last_name.charAt(0).toUpperCase() }}
                              </h2>
                                </div>
                              <p class="name">{{user.other_data.first_name}} {{user.other_data.last_name}}</p>
                           </div>

                      </div>
                       <div v-else>
                           <div class="profile" @click="forwordMessage(user.group_data.group_id)">
                              <div class="profile-pic" v-if="user.group_data.profile_pic != null">
                                   <v-img :src="user.group_data.profile_pic" alt=""></v-img>
                              </div>
                              <div class="profile-pic" v-else>
                              <h2>
                                  {{ user.group_data.name.charAt(0).toUpperCase()
                                  }}
                              </h2>
                                </div>
                              <p class="name">{{user.group_data.name}}</p>
                           </div>
                        </div>
                  </div>
                </div>
                <div class="pagination" style="color: #fff">
                  <button @click="prevPage" :disabled="currentPage === 1">
                    <img src="/src/assets/back-arrow.svg" />
                    <img src="/src/assets/back-arrow.svg" /> Previous
                  </button>
                  <span>{{ currentPage }}</span>
                  <button @click="nextPage" :disabled="currentPage == totalPages">
                    Next <img src="/src/assets/arrow-back.svg" />
                    <img src="/src/assets/arrow-back.svg" />
                  </button>
                </div>
            </div>
          </div>
        </div>
        <button class="back-button-bottom" @click="backFuntion()">Back</button>
      </div>
    </div> 
  </div>
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      searchQuery: "",
      user_list: [],
      isClick: false,
      itemPerPage: 5,
      currentPage: 1,
      user_id:"",
      message:[],
      array:[],
      media:false,
      note:"",
      totalPages:""
    };
  },
//   computed: {
//     filteredList() {
//       let filteredList = this.user_list;
//       const query = this.searchQuery.toLowerCase();
//       // return this.filteredList.filter((user) =>
//       //   user.first_name.toLowerCase().includes(query) ||
//       //     user.last_name.toLowerCase().includes(query)
//       // );
//       filteredList = filteredList.filter((user) =>
//         user.first_name.toLowerCase().includes(query) ||
//           user.last_name.toLowerCase().includes(query)
//       );
//       return filteredList;
//     },
//     totalPages() {
//       return Math.ceil(this.filteredList.length / this.itemPerPage);
//     },
//     paginatedList() {
//       const start = (this.currentPage - 1) * this.itemPerPage;
//       const end = start + this.itemPerPage;
//       return this.filteredList.slice(start, end);
//     },
//   },
  methods: {
    // userAPI
     userchatList() {
      axios.post("user-chat-list").then((response) => {
        console.log(response);
        this.user_list = response.data.data.data;
      });
    },
    forwordMessage(id){
      this.array.push(id);
        axios
        .post("user-forward-chat",{
            other_id:this.array,
            chat_data_id:this.message,
        })
        .then((response)=>{
            console.log(response);

            this.$router.push({name:'messages',query:{user_id:id}})
             
             this.$toast.success(response.data.message, {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
              theme: "sugar", // Toast theme
            });
        })
        .catch((error) => {    
            console.error("Error fetching data:", error);
          });
    },
    sendMedia(id, type)
    {
      if(type == 'user') {
        this.$router.push({ path : 'messages', query:{ user_id:id, media_id:this.$route.query.media_id}});
      }
      else if(type == 'group') {
        this.$router.push({ path : 'messages', query:{ group_id:id, media_id:this.$route.query.media_id}});
      }
    },
    // for pagination 
    // prevPage() {
    //   if (this.currentPage > 1) {
    //     this.currentPage--;
    //   }
    // },
    // nextPage() {
    //   if (this.currentPage < this.totalPages) {
    //     this.currentPage++;
    //   }
    // },
  },
  mounted() {
    if(this.$route.query.media_id)
    {
      this.media = true;
      this.userchatList();
    }
    else 
    { 
      this.media = false;
      this.userchatList();
      this.message.push(this.$route.query.m_id);
     }
    
  },
};
</script>
