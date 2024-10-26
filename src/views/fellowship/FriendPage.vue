<template>
  <v-app>
    <section class="content_section">
      <Header />
      <div>
        <div class="page friends">
          <div class="friends-container">
            <div class="search-friend">
              <div class="bg-white search-area">
                <v-img
                  src="/src/assets/search.png"
                  class="search-icon"
                  height="16"
                ></v-img>
                <input
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
            <div class="listings ">
              <div v-if="L" class="chat-with_loader">
                <div class="loader"></div>
              </div>
              <div v-else-if="user_list.length === 0">
                <p class="text-center text-white">No Record found</p>
              </div>
              <div id="list-view" class="for-scroll" v-on:scroll="loadNextPage($event)" v-else>
                <div class="myfriends"> 
                  <div
                    class="myfriend"
                    v-for="(user, index) in user_list"
                    :key="index"
                  >
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

                      <div>
                        <p class="text-white name">
                          {{ user.first_name }} {{ user.last_name }}
                        </p>
                        <p class="text-white email">{{ user.email }}</p>
                      </div>
                    </div>
                  
                    <div class="actions">
                      <ul>
                        <li class="dis-gray">
                          <v-img src="/src/assets/Group 2231.png"></v-img>
                        </li>
                        <li @click="openChatPage(user.id)">
                          <v-img src="/src/assets/Group 2232.png"></v-img>
                        </li>
                        <li class="dis-gray">
                          <v-img src="/src/assets/Vector 3.png"></v-img>
                        </li>
                      </ul>
                      <button
                        v-if="user.pinrel_count > 0"
                        class="pin-friend-list"
                        @click="$event.preventDefault()"
                      >
                        <img src="/src/assets/Vector.svg" />
                      </button>
                    </div>
                    <div class="manage-friend">
                      <v-btn>
                        <img src="/src/assets/ellipsis-vertical.svg" />
                      </v-btn>
                      <ul class="friend-options">
                        <li  v-if="user.pinrel_count == 0" @click="pinUser(user.id)">
                          <v-img src="/src/assets/pin.png"></v-img>Pin
                        </li>
                        <li  v-else @click="pinUser(user.id)">
                          <v-img src="/src/assets/pin.png"></v-img>Unpin
                        </li>
                        <li @click="launchMyModal(user)">
                          <v-img src="/src/assets/edit.png"></v-img>Edit
                        </li>
                        <li  @click="launchBlockModel(user.id)" >
                          <v-img src="/src/assets/block 1.png" ></v-img>Block
                        </li>
                        <li @click="launchReportModel">
                          <v-img src="/src/assets/report 1.png"></v-img>Report
                        </li>
                      </ul>
                    </div>
                  
                  </div>
                </div>
              </div>
              <!-- <div v-if="loading" class="chat-with_loader"> -->
              <div v-if="loading" class="chat-with_loader height-unset">
                <div class="loader-text bottom-unset">Loading more contacts..</div>
                <div class="loader text-white" ></div>
                </div>
            </div>
          </div>
        </div>
        <!-- friend information popup  -->
        <div class="mymodal edit" id="mymodal">
          <div class="modal modal-edit-frnd">
            <div class="modal-header">
              <v-btn class="close-modal" @click="closeMyModal()">
                <v-img src="/src/assets/close.png"></v-img>
              </v-btn>
              <div class="profile-pic" v-if="profile_pic != null">
                <v-img aspect-ratio="6/3" cover :src="profile_pic"></v-img>
              </div>
              <div class="profile-pic" v-else>
                <h2>
                  {{ first_name.charAt(0).toUpperCase()
                  }}{{ last_name.charAt(0).toUpperCase() }}
                </h2>
              </div>
            </div>
            <h4 class="name text-white text-center">
              {{ first_name ?? "" }}
              {{ last_name ?? "" }}
            </h4>
            <div class="modal-body">
              <div class="info">
                <div class="about">
                  <h4 class="text-white">About</h4>
                  <v-btn @click="editBio = !editBio">
                    <img
                      v-if="!editBio"
                      src="/src/assets/pen-white.png"
                      alt="Pencil"
                    />
                    <img
                      v-else
                      src="/src/assets/checkmark.png"
                      height="15"
                      alt="Checkmark"
                      @click="bioData()"
                    />
                  </v-btn>
                </div>
                <div class="birthday">
                  <div>
                    <img
                      src="/src/assets/cake-white.png"
                      class="mr-2"
                      height="20"
                      alt=""
                    />
                    <p class="text-white">
                      {{ dob }}
                    </p>
                  </div>
                  <div>
                    <img
                      src="/src/assets/work-white.png"
                      class="mr-2"
                      height="20"
                      alt=""
                    />
                    <p v-if="occupation == null" class="text-white">N/A</p>
                    <p v-else class="text-white">{{ occupation }}</p>
                  </div>
                </div>
                <div class="bio">
                  <p v-if="!editBio" class="text-white">{{ bio }}</p>
                  <input v-else type="text"
                   v-model="bio" class="text-white" 
                    @input="limitCharacters" />
                </div>
              </div>
            </div>
            <div class="actions">
              <div>
                <v-btn class="text-white" v-if="is_blocked == 'Already Blocked'"
                  >{{ is_blocked }} {{ first_name }} {{ last_name }}</v-btn
                >
                <v-btn class="text-white" @click="launchBlockModel" v-else
                  ><v-img
                    src="/src/assets/block-white.png"
                    class="mr-2"
                  ></v-img>
                  {{ is_blocked }} {{ first_name }} {{ last_name }}</v-btn
                >
              </div>
              <div>
                <v-btn class="text-white" @click="launchReportModel"
                  ><v-img
                    src="/src/assets/report-white.png"
                    class="mr-2"
                  ></v-img
                  >Report {{ first_name }} {{ last_name }}</v-btn
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- block user pop up -->
      <div class="mymodal block" id="blockmodal">
        <div class="modal block-frnd">
          <div class="modal-header">
            <h2>Block {{ first_name }} {{ last_name }}</h2>
            <v-btn class="close-modal" @click="closeBlockModal()">
              <v-img src="/src/assets/close.png"></v-img>
            </v-btn>
          </div>
          <div class="modal-body">
            <p>
              You are about to block the user. Blocked user will no longer be
              able to pray with you or send you messages.
            </p>
          </div>
          <div class="modal-footer">
            <v-btn @click="closeBlockModal()">Cancel</v-btn>
            <v-btn @click="blocked()">Block</v-btn>
          </div>
        </div>
      </div>
      <!-- report user pop up -->
      <div class="mymodal block" id="reportmodal">
        <div class="modal block-frnd">
          <div class="modal-header">
            <h2>Report {{ first_name }} {{ last_name }}</h2>
            <v-btn class="close-modal" @click="closeReportModal()">
              <v-img src="/src/assets/close.png"></v-img>
            </v-btn>
          </div>
          <div class="modal-body">
            <p>
              You are about to report the user to SQ. Please provide us with
              information on abusive or inappropriate behavior  or content in
              this space below.
            </p>

            <textarea
              v-model="message"
              cols="80"
              class="restyle"
              rows="5"
              placeholder="Type your message here"
            ></textarea>

            <p>
              We will review your request and take necessary action to ensure safety of all our users.
            </p>
          </div>
          <div class="modal-footer">
            <v-btn @click="closeReportModal()">Cancel</v-btn>
            <v-btn @click="reportGroup()">Report</v-btn>
          </div>
        </div>
      </div>
    </section>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
