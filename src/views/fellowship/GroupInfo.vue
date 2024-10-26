
<template>
  <div class="_modal create-group group">
     <div v-if="isLoading" class="chat-with_loader">
            <div class="loader"></div>
      </div>
    <div v-else class="modal-content-wrapper" @click="closeMenu($event)">
      <button class="cross-btn" @click="$emit('closePopup')">+</button>
      <h4 class="text-white text-center mb-4">Group info</h4>
      <div class="content">
          <div class="user-pic mb-2">
            <v-img
              v-if="group_details.group?.image"
              :width="100"
              cover
              :src="group_details.group.image"
            ></v-img>
            <h2 v-else class="text-h3 text-center text-white">
              {{
                group_details.group
                  ? splitName(group_details.group.name)
                  : "N/A"
              }}
            </h2>
          </div>
          <h1 class="text-center text-white name">
            {{ group_details.group ? group_details.group.name : "N/A" }}
          </h1>
          <div class="info" style="white-space: pre-line;">
            <p class="text-white text-center" v-if="group_details.group">
              {{
                group_details.group.description == "null"
                  ? ""
                  : group_details.group.description
              }}
            </p>
          </div>
        </div>
        <div class="participants-container">
          <div class="count">
            <p class="text-white">PARTICIPANTS ({{ memberList.length }})</p>
            <div class="group-actions" id="group-actions-id">
              <v-btn @click="groupActions">
                <img src="/src/assets/ellipsis.svg" />
              </v-btn>
              <ul v-if="group_actions" class="actions">
                <li @click="clearGroupChat(group_details.group.group_id)">
                  Clear Chat
                </li>
                <li @click="editPopup()">
                  Edit
                </li>
                <li class="text-maroon" @click="launchExitModel()">
                  Exit Group
                </li>
                <li class="text-maroon" @click="launchReportModelGroup(group_details.group.name)">
                  Report Group
                </li>
              </ul>
            </div>
          </div>
          <header>
            <!-- <router-link
              class="text-sky"
              :to="{
                name: 'friend-list',
                query: {
                  group_id: this.$route.query.group_id,
                },
              }"
            >
              Add Participants
            </router-link> -->
            <p @click="friendOpenPopup()" class="row font-weight-700 text-sky" style="cursor:pointer;">
            <img src="/src/assets/plus-blue.png" class="mr-2">
            Add Participants</p>
            <div class="align-image" @click="friendOpenPopup()">
              <img  src="/src/assets/chevron-right.png" />
            </div>
          </header>
          <div class="participants">
            <div
              class="participant"
              v-for="(member, index) in memberList"
              :key="index"
            >
              <div class="participant-inner">
                <div class="flex w-100">
                  <div
                    class="profile-pic"
                    v-if="member.user_data?.profile_pic != null"
                  >
                    <v-img
                      :src="member.user_data.profile_pic"
                      alt=""
                    ></v-img>
                  </div>
                  <div class="profile-pic" v-else>
                    <h2 class="v-img">
                      {{
                        member.user_data?.first_name.charAt(0).toUpperCase()
                      }}{{
                        member.user_data?.last_name.charAt(0).toUpperCase()
                      }}
                    </h2>
                  </div>
                  <p class="row">
                    <span class="text-white name">
                      {{ member.user_data?.first_name }}
                      {{ member.user_data?.last_name }}
                    </span>
                    <span class="text-white">{{
                      member.groupadmin_count == 1 ? "Admin" : ""
                    }}</span>
                  </p>
                </div>
                <div
                  class="group-actions"
                  v-if="member.user_id != my_user_id"
                >
                  <v-btn @click="hideShowMenu(member.user_data?.id)" :id="`user-action-btn-${member.user_data?.id}`">
                    <img src="/src/assets/chevron-right.png" />
                  </v-btn>
                  <ul class="actions" :id="`grpinfo-option-id-${member.user_data?.id}`" style="display: none;">
                    <li @click="launchMyModal(member.user_data)">
                      View Profile
                    </li>
                    <li class="text-maroon" v-if="admin_id.includes(my_user_id) && member.groupadmin_count == 1" @click="removeAdmin(member.user_data.id)">
                      Remove From Admin
                    </li>
                    <li v-else-if="admin_id.includes(my_user_id)" @click="makeAdmin(member.user_data.id)">
                      Make As Admin
                    </li>
                    
                    <li
                      class="text-maroon"
                      v-if="admin_id.includes(my_user_id)"
                      @click="removeFromGroup(member.user_data?.id)"
                    >
                      Remove From Group
                    </li>
                    <li @click="cancel()">Cancel</li>
                  </ul>
                </div>
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
                <v-img
                  v-if="profile_pic != null"
                  aspect-ratio="6/3"
                  cover
                  :src="profile_pic"
                ></v-img>
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
                  <input
                    v-else
                    type="text"
                    v-model="bio"
                  @input="limitCharacters"/>
                </div>
              </div>
            </div>
            <div class="action-modals">
              <div>
                <v-btn
                  class="text-white"
                  v-if="is_blocked == 'Already Blocked'"
                  ><v-img src="/src/assets/block-white.png" class="mr-2"></v-img>
                  {{ is_blocked }} {{ first_name }} {{ last_name }}</v-btn
                >
                <v-btn class="text-white" @click="launchBlockModel" v-else
                  ><v-img src="/src/assets/block-white.png" class="mr-2"></v-img>
                  {{ is_blocked }} {{ first_name }} {{ last_name }}</v-btn
                >
              </div>
              <div>
                <v-btn class="text-white" @click="launchReportModel"
                  ><v-img src="/src/assets/report-white.png" class="mr-2"></v-img
                  >Report {{ first_name }} {{ last_name }}</v-btn
                >
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
                You are about to block the user. Blocked user will no
                longer be able to pray with you or send you messages.
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
                You are about to report the user to SQ. Please provide us
                with information on abusive or inappropriate behavior or
                content in this space below.
              </p>

              <textarea
                v-model="message"
                cols="80"
                rows="5"
                class="restyle"
                placeholder="Type your message here"
              ></textarea>

              <p>
                We will review your request and take necessary action to ensure safety of all our users.
              </p>
            </div>
            <div class="modal-footer">
              <v-btn @click="closeReportModal()"
                >Cancel</v-btn
              >
              <v-btn @click="reportUser()">Report</v-btn>
            </div>
          </div>
        </div>
        <!-- report group pop up -->
        <div class="mymodal block" id="reportmodalgroup">
          <div class="modal block-frnd">
            <div class="modal-header">
              <h2>Report {{ rgname }}</h2>
              <v-btn class="close-modal" @click="closeReportModalGroup()">
                <v-img src="/src/assets/close.png"></v-img>
              </v-btn>
            </div>
            <div class="modal-body">
              <div class="user-profile mb-5"></div>
              <p>
                You are about to report this group to SQ. Please provide us
                with information on abusive or inappropriate behaviour or
                content in the space below.
              </p>
              <textarea
                v-model="messageGroup"
                cols="80"
                class="restyle"
                rows="5"
                placeholder="Type your message here"
              ></textarea>
              <p>We will review your request and will take  the appropriate actions within 24 hours.</p>
            </div>
            <div class="modal-footer">
              <v-btn @click="closeReportModalGroup()"
                >Cancel</v-btn
              >
              <v-btn @click="reportGroup()">Report</v-btn>
            </div>

            <!-- <div class="modal-footer">
              <v-btn :disabled="reportbtn" @click="reportGroup()"
                >Report Group</v-btn
              >
            </div> -->
          </div>
        </div>
        <!-- exit group pop up -->
        <div class="mymodal" id="exitmodal">
          <div class="modal exit-group">
            <h2>Exit Group?</h2>
            <div class="modal-body">
              <p>Are you sure you want to leave this group?</p>
            </div>
            <div class="modal-footer">
              <v-btn @click="closeExitModal()">Cancel</v-btn>
              <v-btn @click="exitGroupChat(group_details.group.group_id)"
                >OK</v-btn
              >
            </div>
          </div>
        </div>
    </div>
  </div>
