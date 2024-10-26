<template>
  <!-- myprayer page -->
  <v-app>
    <div class="page pray pray-inbox">
      <div class="main">
        <v-main class="body bg-black-111">
          <div class="inbox">
            <div class="container">
              <aside class="pray-sidebar">
                <div class="menu-wrapper">
                  <header>
                    <ul class="top menus">
                      <li
                        :class="$route.name == 'pray' ? 'active' : ''"
                        @click="$router.push({ name: 'pray' })"
                      >
                        <span
                          >
                           <!-- <v-img src="/src/assets/Mask Groupp.png"></v-img> -->
                          <img src="/src/assets/MaskGroupp.png" alt="Messages" />
                          </span>
                        <span>My Prayers</span>
                      </li>
                      <li
                        :class="$route.name == 'pray-together' ? 'active' : ''"
                        @click="$router.push({ name: 'pray-together' })"
                      >
                        <span
                          ><img src="/src/assets/MaskGroup.png" alt="Messages"
                        /></span>
                        <span>Pray Together</span>
                      </li>
                    </ul>
                  </header>
                  <ul class="menus">
                    <li
                      :class="category_id == '' ? 'active' : ''"
                      @click="prayerList('')"
                    >
                      <span></span>
                      <span>All</span>
                    </li>
                    <li
                      v-for="(category, ind) in categories"
                      :class="category_id == category.id ? 'active' : ''"
                      :key="ind"
                    >
                      <span @click="prayerList(category.id)">
                        <span><img :src="category.image" /></span>
                        <span>{{ category.name }}</span></span
                      >
                    </li>
                  </ul>
                </div>
              </aside>
              <div class="content">
                <div class="search-pray">
                  <input
                    v-model="searchQuery"
                    type="search"
                    placeholder="Search in My Prayers"
                  />
                  <v-btn class="apply-filter" @click="launchFilterOptions"
                    ><img src="/src/assets/filter.svg" alt="Filter"
                  /></v-btn>
                </div>
                <div class="prayer-container" v-if="isLoading">
                  <div class="loader"></div>
                </div>
                <div v-else class="prayer-container">
                  <header>
                    <span>
                      <v-btn @click="refreshData()" class="refresh">
                        <img src="/src/assets/refresh-arrow.svg" alt="refresh" />
                      </v-btn>
                    </span>
                  </header>

                  <div class="prayers">
                    <div v-if="paginatedList?.length === 0">
                      <p style="color: white; text-align: center">
                        No data found
                      </p>
                    </div>
                    <div v-else>    
                      <div
                        class="prayer"
                        :id="pray.id"
                        v-for="(pray, index) in prayer_list"
                        :key="index"
                      >
                        <div class="group">
                          <router-link
                            :to="{
                              name: 'edit-prayer',
                              params: { id: pray.id },
                            }"
                          >
                            <p class="name">{{ pray.title }}</p>
                          </router-link>
                        </div>
                        <!-- <p class="date">{{ formatDate(pray.created_at) }}</p> -->
                         <!-- LIST action  -->
                        <!-- <ul class="actions">
                          <li>
                            <v-btn
                              class="tooltip"
                              @click="launchMyModal(pray.id)"
                              ><img src="/src/assets/Group 1967.svg" />
                              <span class="tooltiptext">Note</span>
                            </v-btn>
                          </li>
                          <li class="pinned-status pinned tooltip">
                            <v-btn @click="pinPrayer(pray.id)">
                              <img
                                v-if="pray.bookmarkrel_count > 0"
                                src="/src/assets/office-push-pin.png"
                              />
                              <img
                                v-else
                                src="/src/assets/office-push-pin.svg"
                              />
                              <span class="tooltiptext">Pin</span>
                            </v-btn>
                          </li>
                          <li>
                            <v-btn
                              class="tooltip"
                              @click="answerPrayer(pray.id)"
                            >
                              <img src="/src/assets/healthcare (2).svg" />
                              <span class="tooltiptext">Answered</span></v-btn
                            >
                          </li>
                          <li>
                            <v-btn class="tooltip" @click="shared(pray.id)">
                              <img src="/src/assets/Layer.svg" />
                              <span class="tooltiptext">Share</span></v-btn
                            >
                          </li>
                          <li>
                            <v-btn
                              class="tooltip"
                              @click="deletePrayer(pray.id)"
                              ><img src="/src/assets/delete (5).svg" />
                              <span class="tooltiptext">Delete</span></v-btn
                            >
                          </li>
                        </ul> -->
                      </div>
                    </div>
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
                </div>
                <!-- filter popup  -->
                <!-- <div class="filter-options" id="filter-options">
                  <div class="filter-options-container">
                    <header>
                      <div class="filter-option by-name">
                        <input type="checkbox" id="filter-by-name" @click="
                            $router.push({
                              path: '/user',
                              query: { section: 'filter'}
                            })
                          " />
                        <label for="filter-by-name">Filter by name</label>
                      </div>
                      <div class="action">
                        <v-btn
                          class="escape"
                          @click="
                           $router.push({
                              path: '/user',
                              query: { section: 'filter'}
                            })
                          "
                        >
                          <img src="/src/assets/caret-right.svg" />
                        </v-btn>
                      </div>
                    </header>
                    <div class="filter-option by-date">
                      <div class="fields">
                        <label>Date Filter</label>
                        <select v-model="dateRange">
                          <option value="all" selected>All</option>
                          <option value="lastMonth">Last Month</option>
                          <option value="last3Months">Last 3 Months</option>
                          <option value="last6Months">Last 6 Months</option>
                        </select>
                      </div>
                    </div>
                    <footer>
                      <div class="footer-actions">
                        <v-btn class="button clear-filter">CLEAR FILTER</v-btn>
                        <v-btn class="button add-filter" @click="closeFilter()"
                          >APPLY FILTER</v-btn
                        >
                      </div>
                    </footer>
                  </div>
                </div> -->

                <!-- pagination button  -->
                <!-- <div class="pagination" style="color: white">
                  <button @click="prevPage" :disabled="currentPage === 1">
                    <img src="/src/assets/back-arrow.svg" />
                    <img src="/src/assets/back-arrow.svg" /> Previous
                  </button>
                  <span>{{ currentPage }}</span>
                  <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                  >
                    Next <img src="/src/assets/arrow-back.svg" />
                    <img src="/src/assets/arrow-back.svg" />
                  </button>
                </div> -->
              </div>
            </div>
            <router-link :to="{ name: 'home-page' }">
              <button class="back-button-bottom">Back</button></router-link
            >
          </div>
        </v-main>
      </div>
    </div>
  </v-app>
