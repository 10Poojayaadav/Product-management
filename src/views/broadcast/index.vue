<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
              <div class="bundle">
                <div class="search-pray">
                  <input type="search" placeholder="Search in Broadcast" />
                
                </div>
                <select class="by-date">
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
                    <img src="/src/assets/refresh-arrow.svg" alt="refresh" />
                  </span>
                </header>
                <div class="prayers">
                  <div
                    class="prayer"
                    v-for="(v1, index) in broadcast_file"
                    :key="index"
                  >
                    <div class="group">
                      <p class="name">{{ v1.caption }}</p>
                    </div>
                    <p class="date">{{ v1.body }}</p>
                    <!-- LIST action  -->
                    <ul class="actions">
                      <li>
                        <v-btn><img src="/src/assets/unread.svg" /></v-btn>
                      </li>
                      <li>
                        <v-btn><img src="/src/assets/delete (5).svg" /></v-btn>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- filter popup  -->
                <!-- <div class="filter-options" id="filter-options">
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
                          <img src="src/assets/caret-right.svg" />
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
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      broadcast_file: [],
      type: "",
    };
  },
  mounted() {
    this.userProfile();
  },
  methods: {
    broadcast() {
      axios
        .post("inbox", {
          user_type: this.type,
        })
        .then((response) => {
          this.broadcast_file = response.data.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    userProfile() {
      axios.get("user-profile").then((response) => {
        this.type = response.data.data.type;
        this.broadcast();
      });
    },
    // launchFilterOptions() {
    //   const filterOptions = document.querySelector("#filter-options");
    //   filterOptions.classList.toggle("open");
    // },
    // closeFilter() {
    //   document.querySelector("#filter-options").classList.remove("open");
    // },
  },
};
</script>
