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
                          ><img src="/src/assets/MaskGroupp.png" alt="Messages"
                        /></span>
                        <span>My Prayer</span>
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
                    placeholder="Search in Pray Together"
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
                    <div v-if="paginatedList.length === 0">
                      <p style="color: white; text-align: center">
                        No data found
                      </p>
                    </div>
                    <div v-else>
                      <div
                        class="prayer"
                        :id="pray.id"
                        v-for="(pray, index) in paginatedList"
                        :key="index"
                      >
                        <div class="group">
                          <router-link
                            :to="{
                              name: 'edit-prayTogether-prayer',
                              params: { id: pray.id },
                            }"
                          >
                            <p class="name">{{ pray.title }}</p>
                          </router-link>
                        </div>
                        <p class="date">{{ formatDate(pray.created_at) }}</p>
                        <!-- LIST action  -->  
                        <ul class="actions">
                          <li>
                            <v-btn
                              class="tooltip"
                              @click="launchMyModal(pray.id)"
                              ><img src="/src/assets/chat (2).svg" />
                              <span class="tooltiptext">Comment</span>
                            </v-btn>
                          </li>
                          <li class="pinned-status pinned tooltip">
                            <v-btn @click="pinPrayer(pray.id)">
                              <img
                                v-if="pray.pinrel_count > 0"
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
                             @click="reportMyModal(pray)"
                            >
                              <img src="/src/assets/dislike.svg" />
                              <span class="tooltiptext">Report</span></v-btn
                            >
                          </li>
                          <li>
                            <v-btn class="tooltip" @click="untag(pray)">
                              <img src="/src/assets/Group 1977.svg" />
                              <span class="tooltiptext">Un-tag</span></v-btn
                            >
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!-- add comment popup  -->
                  <div class="mymodal" id="mymodal">
                    <div class="myform">
                      <span class="close-modal" @click="closeMyModal()">+</span>
                      <div class="form">
                        <h2>Add Comment +</h2>
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
                   <!-- report pop up -->
                   
                  <div class="mymodal" id="reportmodal">
                    <div class="myform">
                      <span class="close-modal" @click="closeReportModal()">+</span>
                      <div class="form">
                        <h2>Report Content</h2>
                        <p class="name">You are about to flag this 
                          content as offensive and objectionble. Our team will 
                          review this content and respond within 24 hours.
                        </p>
                        <v-btn> CANCEL </v-btn> <v-btn @click="report()"> REPORT </v-btn>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- filter popup  -->
                <div class="filter-options" id="filter-options">
                  <div class="filter-options-container">
                    <header>
                      <div class="filter-option by-name">
                        <input
                          type="checkbox"
                          id="filter-by-name"
                          @click="
                            $router.push({
                            path: '/user',
                              query: { section: 'filterTogether'}
                            })
                          "
                        />
                        <label for="filter-by-name">Filter by name</label>
                      </div>
                      <div class="action">
                        <v-btn class="escape">
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
                      <div class="fields">
                        <label>Custom Range Filter</label>
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
                </div>

                <!-- pagination button  -->
                <div class="pagination" style="color: white">
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
                </div>
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
import axios from "../../router/axios.js";

export default {
  data() {
    return {
      searchQuery: "",
      dateRange: "all",
      prayer_list: [],
      categories: [],
      category_id: "",
      type: "",
      my_prayer_id: "",
      reportId:'',
      note: "",
      pryerId: "",
      isLoading: true,
      dropdownDate: [
        { key: 1, value: "Last Month" },
        { key: 3, value: "Last 3 Months" },
        { key: 6, value: "Last 6 Months" },
        { key: 12, value: "Last Year" },
      ],
      user_ids: [],
      user_id:'',
      itemsPerPage: 9,
      currentPage: 1,
      comment:"",
    };
  },
  computed: {
    filteredList() {
      let filteredList = this.prayer_list;
      const query = this.searchQuery.toLowerCase();
      filteredList = filteredList.filter((pray) =>
        pray.title.toLowerCase().includes(query)
      );
      if (this.dateRange !== "all") {
        const currentDate = new Date();
        let startDate;

        if (this.dateRange === "lastMonth") {
          startDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() - 1,
            1
          );
        } else if (this.dateRange === "last3Months") {
          startDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() - 3,
            1
          );
        } else if (this.dateRange === "last6Months") {
          startDate = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth() - 6,
            1
          );
        }

        filteredList = filteredList.filter((pray) => {
          const prayerDate = new Date(pray.created_at);
          return prayerDate >= startDate && prayerDate <= currentDate;
        });
      }
      return filteredList;
    },
    totalPages() {
      return Math.ceil(this.filteredList.length / this.itemsPerPage);
    },
    paginatedList() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredList.slice(start, end);
    },
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
     reportMyModal(pray) {
      this.my_prayer_id = pray.id;
      this.reportId= pray.user_id;
      document.querySelector("#reportmodal").classList.add("open");
    },
    closeReportModal() {
      document.querySelector("#reportmodal").classList.remove("open");
    },


    launchMyModal(prayId) {
      this.my_prayer_id = prayId;
      document.querySelector("#mymodal").classList.add("open");
    },
    closeMyModal() {
      document.querySelector("#mymodal").classList.remove("open");
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
        .post("my-pray-together-list", {
          type: "all",
          category_id: c_id,
          year: "",
          page: 1,
        })
        .then((response) => {
          console.log(response);
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
    pinPrayer(c_id) {
      
      axios
        .post("pin-my-pray-together", { my_pray_together_id: c_id })
        .then((response) => {
          this.prayerList();
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right",
            theme: "sugar",
          });
          document
            .querySelector(`div[id="${c_id}"]`)
            .classList.toggle("pinned");
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
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
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeMyModal();
          // this.$router.push({ name: "pray-together" });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    report(){
      axios.post("report-user",{
        type:"prayer",
        report_id:this.reportId,
        prayer_id: this.my_prayer_id,
      })
      .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push({
            name: "friend-info",
            params: { id: this.$route.params.id},
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
    untag(pray){

      axios.post("untag-my-prayer",{
        user_id:pray.user_id,
        prayer_id:pray.id,
      })
      .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.push({
            name: "friend-info",
            params: { id: this.$route.params.id},
          });
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    }
  },
  mounted() {
    this.getCategory();
    this.prayerList("");
  },
};
</script>