</template>
<script>
import axios from "../../router/axios.js";
import Header from "../layout/Header.vue";
import $ from "jquery";
import logOut from "@/middleware/logout";

export default {
  // props: ["name", "description",
  // ],
  data() {
    return {
      isLoading:false,
      group_details: [],
      memberList: [],
      group_actions: false,
      user_actions: '',
      first_name: "",
      last_name: "",
      profile_pic: "",
      message: "",
      messageGroup: "",
      bid: "",
      dob: "",
      occupation: "",
      is_blocked: "",
      reportbtn: false,
      my_user_id: "",
      admin_id: [],
      bio: "",
      editBio: false,
      isLoading: true,
      rgname:"",
      adminCount:'',
   
    };
  },
   props: {
    id:{
        type:String,
        default:() => '',
    }  
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
    }
  },
  mounted() {
    this.authUser();
    this.groupInfo();
    this.groupMember();
  },
  methods: {
    editPopup(){
      $('.options').hide();
      event.preventDefault();
      event.stopPropagation();
      this.$emit('edit', this.id); 
    },
     friendOpenPopup() {
    //  this.adminCount = memberList.groupadmin_count;
      this.$emit('addParticipant', this.id); 
    },
    cancel(){
      $('.actions').hide();
    },
    hideShowMenu(id){
      this.user_actions = id;
      $('#grpinfo-option-id-'+id).toggle();
    },

    closeMenu(event) {
      if(this.user_actions !=''){
        if(!document.getElementById("user-action-btn-"+this.user_actions).contains(event.target)){
          $('#grpinfo-option-id-'+this.user_actions).hide();
          this.user_actions = '';
          event.preventDefault();
        }
      }
      if (
        this.group_actions &&
        !document.getElementById("group-actions-id").contains(event.target)
      ) {
        this.group_actions = false;
        event.preventDefault();
      }
    },
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


    launchReportModelGroup(groupName) {
      $('.options').hide();
      event.preventDefault();
      event.stopPropagation();
      this.rgname = groupName;
      document.querySelector("#reportmodalgroup").classList.add("open");
    },
    closeReportModalGroup() {
      document.querySelector("#reportmodalgroup").classList.remove("open");
      this.messageGroup='';
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
    groupActions() {
      this.group_actions = !this.group_actions;
    },
    userActions() {
      this.user_actions = !this.user_actions;
    },

    groupInfo() {
      axios
        .post("group-view", { other_id: this.id})
        .then((response) => {
          this.group_details = response.data.data;
          console.log("a",this.group_details);
          this.isLoading = false;
        })
        .catch((error) => {
          if (error.response) {
            console.error("Server Error:", error.response.data);
          } else if (error.request) {
            console.error("No response received:", error.request);
          } else {
            console.error("Error setting up the request:", error.message);
          }
        });
    },
    groupMember() {
      axios
        .post("group-member-list", {
          other_id: this.id,
        })
        .then((response) => {
          console.log("memberlist", response);
          this.memberList = response.data.data;
          var array = [];
          $.each(this.memberList, function (key, value) {
            if (value.groupadmin_count > 0) {
              array.push(value.user_id);
            }
          });
          this.admin_id = array;
        
        });   
    },
    clearGroupChat() {
      $('.options').hide();
      event.preventDefault();
      event.stopPropagation();
      axios
        .post("group-clear-chat", {
          id: this.id,
        })
        .then((response) => {
          // console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    muteGroupChat() {
      axios
        .post("user-mute-chat-list", {
          other_id: this.id,
        })
        .then((response) => {
          // console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    exitGroupChat() {
      axios
        .post("user-leave-group", {
          other_id: this.id,
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$emit('closePopup');
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    launchMyModal(user) {
      $('.actions').hide();
      axios.post("contact-list-profile?id=" + user.id).then((response) => {
        console.log(response.data.data);
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
    bioData() {
      this.bio = this.bio.substring(0,50);
      axios
        .post("contact-list-bio", {
          other_id: this.bid,
          bio: this.bio,
        })
        .then((response) => {
          console.log("++++++++++++++++++", response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
    },
    // block model
    launchBlockModel() {
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
    // exit model
    launchExitModel() {
      $('.options').hide();
      event.preventDefault();
      event.stopPropagation();
      document.querySelector("#exitmodal").classList.add("open");
    },
    closeExitModal() {
      document.querySelector("#exitmodal").classList.remove("open");
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
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    reportUser() {
      this.reportbtn = true;
      axios
        .post("report-user", {
          type: "user",
          message: this.message,
          report_id: this.bid,
        })
        .then((response) => {
          console.log(response);
          this.reportbtn = false;
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeReportModal();
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
    reportGroup() {
      this.reportbtn = true;
      axios
        .post("report-user", {
          type: "group",
          message: this.messageGroup,
          report_id: this.id,
        })
        .then((response) => {
          console.log(response);
          this.reportbtn = false;
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeReportModalGroup();
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
    authUser() {
      axios
        .get("user-profile")
        .then((response) => {
          this.my_user_id = "" + response.data.data.id + "";
        })
        .catch(async (error) => {
          if (error.response.data.status_code == 401) {
            await logOut(error);
          }
        });
    },
    makeAdmin(id) {
      $('.options').hide();
      axios
        .post("assign-admin", {
          group_id: this.id,
          user_id: id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.groupMember();
        })
        .catch(async (error) => {
          if (error.response.data.status_code == 401) {
            await logOut(error);
          }
        });
    },
    removeAdmin(id) {
      $('.options').hide();
      axios
        .post("admin-role", {
          group_id: this.id,
          user_id: id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.groupMember();
        })
        .catch(async (error) => {
          if (error.response.data.status_code == 401) {
            await logOut(error);
          }
        });
    },
    removeFromGroup(id) {
      $('.options').hide();
      axios
        .post("remove-member", {
          group_id: this.id,
          user_id: id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.groupMember();
        })
        .catch(async (error) => {
          if (error.response.data.status_code == 401) {
            await logOut(error);
          }
        });
    },
  },
  components: {
    Header,
  },
};
</script>
