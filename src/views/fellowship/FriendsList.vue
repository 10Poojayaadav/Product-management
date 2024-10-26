<template>
  <v-app>
    <div class="_modal create-group users group">
      <div class="modal-content-wrapper">
        <h4 class="text-white text-center mb-4">Add Participants</h4>
        <button class="cross-btn" @click="$emit('close')">+</button>
        <v-btn
          @click="gotToBackPage()"
          :disabled="doneBtn"
          class="done-btn ml-auto mr-0 mb-3"
          >Done</v-btn
        >
        <div class="users-container">
          <div class="search-friend">
            <div class="search-area bg-white">
              <v-img
                src="/src/assets/search.png"
                class="search-icon"
                height="16"
              ></v-img>
              <input
                autocomplete="off"
                v-model="searchQuery"
                type="text"
                @keyup="userList()"
                id="search"
                placeholder="Search a friend"
              />
              <v-btn
                v-if="searchQuery != ''"
                id="clear-search-btn"
                class="clear-search"
                @click="clearSearch"
              >
                <v-img src="/src/assets/cross-fill.png" height="14"></v-img>
              </v-btn>
            </div>
          </div>
          <div v-if="isLoading" class="chat-with_loader">
            <div class="loader"></div>
           </div>
          <div v-else class="friend-listings">
            <div v-if="user_list.length === 0">
              <p style="color: white; text-align: center">No data found</p>
            </div>
            <div v-else class="my_friends" id="list-view-friend" v-on:scroll="loadNextPage($event)">
                <div
                class="user"
                v-for="(user, index) in user_list"
                :key="user.i">
                <div class="profile">
                  <div class="profile-pic" v-if="user.profile_pic != null">
                    <v-img :src="user.profile_pic" alt=""></v-img>
                  </div>
                  <div class="profile-pic" v-else>
                    <h2>
                      {{ user.first_name.charAt(0).toUpperCase()
                      }}{{ user.last_name.charAt(0).toUpperCase() }}
                    </h2>
                  </div>

                  <p class="name">
                    {{ user.first_name }} {{ user.last_name }}
                  </p>
                </div>
                <div>
                  <v-btn :id="`btn-${index}`" class="add">
                    <img :disabled="true"
                      v-if="shareUser.includes('' + user.id + '')"
                      src="/src/assets/tick.png"
                    />
                    <img @click="removeFromArray(user.id)"
                      v-else-if="newUser.includes('' + user.id + '')"
                      src="/src/assets/tick.png"
                    />
                    <img
                      v-else
                      @click="insertInArray(user)"
                      src="/src/assets/add.png"
                    />
                  </v-btn>
                </div>
              </div>
            </div>
           
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
import $ from "jquery";
export default {
  data() {
    return {  
      isLoading:true,
      searchQuery: "",
      user_list: [],
      isClick: false,
      user_ids: [],
      itemPerPage: 20,
      last_page:1,
      currentPage: 1,
      prayId: "",
      shareUser: [],
      newUser:[],
      string: [],
      group_id: "",
      doneBtn:true,
    };
  },
   props:{
    id:{
      type:Number,
      default:()=> null,
    }
  },
  methods: {
     closeFunction(id)
    {
      this.$emit('groupId', id);
    },
    // userAPI
    clearSearch() {
      $("#search").val("");
      $("#search").focus();
      this.searchQuery = "";
    },
    userList() {
      if(this.searchQuery != "")
      {
        axios.post("friend-list-all",{search:this.searchQuery})
        .then((response) => {
          this.user_list = response.data.data.data;
        });
      }
      else
      {
        axios.post("friend-list-all",{search:this.searchQuery,page:this.currentPage})
        .then((response) => {
          this.isLoading = false;
          if(this.currentPage == 1)
          {
            this.user_list = response.data.data.data;
          }
          else
          {
             var arr = response.data.data.data;
             this.user_list = this.user_list.concat(arr);
          }
          this.itemPerPage = response.data.data.per_page;
          this.last_page = response.data.data.last_page;
          console.log("itemPerPage",this.itemPerPage,"last_page",this.last_page);
          console.log("userlist",this.user_list);
        });
      }
    },
    loadNextPage(event)
    { 
      var obj = document.getElementById("list-view-friend");
      if ((parseInt(obj.scrollTop) === (obj.scrollHeight - obj.offsetHeight))
       && this.currentPage != this.last_page)
      {
        this.currentPage++;
        this.userList();
      }
    },
    // to manage the click
    insertInArray(u_id) {
      this.newUser.push("" + u_id.id + "");
      // this.string.push(u_id.first_name + " " + u_id.last_name);
      this.doneBtn = false;
    },
    removeFromArray(u_id) {
      const indexToRemove = this.newUser.indexOf(("" + u_id + ""));
      this.newUser.splice(indexToRemove, 1);
      // const removeString = this.string.indexOf(
      //   u_id.first_name + " " + u_id.last_name
      // );
      // this.string.splice(removeString, 1);
      this.doneBtn = false;
    },
    // for go back to that page where it come from
    gotToBackPage() {
      this.shareUser =this.shareUser.concat(this.newUser);
      axios
        .post("add-member", {
          other_id: this.id,
          user_id: this.shareUser,
        })
        .then((response) => {
          this.closeFunction(this.id);
          this.$toast.success('Group members updated successfully', {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    groupMember() {
      axios
        .post("group-member-list", {
          other_id: this.id,
        })
        .then((response) => {
          this.user_ids = response.data.data;
          this.user_ids.forEach((element) => {
            this.shareUser.push(element.user_id);
          });
          console.log(response.data.data);
        });
    },
    // back button function
    backFuntion() {
      this.$router.push({
        name: "groupInfo",
        query: { group_id: this.$route.query.group_id },
      });
    },
  },

  mounted() {
    this.group_id = this.id;
    this.userList();
    this.groupMember();
  },
};
</script>
