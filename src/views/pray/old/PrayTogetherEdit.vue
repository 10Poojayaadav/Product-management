<template>
  <v-app>
    <div class="page add-prayer">
      <div class="main">
        <div class="body">
          <div class="content-area">
            <div class="container-sm">
              <h3 class="text-white">{{ currentDate }}</h3>
          
              <div class="prayer writeup">
                <div class="column column-50 add-prayer">
                  <textarea
                    class="custom-textarea"
                    rows="5"
                    placeholder="Add a prayer"
                    v-model="title"
                    disabled
                  ></textarea>
                  <div class="prayer-actions">
                    <v-btn class="action" disabled>Save Prayer</v-btn>
                    <v-btn class="action"
                      ><v-img src="/src/assets/Layer.svg"></v-img>Share
                      Prayer</v-btn
                    >
                  </div>
                </div>

                <div class="column column-50 tabs">
                  <v-card>
                    <v-tabs>
                      <v-tab
                        v-model="tab"
                        bg-color="primary"
                        v-for="(v1, index) in category_list"
                        :key="index"
                        :tabindex="index"
                         :class="cat_id == v1.id ? 'v-slide-group-item--active' : ''"
                         disabled="true"
                      >
                        <div 
                       :class="cat_id==v1.id ? 'icon': 'icon'"
                         :value="index">
                          <img :src="v1.image" />
                        </div>  
                      </v-tab>
                    </v-tabs>

                    <v-card-text>
                      <v-window v-model="tab">
                        <v-window-item :value="index">
                          <header>
                            <div class="column">
                              <b>BIBLE VERSE</b>
                            </div>
                            <div class="actions">
                              <v-btn @click="decrement()">
                                <img
                                  src="/src/assets/back-arrow.svg"
                                  alt="back"
                              /></v-btn>

                              <v-btn>
                                <img
                                  src="/src/assets/Group 1903.svg"
                                  alt="back"
                              /></v-btn>
                              <v-btn @click="increment()">
                                <img
                                  src="/src/assets/arrow-back.svg"
                                  alt="back"
                              /></v-btn>
                            </div>
                          </header>
                          <article>
                            <p 
                              @click="
                                selectBibleVerse(
                                  bible_verse_list[index]?.bible_verse
                                )
                              "
                            >
                                {{ bible_verse_data }}
                            </p>
                          </article>
                        </v-window-item>
                      </v-window>
                    </v-card-text>
                  </v-card>

                  <v-btn class="action" @click="launchMyModal()"
                    ><v-img src="/src/assets/Group 1967.svg"></v-img>Add
                    comment</v-btn
                  >
                </div>
                <!-- add note popup  -->
                <div class="mymodal" id="mymodal">
                  <div class="myform">
                    <span class="close-modal" @click="closeMyModal()">+</span>
                    <div class="form">
                      <h2>Add comment +</h2>
                      <v-textarea
                        class="padding-left-0"
                        placeholder="Start typing from here..."
                        v-model="comment"
                      ></v-textarea>
                      <v-form>
                        <div class="field"></div>
                        <v-btn @click="commentPrayer">Add</v-btn>
                      </v-form>
                    </div>
                  </div>
                </div>

                <!-- Edit comment popup  -->
                <div class="mymodal mymodal_edit" id="mymodal_edit">
                  <div class="myform">
                    <span class="close-modal" @click="closeEditCommentModal()"
                      >+</span
                    >
                    <div class="form">
                      <h2>Edit comment +</h2>
                      <v-textarea
                        class="padding-left-0"
                        placeholder="Start typing from here..."
                        v-model="editcomment"
                      ></v-textarea>
                      <v-form>
                        <div class="field"></div>
                        <v-btn @click="updateCommentPrayer()">Add</v-btn>
                      </v-form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="prayer notes-comments">
                <div class="column">
                  <v-card>
                    <v-tabs v-model="tabs" bg-color="primary">
                      <v-tab value="two" disabled>Notes</v-tab>
                      <v-tab value="one" @click="commentListPrayerTogether()"
                        >Comments</v-tab
                      >
                    </v-tabs>

                    <v-window v-model="tabs">
                      <v-window-item value="one">
                        <div
                          class="pray_to_god"
                          v-for="(items, index) in comment_list"
                          :key="index"
                          @click="
                            launchEditCommentModal(items)
                          "
                        >
                          <header>
                            <h3 class="text-white">{{ items.comment }}</h3>
                            <!-- <p>{{items.user_id}}</p>
                             <p>{{userData.id}}</p> -->
                          </header>
                          <p class="datetime">
                            {{ format_date(items.created_at) }}
                          </p>
                        </div>
                      </v-window-item>
                    </v-window>
                  </v-card>
                </div>
              </div>
            </div>
            <router-link :to="{ name: 'pray-together' }">
              <button class="back-button-bottom">Back</button></router-link
            >
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import axios from "../../router/axios.js";
import moment from "moment";

