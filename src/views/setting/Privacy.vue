<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
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
                    class="prayer" >
                    <div class="group">
                        <span class="name">Hide my birthday</span>
                      <label class="switch">
                        <input type="checkbox" checked />
                        <span class="slider round"></span>
                      </label>
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
</template>
<script>
import axios from "../../router/axios.js";
export default {
  data() {
    return {
      birthday_details: [],
    };
  },
  methods: {
    birthday() {
      axios
        // .post("faqs?category_id="+this.$route.params.id)
        .post("hide-my-birthday")
        .then((response) => {
          console.log(response.data.data);
          this.birthday_details = response.data.data;
        });
    },
  },
  mounted() {
    // this.faqCategory();
    this.birthday();
  },
};
</script>