</template>
<script>
import axios from "../../../router/axios.js";
export default {
  data() {
    return {
      pinned: true,
      searchQuery: "",
      dateRange: "all",
      prayer_list: [],
      categories: [],
      category_id: "",
      type: "",
      my_prayer_id: "",
      note: "",
      pryerId: "",
      isLoading: true,
      user_ids: [],
      itemsPerPage: 9,
      currentPage: 1,
    };
  },
  computed: {
    // filteredList() {
    //   let filteredList = this.prayer_list;
    //   const query = this.searchQuery.toLowerCase();
    //   filteredList = filteredList.filter((pray) =>
    //     pray.title.toLowerCase().includes(query)
    //   );
    //   if (this.dateRange !== "all") {
    //     const currentDate = new Date();
    //     let startDate;

    //     if (this.dateRange === "lastMonth") {
    //       startDate = new Date(
    //         currentDate.getFullYear(),
    //         currentDate.getMonth() - 1,
    //         1
    //       );
    //     } else if (this.dateRange === "last3Months") {
    //       startDate = new Date(
    //         currentDate.getFullYear(),
    //         currentDate.getMonth() - 3,
    //         1
    //       );
    //     } else if (this.dateRange === "last6Months") {
    //       startDate = new Date(
    //         currentDate.getFullYear(),
    //         currentDate.getMonth() - 6,
    //         1
    //       );
    //     }

    //     filteredList = filteredList.filter((pray) => {
    //       const prayerDate = new Date(pray.created_at);
    //       return prayerDate >= startDate && prayerDate <= currentDate;
    //     });
    //   }
    //   return filteredList;
    // },
    // totalPages() {
    //   return Math.ceil(this.filteredList.length / this.itemsPerPage);
    // },
    // paginatedList() {
    //   const start = (this.currentPage - 1) * this.itemsPerPage;
    //   const end = start + this.itemsPerPage;
    //   return this.filteredList.slice(start, end);
    // },
  },

  methods: {
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    refreshData() {
      this.isLoading = true;
      this.prayerList();
    },

    launchMyModal(prayId) {
      this.my_prayer_id = prayId;
      document.querySelector("#mymodal").classList.add("open");
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
      this.note = "";
    },
    launchFilterOptions() {
      const filterOptions = document.querySelector("#filter-options");
      filterOptions.classList.toggle("open");
    },
    closeFilter() {
      document.querySelector("#filter-options").classList.remove("open");
    },
    formatDate(dateString) {
      const dateOptions = { day: "numeric" };
      const monthOptions = { month: "short" };

      const day = new Intl.DateTimeFormat(undefined, dateOptions).format(
        new Date(dateString)
      );
      const month = new Intl.DateTimeFormat(undefined, monthOptions).format(
        new Date(dateString)
      );

      return `${month} ${day}`;
    },
    prayerList(c_id) {
      this.category_id = c_id;
      axios
        .post("my-prayer-list", {
          type: "all",
          category_id: c_id,
          year: "",
          page: 1,
        })
        .then((response) => {
         console.log(response,">>>>>>>>>>>>>>>>>.s")
          this.prayer_list = response.data.data.data;
          this.isLoading = false;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    getCategory() {
      axios
        .post("category-list")
        .then((response) => {
          this.categories = response.data.data;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },
    deletePrayer(c_id) {
      axios
        .post("delete-my-prayer", { id: c_id })
        .then((response) => {
          this.prayerList();
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
    pinPrayer(p_id) {
      axios
        .post("pin-my-prayer", { my_prayer_id: p_id })
        .then((response) => {
          console.log(response);
          this.pinned = false;
          this.prayerList();
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right",
            theme: "sugar",
          });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
    shared(prayId) {
      this.$router.push({
        name: "user",
        query: { prayId: prayId },
      });
    },

    // for answer prayer
    answerPrayer(p_id) {
      axios
        .post("answered-my-prayer", {
          type: "add",
          my_prayer_id: p_id,
        })
        .then((response) => {
          console.log(response);
          this.prayerList();
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.isLoading = false;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },

    notePrayer() {
      axios
        .post("my-prayer-note", {
          type: "add",
          my_prayer_id: this.my_prayer_id,
          note: this.note,
        })
        .then((response) => {
          this.note = "";
          this.prayerList();
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeMyModal(); // Call closeMyModal here
          this.$router.push({ name: "pray" });
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
  },
  mounted() {
    this.getCategory();
    this.prayerList("");
  },
};
</script>
