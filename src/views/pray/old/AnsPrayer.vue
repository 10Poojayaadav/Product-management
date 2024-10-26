<template>
  <!-- ansprayer page -->
  <v-app>
    <div class="page pray pray-inbox">
      <div class="main">
        <v-main class="body bg-black-111">
          <div class="inbox">
            <div class="container">
              <aside class="pray-sidebar">
                <div class="menu-wrapper">
                  <ul class="menus">
                    <li
                      :class="category_id == '' ? 'active' : ''"
                      @click="ansPrayerList('')"
                    >
                      <span></span>
                      <span>All</span>
                    </li>
                    <li
                      v-for="(category, ind) in categories"
                      :class="category_id == category.id ? 'active' : ''"
                      :key="ind"
                    >
                      <span @click="ansPrayerList(category.id)">
                        <span><img :src="category.image" /></span>
                        <span>{{ category.name }}</span></span
                      >
                    </li>
                  </ul>
                </div>
              </aside>
              <div class="content">
                <!-- <div class="search-pray">
                  <input
                    v-model="searchQuery"
                    type="search"
                    placeholder="Search in answered prayer"
                  />
                  <v-btn class="apply-filter" @click="launchFilterOptions"
                    ><img src="src/assets/filter.svg" alt="Filter"
                  /></v-btn>
                </div> -->
                <div class="bundle">
                <div class="search-pray">
                  <input type="search" placeholder="Search in Broadcast" />
                
                </div>
                <select class="by-date" style="color:white;"  v-model="year" @change="yearFilter()">
                  <option>2023</option>
                  <option>2022</option>
                  <option>2021</option>
                  <option>2020</option>
                  <option>2019</option>
                  <option>2018</option>
                  <option>2017</option>
                  <option>2016</option>
                  <option>2015</option>
                </select>
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
                    <div
                      class="prayer"
                      :id="ansPray.id"
                      v-for="(ansPray, index) in paginatedList"
                      :key="index"
                    >
                      <div v-if="ansPray.prayer">
                        <h4 style="color: white">AA</h4>
                      </div>
                      <div class="group">
                        <p class="name">
                          {{ ansPray.prayer ? ansPray.prayer.title : "NA" }}
                        </p>
                      </div>

                      <!-- LIST action  -->
                      <ul class="actions">
                        <li>
                          <v-btn
                            class="tooltip"
                            @click="unansweredPrayer(ansPray.prayer.id)"
                          >
                            <img src="/src/assets/prayy.svg" />
                            <span class="tooltiptext">Unanswered</span></v-btn
                          >
                        </li>
                        <li>
                          <v-btn
                            class="tooltip"
                            @click="deletePrayer(ansPray.id)"
                            ><img src="/src/assets/delete (5).svg" />
                            <span class="tooltiptext">Delete</span></v-btn
                          >
                        </li>
                      </ul>
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
                              name: 'user',
                              params: { section: 'pray' },
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
      ansPrayer_list: [],
      categories: [],
      category_id: "",
      pryerId: "",
      isLoading: true,
      user_ids: [],
      itemsPerPage: 9,
      currentPage: 1,
      year:"",
    };
  },
  computed: {
    filteredList() {
      let filteredList = this.ansPrayer_list;
      const query = this.searchQuery.toLowerCase();
      filteredList = filteredList.filter((ansPray) =>
        ansPray.prayer.title.toLowerCase().includes(query)
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
    yearFilter(){
      
      this.ansPrayerList();
    },
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
      this.ansPrayerList();
    },

    launchMyModal() {
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
    ansPrayerList(c_id) {
      this.category_id = c_id;
      axios
        .post("answered-my-prayer-list", {
          category_id: c_id,
          year: this.year,
          page: 1,
        })
        .then((response) => {
          console.log(response);
          this.ansPrayer_list = response.data.data.data;
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
        .post("delete-answered-prayer", { id: c_id, my_prayer_id: c_id })
        .then((response) => {
          this.ansPrayerList();
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
    unansweredPrayer(p_id) {
      axios
        .post("unanswer-my-prayer", {
          type: "unanswer",
          my_prayer_id: p_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.ansPrayerList("");
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
  },
  mounted() {
    this.getCategory();
    this.ansPrayerList("");
  },
};
</script>
