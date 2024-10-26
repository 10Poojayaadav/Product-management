<template>
  <v-app>
    <!-- <section class="content_section"> -->
    <section class="_modal create-group users group">
      <div class="modal-content-wrapper">
        <button class="cross-btn" @click="$emit('close')">+</button>
      <div class="page users">
        <div class="friends-container">
          <div class="users-container">
            <div class="search-friend">
              <div class="bg-white search-area">
                <img
                  src="/src/assets/search.png"
                  class="search-icon"
                  height="16"
                >
                <input
                  v-model="searchQuery"
                  type="text"
                  id="search"
                  @keyup="userSearchList()"
                  placeholder="Search a friend"
                />
                <v-btn
                  v-if="searchQuery != ''"
                  id="clear-search-btn"
                  class="clear-search"
                  @click="clearSearch">
                  <v-img src="/src/assets/cross-fill.png" height="14"></v-img>
                </v-btn>
              </div>
            </div>
            <div class="listings">
              <div v-if="isLoading" class="1chat-with_loader">
                <div class="loader"></div>
              </div>
              <div v-else>
                <div v-if="user_list.length === 0">
                  <p style="color: white; text-align: center">No data found</p>
                </div>
                <div id="list-view-forword" class="my_friends" v-on:scroll="loadNextPage($event)" v-else>
                  <div class="user" v-for="(user, index) in user_list" :key="index" style="cursor:pointer;">
                    <div>
                      <div v-if="user.group_id" @click="forwordMessage(user.group_id)">
                        <div  class="profile" >
                          <div
                            class="profile-pic"
                            v-if="user.profile_pic != null"
                          >
                            <v-img
                              :src="user.profile_pic"
                              alt=""
                            ></v-img>
                          </div>
                          <div class="profile-pic" v-else>
                            <h2>
                              {{ splitName(user?.name)}}
                            </h2>
                          </div>
                          <p class="name">{{ user?.name }}</p>
                        </div>
                      </div>
                      <div v-else @click="forwordMessage(user.id)">
                        <div class="profile">
                          <div
                            class="profile-pic"
                            v-if="user.profile_pic != null"
                          >
                            <v-img
                              :src="user.profile_pic"
                              alt=""
                            ></v-img>
                          </div>
                          <div class="profile-pic" v-else>
                            <h2>
                              {{
                                user.first_name
                                  .charAt(0)
                                  .toUpperCase()
                              }}{{
                                user.last_name.charAt(0).toUpperCase()
                              }}
                            </h2>
                          </div>
                          <p class="name">
                            {{ user.first_name }}
                            {{ user.last_name }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="loaders" v-if="Momo">
                    <div class="loader-text">Loading more contacts..</div>
                    <div class="loader bottom text-white" ></div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
      </div>
    </section>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
import $ from "jquery";
export default {
  data() {
    return {
      searchQuery: "",
      user_list: [],
      isClick: false,
      isLoading:true,
      itemPerPage: 10,
      currentPage: 1,
      searchItemPerPage: 10,
      currentSearchPage:1,
      search_last_page:'',
      last_page:'',
      user_id: "",
      message: [],
      array: [],
      Momo:false,
      scroll:false,
    };
  },
  props:{
    id:{
      type:Number,
      default:()=>null,
    },    
    string:{
      type:String,
      default:()=>'',
    }
  },
  methods: {
    clearSearch() {
      this.isLoading=true;
      $("#clear-search-btn").val("");
      $("#clear-search-btn").focus();
      this.searchQuery = "";
      this.currentPage = 1;
      this.currentSearchPage = 1;
      this.userChatList();
    },
    // for pagination
    loadNextPage(event)
    { 
      if(this.searchQuery)
      {
        var obj = document.getElementById("list-view-forword");
        if ((parseInt(obj.scrollTop+1) >= (obj.scrollHeight - obj.offsetHeight)) 
        && this.currentSearchPage != this.search_last_page  && !this.Momo)
        {
          this.Momo = true;
          this.currentSearchPage++;
          this.scroll = true;
          this.userSearchList();
        }
      }
      else
      {
        
        var obj = document.getElementById("list-view-forword");
        console.log(obj.scrollTop , (obj.scrollHeight - obj.offsetHeight), this.last_page, this.currentPage);
        if ((parseInt(obj.scrollTop+1) >= (obj.scrollHeight - obj.offsetHeight)) 
        && this.currentPage != this.last_page  && !this.Momo)
        {
          this.Momo = true;
          this.currentPage++;
          this.userChatList().then(() => {
          this.Momo = false;
          });
        }
      }
    },
    backMessagePage() {
      this.$router.push({
        name: "messages",
        query: { user_id: btoa(this.$route.query.other) },
      });
    },
    // userAPI
    userChatList()
    { 
      axios.post("user-chat-list-all",{search:this.searchQuery, page: this.currentPage}).then((response) => {
        if(this.currentPage > 1)
        {
          var users =response.data.data.user.data;
          this.itemPerPage = response.data.data.user.per_page;
          this.last_page = response.data.data.user.last_page;
          this.user_list = this.user_list.concat(users);
          this.Momo = false;
        }
        else
        {
          this.isLoading = true;
          this.user_list  = response.data.data.group;
          var users =response.data.data.user.data;
          this.itemPerPage = response.data.data.user.per_page;
          this.last_page = response.data.data.user.last_page;
          this.user_list = this.user_list.concat(users);
          this.isLoading = false;
        }
        
      });
    },
    userSearchList()
    {
        if(this.scroll == false)
      {
        this.currentSearchPage = 1;
        this.currentPage = 1;
        this.isLoading = true;
      }
        axios.post("user-chat-list-all",{search:this.searchQuery, page: this.currentSearchPage})
      .then((response) => {
        setTimeout(() => {
        if (this.currentSearchPage > 1 && this.scroll == true) {
          var users = response.data.data.user.data;
          this.searchItemPerPage = response.data.data.user.per_page;
          this.search_last_page = response.data.data.user.last_page;
          this.user_list = this.user_list.concat(users);
          this.Momo = false;
          this.scroll = false;
          console.log("if");
        } else {
          this.isLoading = true;
          this.user_list = null;
          this.user_list = response.data.data.group;
          var users = response.data.data.user.data;
          this.user_list = this.user_list.concat(users);
          this.itemPerPage = response.data.data.user.per_page;
          this.last_page = response.data.data.user.last_page;
          this.isLoading = false;
          console.log("else");
        }}, 500); 
    });
  },
    splitName(groupName) {
      var group = groupName?.split(" ");
      if (group.length == 1) {
        return (
          group[0].charAt(0)?.toUpperCase() +
          "" +
          group[0].charAt(1)?.toUpperCase()
        );
      } else {
        let a = group[0]?.charAt(0);
        let b = group[1]?.charAt(0);
        return a?.toUpperCase() + "" + b?.toUpperCase();
      }
    },
    
    forwordMessage(u_id) {
      if(this.string=='media'){
        if(u_id % 1 != 0){
          this.$router.push({
          name:'messages',
          query:{'media_id':btoa(this.id),'group_id':btoa(u_id)}
        });
        }
        else{
          this.$router.push({
          name:'messages',
          query:{'media_id':btoa(this.id),'user_id':btoa(u_id)}
        });
        }
      }
      else{
        var array = [];
        array.push(u_id);
      axios
        .post("user-forward-chat", {
          other_id: array,
          chat_data_id: this.message,
        })
        .then((response) => {
          this.$emit('close');
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
      }
      
    },
  },
  mounted() {
    this.userChatList();
    this.message.push(this.id);
  },
};
</script>