export default {
  data: () => ({
    tab: "",
    tabs: "",
    index: 0,
    category: "",
    title: "",
    editcomment: "",
    commentId: "",
    bible_verse_data: "",
    category_list: [],
    currentDate: "",
    bible_verse_list: [],
    comment_list: [],
    userData:[],
    userId:"",
    // index: 0,
    // verse: "",

    editedPrayer: {
      title: [],
    },
  }),
  mounted() {
    this.fetchPrayerData();
    this.categoryList();
    this.commentListPrayerTogether();
    this.getCurrentDateTime();
    this.user();
  },

  methods: {
    user(){
        axios
        .get("user-profile")
        .then((response)=>{
            console.log(response.data.data)
            this.userData=response.data.data;
        })
    },
    getCurrentDateTime() {
      const today = new Date();
      const options = {
        weekday: "short",
        month: "short",
        day: "numeric",
      };

      // Set the current date
      this.currentDate = today.toLocaleDateString(undefined, options);

      // Set the current day
      this.currentDay = today.toLocaleDateString(undefined, {
        weekday: "long",
      });
    },
    categoryList() {
      axios.post("category-list").then((response) => {
        this.category_list = response?.data?.data;
      });
    },
    bibleVerseList(c_id) {
      console.log(c_id);
      axios
        .post("bible-verse-list", {
          category_id: c_id,
        })
        .then((response) => {
          this.bible_verse_list = response?.data?.data;
          // this.getVerse(0);
        });
    },
    decrement() {
      if (this.index <= 0) return;
      this.index--;
    },
    increment() {
      if (
        this.index == this.bible_verse_list.length - 1 ||
        this.index > this.bible_verse_list.length
      )
        this.index = 0;
      this.index++;
    },

    fetchPrayerData() {
    
      axios
        .post("my-prayer-detail", {
          prayer_id: this.$route.params.id,
        })
        .then((response) => {
          this.title = response.data.data.title; 
          this.bible_verse_data = response.data.data.bible_verse;
          this.cat_id = response.data.data.category_id;
        })

        .catch((error) => {
          console.error("Error fetching prayer data:", error);
        });
    },
    // Modal Popup
    launchMyModal() {
      // alert(prayId);
      this.my_prayer_id = this.$route.params.id;
      document.querySelector("#mymodal").classList.add("open");
    },
    closeMyModal() {
      this.comment = "";
      document.querySelector("#mymodal").classList.remove("open");
    },
    // Modal Popup of Edit Comment
    launchEditCommentModal(commentData) {
      this.editcomment = commentData.comment;
      this.commentId = commentData.id;
      this.userId= commentData.user_id;
      this.my_prayer_id = this.$route.params.id;
      // alert(this.userData.id +" a "+this.userId)
      if(this.userData.id==this.userId){
       document.querySelector("#mymodal_edit").classList.add("open");
      }
    },
    closeEditCommentModal() {
      this.comment = "";
      document.querySelector("#mymodal_edit").classList.remove("open");
    },

    // Add Comment
    commentPrayer() {
      axios
        .post("comment", {
          type: "add",
          prayer_id: this.my_prayer_id,
          comment: this.comment,
        })
        .then((response) => {
          this.comment = "";
          this.note_list = response?.data?.data;
          this.$toast.success("Comment Added Successfully.", {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeMyModal();
          // Call closeMyModal here
          this.commentListPrayerTogether();
          this.$router.push({ name: "edit-prayTogether-prayer" });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    updateCommentPrayer() {
      axios
        .post("comment", {
          type: "edit",
          prayer_id: this.my_prayer_id,
          comment_id: this.commentId,
          comment: this.editcomment,
        })
        .then((response) => {
          this.$toast.success("Comment Updated Successfully.", {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.commentListPrayerTogether();
          this.closeEditCommentModal();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
       
     
    },
    commentListPrayerTogether() {
      axios
        .post("comment-list", {
          prayer_id: this.$route.params.id,
          tabs: "one",
        })
        .then((response) => {
           console.log(".................>>>>>>>>>>>>>>>>>>>>>>>>")
          console.log(response);
          this.comment_list = response?.data?.data.data;
          console.log(this.comment_list);
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    format_date(value) {
      if (value) {
        return moment(String(value)).format("ddd, DD MMM YYYY, hh:mm a");
      }
    },
  },
};
</script>
<style>
.custom-textarea::first-line {
  font-weight: bold;
}
</style>
