<template>
  <v-app>
    <div class="page add-prayer">
      <div class="main" >
        <div
          class="body bg-black-111"
        >
          <div class="content-area">
            <div class="container-sm">
              <h3 class="text-white">
                <p>{{ currentDate }}</p>
              </h3>
              <div class="prayer writeup">
                <div class="column column-50 add-prayer">
                  <textarea
                    rows="5"
                    placeholder="Add a prayer"
                    v-model="title"
                  ></textarea>
                  <div class="prayer-actions">
                    <v-btn class="action" @click="addPrayer()"
                    :disabled="isTitleEmpty" >Save Prayer</v-btn
                    >
                    <v-btn class="action" @click="goToUsersPage()"
                     :disabled="isTitleEmpty" ><v-img src="/src/assets/Layer.svg"></v-img>Share
                      Prayer</v-btn
                    >
                  </div>
                  <div style="color: white">{{ names }}</div>
                </div>
                <div class="column column-50 tabs">
                  <v-card>
                    <v-tabs>
                      <v-tab
                        v-model="tab"
                        v-for="(v1, index) in category_list"
                        bg-color="primary"
                        :class="
                          categoryId == v1.id
                            ? 'v-slide-group-item--active'
                            : ''
                        "
                        :key="index"
                        :tabindex="index"
                        @click="selectCategory(v1.id)"
                      >
                        <div
                          class="icon"
                          :value="index"
                          @click="bibleVerseList(v1.id)"
                        >
                          <img :src="v1.image" />
                          <!-- <span class="tooltiptext">{{v1.name}}</span> -->
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

                  <!-- verse listing  -->

                  <div class="mymodal" id="mymodal">
                    <div class="myform">
                      <span class="close-modal" @click="closeMyModal()">+</span>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import axios from "../../../router/axios.js";

export default {
  data: () => ({
    prayerText: "",
    tab: "",
    tabs: "",
    index: 0,
    category: "",
    title: "",
    bible_verse: "",
    category_list: [],
    bible_verse_list: [],
    bible_verse_data: "",
    categoryId: "",
    currentDate: "",
    currentDay: "",
    back: true,
    user_ids: [],
    names: [],
    front: true,
    list: true,
    // index: 0,
    // verse: "",
  }),
computed: {
    isTitleEmpty() {
      return this.title.length === 0;
    }
  },
  mounted() {
    
    this.title = localStorage.getItem("title");
    this.bible_verse_data = localStorage.getItem("bible_verse_data");
    this.categoryId = localStorage.getItem("categoryId");
    this.categoryList();
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
    this.p_id = this.$route.query.participantArray
    
  },
 
  methods: {
   
 handleSidebarClick() {
      
      // alert('Sidebar clicked!');
    },
    verseList(verse) {
      this.bible_verse_data = verse;
      this.closeMyModal();
    },
    makeUserName(item, index) {
      alert(item);
    },
    launchVerseList() {
      // alert(prayId);
      this.my_prayer_id = this.$route.params.id;
      document.querySelector("#mymodal").classList.add("open");
      window.scrollTo(0, 0);
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
    },

    categoryList() {
      axios.post("category-list").then((response) => {
        this.category_list = response?.data?.data;
      });
    },
    bibleVerseList(c_id) {
      this.categoryId = c_id;
      axios
        .post("bible-verse-list", {
          category_id: c_id,
        })
        .then((response) => {
          this.bible_verse_list = response?.data?.data;
          this.selectBibleVerse(this.bible_verse_list[0].bible_verse);
          if (this.categoryId) {
            this.back = false;
            this.front = false;
            this.list = false;
          }
        });
    },
    goToUsersPage() {
      localStorage.setItem("title", this.title);
      localStorage.setItem("bible_verse_data", this.bible_verse_data);
      localStorage.setItem("categoryId", this.categoryId);
      this.$router.push({ name: "user", params: { section: "add-prayers" } });
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
      this.bible_verse_data = verse; // Update the selected Bible verse
    },
    // Method to select a category
    selectCategory(category) {
      this.categoryId = category; // Update the selected category ID
    },

    addPrayer() {
      console.log(this.user_ids);
      localStorage.setItem("title", "");
      localStorage.setItem("bible_verse_data", "");
      localStorage.setItem("categoryId", "");
      axios
        .post("add-my-prayer", {
          category: this.categoryId,
          title: this.title,
          bible_verse: this.bible_verse_data,
          to_user: this.user_ids,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          console.log(response);

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
    backFuntion() {
      var route = this.$route.path.split("/");
      if (route[1] == "home-page" || route[1] == "pray") {
        this.$router.push({ name: "home-page" });
      } else if (route[1] == "meditate") {
        this.$router.push({ name: "meditate" });
      } else if (route[1] == "fellowship") {
        this.$router.push({ name: "fellowship" });
      } else {
        this.$router.push({ name: "proclaim" });
      }
    },
  },
};
</script>