import Header from "../layout/Header.vue";
import $ from "jquery";
export default {
  data() {
    return {
      searchItemPerPage: 10,
      currentSearchPage:1,
      search_last_page:'',
      searchQuery: "",
      user_list: [],
      itemPerPage: 10,
      first_name: "",
      last_name: "",
      currentPage: 1,
      last_page:5,
      message: "",
      profile_pic: "",
      dob: "",
      bid: "",
      occupation: "",
      is_blocked: "",
      reportbtn: false,
      details: "",
      editBio: false,
      bio: "",
      L: true,
      loading:false,
      scroll:false,
    };
  },
  components: {
    Header,
  },
  computed: {
    limitedBio: {
      get() {
        return this.bio; 
      },
      set(value) {
        if (value.length <= 50) {
          this.bio = value;
        }
      }
    },
  },
  methods: {
    loadNextPage(event)
    { 
      if(this.searchQuery)
      {
        var obj = document.getElementById("list-view");
        if ((parseInt(obj.scrollTop+1) >= (obj.scrollHeight - obj.offsetHeight)) 
        && this.currentSearchPage != this.search_last_page  && !this.loading)
        {
          this.loading = true;
          this.currentSearchPage++;
          this.scroll = true;
          this.userList();
        }
      }
      else{
        var obj = document.getElementById("list-view");
        if ((parseInt(obj.scrollTop + 1) >= (obj.scrollHeight - obj.offsetHeight)) 
        && this.currentPage != this.last_page &&  !this.loading)
        {
          this.loading = true;
          console.log(this.last_page, this.currentPage);
          this.currentPage++;
          this.userListPage().then(() => {
          this.loading = false;
          });
        }
        }
    },
    // information model
    clearSearch() {
      $("#search").val("");
      $("#search").focus();
      this.searchQuery = "";
      this.currentPage = 1;
      this.currentSearchPage = 1;
      this.L = true;
      this.userListPage();
    },
    //for limit bio
     limitCharacters(event) {
    if (event.target.value.length > 50) {
      if (!this.bioLengthExceeded) {
        this.$toast.error("Please write bio under 50 characters", {
          duration: 4000, 
          position: "top-right",
          theme: "sugar", 
        });
        this.bioLengthExceeded = true; 
      }
      event.target.value = event.target.value.slice(0, 50);
    } else {
      this.bioLengthExceeded = false;
    }
    },
    launchMyModal(user) {
      axios.post("contact-list-profile?id=" + user.id).then((response) => {
          (this.first_name = response.data.data.first_name),
          (this.last_name = response.data.data.last_name),
          (this.profile_pic = response.data.data.profile_pic),
          (this.dob = response.data.data.dob),
          (this.bid = response.data.data.id),
          (this.occupation = response.data.data.occupation),
          (this.bio = response.data.data.bio),
          (this.is_blocked = response.data.data.block),
          document.querySelector("#mymodal").classList.add("open");
      });
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
    },
    // block model
    launchBlockModel(id) {
      this.bid =id;
      document.querySelector("#blockmodal").classList.add("open");
    },
    closeBlockModal() {
      document.querySelector("#blockmodal").classList.remove("open");
    },
    //  repot model
    launchReportModel() {
      document.querySelector("#reportmodal").classList.add("open");
    },
    closeReportModal() {
      document.querySelector("#reportmodal").classList.remove("open");
      this.message='';
    },
    //user bio
    bioData() {
      this.bio = this.bio.substring(0,50);
      axios
        .post("contact-list-bio", {
          other_id: this.bid,
          bio: this.bio,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    // userAPI
    userList() {
      if(this.scroll == false)
      {
        this.currentSearchPage = 1;
        this.currentPage = 1;
        this.L = true;
      } 
       axios.post("friend-list-all",{search:this.searchQuery ,page: this.currentSearchPage})
       .then((response) =>
        {
          console.log(response);
          setTimeout(() => {
          if(this.currentSearchPage > 1 && this.scroll == true)
          {
            var arr = response.data.data.data;
            this.user_list = this.user_list.concat(arr);
            this.itemPerPage = response.data.data.per_page;
            this.last_page = response.data.data.last_page;
            this.loading = false;
            this.scroll = false;
            console.log("if");
          }
          else
          { 
            this.L =true;
            this.user_list = null;
            this.user_list =response.data.data.data;
            this.itemPerPage = response.data.data.per_page;
            this.last_page = response.data.data.last_page;
            this.L = false;
            console.log("else");
          }  }, 500); 
        });  
    },
    userListPage() {
      axios.post("friend-list-all",{page:this.currentPage}).then((response) => {
        console.log(response.data.data.data);
        if(this.currentPage > 1)
        {
        var arr = response.data.data.data;
        this.user_list = this.user_list.concat(arr);
        this.itemPerPage = response.data.data.per_page;
        this.last_page = response.data.data.last_page;
        this.loading = false;
        }
        else{
        this.L = true;
        this.user_list = response.data.data.data;
        this.itemPerPage = response.data.data.per_page;
        this.last_page = response.data.data.last_page;
        this.L =false;
        }
      });
    },
    openChatPage(userId) {
      this.$router.push({ name: "messages", query: { user_id: btoa(userId) } });
    },
    blocked() {
      axios
        .post("block-user", {
          block_id: this.bid,
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          document.querySelector("#blockmodal").classList.remove("open");
          document.querySelector("#mymodal").classList.remove("open");
          this.userList();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    reportGroup() {
      this.reportbtn = true;
      axios
        .post("report-user", {
          type: "user",
          message: this.message,
          report_id: this.bid,
        })
        .then((response) => {
          // console.log(response);
          this.reportbtn = false;
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.message = "";
          document.querySelector("#reportmodal").classList.remove("open");
        })
        .catch((error) => {
          this.reportbtn = false;
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    pinUser(other_id) {

      axios
        .post("friend-list-pin", {
          other_id: other_id,
        })
        .then((response) => {
          this.currentPage = 1;
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.userList();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
  },
  mounted() {
    this.userListPage();
  },
};
</script>
