<template>
  <div class="video">
    <div class="_modal create-group group">
        <div class="content-area" v-if="isLoading">
          <div class="loader"></div>
        </div>
        <div v-else class="content-area">
          <div class="video-container">
        <button class="cross-btn text-black" @click="$emit('close')">+</button>
            <div class="group-actions" id="video-actions-id">
              <v-btn @click="groupActions" style="background: inherit; left: 5px;">
                <img src="/src/assets/ellipsis-vertical 1.svg" />
              </v-btn>
              <ul v-if="group_actions" class="actions">
                <li @click="launchMyModal()">
                  Add Note
                </li>
                <li  @click="listnote(id)">
                 Notes
                </li>
                <li @click="openList(id)">
                 Share
                </li>
                <li @click="favourite(id)" v-if="videoInfo.favourite==true">Favourite</li>
                <!-- <li @click="favourite(id)" v-else>Un-favourite</li> -->
                <li @click="InsightOpen">
                 Insight
                </li>
              </ul>
            </div>
            <video
              :src="video_path"
              type="video/mp4"
              controlslist="nodownload"
              controls
              autoplay
              muted
            ></video>
          </div>
        </div>
          <!-- add note popup  -->
          <div class="mymodal" id="mymodal">
            <div class="myform">
              <span class="close-modal text-white" @click="closeMyModal()">+</span>
              <div class="form">
                <h2 class="text-white text-center">Add Note</h2>
                <p class="text-white"> {{ currentDate }}</p>
                <textarea
                class="note-textarea"
                  rows="8"
                  placeholder="Start typing from here..."
                  v-model="note"
                ></textarea>
                <v-form>
                  <div class="field"></div>
                  <center>
                    <v-btn class="note-btn" @click="addNote">Save</v-btn>
                  </center>
                </v-form>
              </div>
            </div>
          </div>
          <!-- note listing  -->
          <div class="mymodal" id="listmodal">
            <div class="myform">
              <span class="close-modal text-white" @click="closelistModal()">+</span>
              <div class="form" >
                <h2 class="text-white text-center">Notes</h2>
                <div class="note-container">
                  <div class="note-list" v-for="(list, index) in note_list" :key="index">
                    <h4>{{ dateChange(list.created_at) }}</h4>
                    <p>{{ list.note }}</p>
                    <div class="buttons">
                      <button class="text-white" @click="deleteNote(list.id)"><img src="/src/assets/delete.svg"/></button>
                      <button class="text-white" @click="launchEditModal(list)"><img src="/src/assets/edit.png"/></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mymodal" id="editmodal">
            <div class="myform">
              <span class="close-modal text-white" @click="closeEditModal()">+</span>
              <div class="form">
                <h2 class="text-white text-center">Edit Note</h2>
                <p class="text-white"> {{ currentDate }}</p>
                <textarea
                  class="note-textarea"
                  rows="8"
                  placeholder="Start typing from here..."
                  v-model="currentNote.note"
                ></textarea>
                <v-form>
                  <div class="field"></div>
                  <center>
                    <v-btn class="note-btn" @click="editNote">Save</v-btn>
                  </center>
                </v-form>
              </div>
            </div>
          </div>
          <div class="mymodal" id="Insightmodal">
            <div class="myform">
              <span class="close-modal text-white" @click="closeInsight()">+</span>
              <div class="form text-white">
                <h2 class="text-white text-center">Insight</h2>
                <div class="note-container" v-html="videoInfo.insight"></div>
              </div>
            </div>
          </div>
          <ChatList v-if="openChatList" @close="closeList" :id="v_id" :string="string" />
      </div>
  </div>
      
</template>
<script>
import axios from "../../router/axios.js";
import ChatList from "../chat/ChatList.vue";

export default {
  data() {
    return {
      videoInfo:[],
      formattedDate: null,
      openChatList:false,
      currentDate: null,
      group_actions: false,
      video_path: "",
      lord_prayer_id:'',
      isLoading: true,
      string:'media',
      note:'',
      note_list:[],
       insightContent: "",
       currentNote: {
        id: null,
        note: ""
      }
    };
  },
  components:{
    ChatList,
  },
  props:{
    id:{
      type:Number,
      default:()=>null,
    }
  },
  mounted() {
    this.videoContent();
    this.getCurrentDate();
  },
  methods: {
    dateChange(inputDate){
      const date = new Date(inputDate);
      const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        weekday: 'long'
      };
      return date.toLocaleString('en-US', options);
    },
    getCurrentDate() {
      const currentDate = new Date();
      const formattedDate = `${currentDate.getFullYear()}-${currentDate.getMonth() + 1}-${currentDate.getDate()}`;
      this.currentDate = formattedDate;
    },
     favourite(id){
      axios
      .post("favourite-data",{
        media_id: id,
      })
      .then((response)=>{
        this.group_actions = false;
         this.videoContent();
       this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          }); 
      })
    },
    openList(id){
      this.v_id=id;
      this.openChatList=true;
    },
    closeList(){
      this.openChatList=false;
    },
     groupActions() {
      this.group_actions = !this.group_actions;
    },
    launchMyModal() {
      this.lord_prayer_id = this.id ;
      document.querySelector("#mymodal").classList.add("open");
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
      this.note = "";
    },
    launchlistModal() {
      this.lord_prayer_id = this.id ;
      document.querySelector("#listmodal").classList.add("open");
    },
    closelistModal() {
      document.querySelector("#listmodal").classList.remove("open");
      this.note = "";
    },
     launchEditModal(note) {
      this.currentNote.id = note.id;
      this.currentNote.note = note.note;
      document.querySelector("#editmodal").classList.add("open");
    },
     closeEditModal() {
      document.querySelector("#editmodal").classList.remove("open");
      this.currentNote = { id: null, note: "" };
    },
    InsightOpen() {
      this.lord_prayer_id = this.id;
      document.querySelector("#Insightmodal").classList.add("open");
    },
    closeInsight() {
      document.querySelector("#Insightmodal").classList.remove("open");
    },
    addNote() {
      axios
        .post("lord-prayer-add-note", {
          type: "add",
          lord_prayer_id: this.id,
          note: this.note,
          
        })
        .then((response) => {
          this.note = "";
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeMyModal(); 
        })
        .catch((error) => {
          this.note = "";
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
     deleteNote(id) {
      this.nodeId = id;
      axios
        .post("lord-prayer-add-note", {
          type: "delete",
          id: this.nodeId,
        })
        .then((response) => {
          this.listnote();
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.closeMyModal();
        })
        .catch((error) => {
          this.note = "";
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
    listnote(){
      axios
      .post("lord-pray-content-note",{
        id: this.id,
      })
      .then((response)=>{
       console.log(response);
       this.note_list= response.data.data;
       this.launchlistModal();
      });
    },
     editNote() {
      axios
        .post("lord-prayer-add-note", {
          type: "edit",
          id: this.currentNote.id,
          note: this.currentNote.note,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.closeEditModal();
          this.listnote();
        })
        .catch((error) => {
          this.currentNote.note = "";
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
    videoContent() {
      axios.get("get-content/" + this.id).then((response) => {
        this.videoInfo = response.data.data;
        this.video_path = response.data.data.media;
        this.isLoading = false;
      });
    },
    backFuntion() {
      this.$router.go(-1);
    },
  },
};
</script>
