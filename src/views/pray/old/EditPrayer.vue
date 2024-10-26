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
                  ></textarea>
                  <div class="prayer-actions">
                    <v-btn class="action" @click="updatePrayer"
                      >Save Prayer</v-btn
                    >
                    <v-btn class="action" @click="goToUsersPage()"
                       ><v-img src="/src/assets/Layer.svg"></v-img>Share
                      Prayer</v-btn
                    >
                  </div>
                  <div v-if="names!=[]" style="color: white" >
                  <p>{{names}} ,</p>
                  </div>
                  <div v-else style="color: white" v-for="(v1, index) in prayerDetails" :key="index">
                  <p>{{v1.touser.first_name}} {{v1.touser.last_name}} ,</p>
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
                        :class="
                          cat_id == v1.id ? 'v-slide-group-item--active' : ''
                        "
                        @click="selectCategory(v1.id)"
                      >
                        <div
                          :class="cat_id == v1.id ? 'icon' : 'icon'"
                          :value="index"
                          @click="bibleVerseList(v1.id)"
                        >
                          <img :src="v1.image" />
                          <span class="tooltiptext">{{ v1.title }}</span>
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
                              <v-btn :disabled="back" @click="decrement()">
                                <img
                                  src="/src/assets/back-arrow.svg"
                                  alt="back"
                              /></v-btn>

                              <v-btn
                                :disabled="list"
                                @click="launchVerseList()"
                              >
                                <img
                                  src="/src/assets/Group 1903.svg"
                                  alt="back"
                              /></v-btn>
                              <v-btn :disabled="front" @click="increment()">
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
                    notes</v-btn
                  >
                </div>
                <!-- add note popup  -->
                <div class="mymodal" id="mymodal">
                  <div class="myform">
                    <span class="close-modal" @click="closeMyModal()">+</span>
                    <div class="form">
                      <h2>Add note +</h2>
                      <v-textarea
                        class="padding-left-0"
                        placeholder="Start typing from here..."
                        v-model="note"
                      ></v-textarea>
                      <v-form>
                        <div class="field"></div>
                        <v-btn @click="notePrayer">Add</v-btn>
                      </v-form>
                    </div>
                  </div>
                </div>
                <!-- Edit note popup  -->
                <div class="mymodal mymodal_edit" id="mymodal_edit">
                  <div class="myform">
                    <span class="close-modal" @click="closeEditNoteModal()"
                      >+</span
                    >
                    <div class="form">
                      <h2>Edit note +</h2>
                      <v-textarea
                        class="padding-left-0"
                        placeholder="Start typing from here..."
                        v-model="editnote"
                      ></v-textarea>
                      <v-form>
                        <div class="field"></div>
                        <v-btn @click="updateNotePrayer()">Add</v-btn>
                      </v-form>
                    </div>
                  </div>
                </div>
                <!-- verse listing  -->
                <div class="mymodal" id="versemodal">
                  <div class="myform">
                    <span class="close-modal" @click="closeVerseModal()"
                      >+</span
                    >
                    <div class="verse">
                      <div
                        class="form"
                        v-for="(verse, index) in bible_verse_list"
                        :key="index"
                      >
                        <div>
                          <h3 @click="verseList(verse.bible_verse)">
                            {{ verse.bible_verse }}
                          </h3>
                          <hr />
                          <br />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="prayer notes-comments">
                <div class="column">
                  <v-card>
                    <v-tabs v-model="tabs" bg-color="primary">
                      <v-tab value="one" @click="notePrayerList()">Notes</v-tab>
                      <v-tab value="two" disabled>Comments</v-tab>
                    </v-tabs>

                    <v-window v-model="tabs">
                      <v-window-item value="one">
                        <div
                          class="pray_to_god"
                          v-for="(item, index) in note_list"
                          :key="index"
                        >
                          <div @click="launchEditNoteModal(item.note, item.id)">
                            <header>
                              <h3 class="text-white">
                                {{ item.note }}
                              </h3>
                              <v-btn
                                class="close"
                                @click="deleteNotePrayer(item.id)"
                                >+</v-btn
                              >
                            </header>
                            <p class="datetime">
                              {{ format_date(item.created_at) }}
                            </p>
                          </div>
                        </div>
                      </v-window-item>
                    </v-window>
                  </v-card>
                </div>
              </div>
            </div>
            <router-link :to="{ name: 'pray' }">
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
prayerDetails:[],
    tabs: "",
    index: 0,
    category: "",
    title: "",
    editnote: "",
    bible_verse_data: "",
    category_list: [],
    bible_verse_list: [],
    note_list: [],
    currentDate: "",
    cat_id: "",
    note: "",
    user_ids: [],
    names: [],
    back: true,
    list: true,
    front: true,
  }),
  mounted() {
    if (this.$route.query.edit) {
      this.title = localStorage.getItem("title1");
      this.bible_verse_data = localStorage.getItem("bible_verse_data1");
      this.cat_id = localStorage.getItem("cat_id");
    } else {
      this.title = "";
      localStorage.removeItem("title1");
      this.bible_verse_data = "";
      localStorage.removeItem("bible_verse_data1");
      this.cat_id = "";
      localStorage.removeItem("cat_id");
    }
    this.categoryList();
    this.fetchPrayerData();
    this.notePrayerList();
    this.getCurrentDateTime();
    this.user_ids = this.$route.query.array
      ? JSON.parse(this.$route.query.array)
      : [];
    this.user_ids = this.user_ids != [] ? atob(this.user_ids) : [];
    this.user_ids = this.user_ids != [] ? this.user_ids.split(",") : [];
    this.names = this.$route.query.uids
      ? JSON.parse(this.$route.query.uids)
      : [];
    this.names = this.names != [] ? atob(this.names) : [];
    // alert(this.names);
  },

  methods: {
    goToUsersPage() {
      this.$router.push({
        name: "user",
        query: { section: "edit", prayId: this.$route.params.id },
      });

      localStorage.setItem("title1", this.title);
      localStorage.setItem("bible_verse_data1", this.bible_verse_data);
      localStorage.setItem("cat_id", this.cat_id);
    },

    verseList(verse) {
      this.bible_verse_data = verse;
      this.closeVerseModal();
    },
    closeVerseModal() {
      document.querySelector("#versemodal").classList.remove("open");
    },
    launchVerseList() {
      this.my_prayer_id = this.$route.params.id;
      document.querySelector("#versemodal").classList.add("open");
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
    selectCategory(category) {
      this.cat_id = category;
    },
    categoryList() {
      axios.post("category-list").then((response) => {
        this.category_list = response?.data?.data;
      });
    },
    bibleVerseList(c_id) {
      this.cat_id = c_id;
      axios
        .post("bible-verse-list", {
          category_id: c_id,
        })
        .then((response) => {
          this.bible_verse_list = response?.data?.data;
          var d = this.bible_verse_data
            ? this.bible_verse_data
            : this.bible_verse_list[0].bible_verse;
          this.selectBibleVerse(d);
          if (this.cat_id) {
            this.back = false;
            this.list = false;
            this.front = false;
          }
        });
    },
    decrement() {
      if (this.index <= 0) return;
      this.index--;
      this.selectBibleVerse(this.bible_verse_list[this.index].bible_verse);
    },
    increment() {
      if (
        this.index == this.bible_verse_list.length - 1 ||
        this.index > this.bible_verse_list.length
      )
        this.index = 0;
      this.index++;
      this.selectBibleVerse(this.bible_verse_list[this.index].bible_verse);
    },
    selectBibleVerse(verse) {
      this.bible_verse_data = verse;
    },

    updatePrayer() {
      axios
        .post("add-my-prayer", {
          id: this.$route.params.id,
          title: this.title,
          category: this.cat_id,
          bible_verse: this.bible_verse_data,
          to_user: this.user_ids,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push({ name: "pray" });
        })

        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    fetchPrayerData() {
      // alert(this.$route.params.id);
      axios
        .post("my-prayer-detail", {
          prayer_id: this.$route.params.id,
        })
        .then((response) => {
          console.log(response);
          this.prayerDetails = response.data.data.sharedbyme;
          if (localStorage.getItem("title1")) {
            this.title = localStorage.getItem("title1");
            this.bible_verse_data = localStorage.getItem("bible_verse_data1");
            this.cat_id = localStorage.getItem("cat_id");
            this.bibleVerseList(this.cat_id);
            var data = response.data.data.shared_by_me;
            $.each(data, function (key, value) {
              list.push(key);
            });
          } else {
            this.title = response.data.data.title; // Assuming 'title' is the property to be displayed
            this.bible_verse_data =
              response.data.data.bible_verse == "null"
                ? ""
                : response.data.data.bible_verse;
            this.cat_id = response.data.data.category_id;
            this.bibleVerseList(this.cat_id);
            // var data = response.data.data.shared_by_me;
            // $.each(data, function (key, value) {
            //   list.push(key);
            // });
          }
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
      this.note = "";
      document.querySelector("#mymodal").classList.remove("open");
    },
    // Modal Popup of Edit Note
    launchEditNoteModal(note, note_id) {
      this.editnote = note;
      this.noteId = note_id;
      this.my_prayer_id = this.$route.params.id;
      document.querySelector("#mymodal_edit").classList.add("open");
      window.scrollTo(0, 0);
    },
    closeEditNoteModal() {
      document.querySelector("#mymodal_edit").classList.remove("open");
    },
    // Add Note
    notePrayer() {
      axios
        .post("my-prayer-note", {
          type: "add",
          my_prayer_id: this.my_prayer_id,
          note: this.note,
        })
        .then((response) => {
          this.note = "";
          this.note_list = response?.data?.data;

          this.$toast.success("Note Added Successfully.", {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeMyModal(); // Call closeMyModal here
          this.notePrayerList();
          // this.$router.push({ name: "pray" });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    // update note 
    updateNotePrayer() {
      axios
        .post("my-prayer-note", {
          type: "edit",
          prayer_id: this.my_prayer_id,
          id: this.noteId,
          note: this.editnote,
        })
        .then((response) => {
          this.$toast.success("Note Updated Successfully.", {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.notePrayerList();
          this.closeEditNoteModal();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
  // note prayer list 
    notePrayerList() {
      axios
        .post("my-prayer-note-list", {
          id: this.$route.params.id,
          tabs: "one",
        })
        .then((response) => {
          this.note_list = response?.data?.data.data;
          this.createdDate = response?.data?.data.data.created_at;
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
// delete note 
    deleteNotePrayer(note_id) {
      axios
        .post("my-prayer-note", {
          type: "delete",
          my_prayer_id: this.my_prayer_id,
          id: note_id,
        })
        .then((response) => {
          this.notePrayerList();
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
  },
};
</script>
<style>
.custom-textarea::first-line {
  font-weight: bold;
}
</style>
