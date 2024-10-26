<template>
  <v-app>
    <div class="page pray-list">
      <v-main class="body" style="width: 100%">
        <div class="content_section">
          <Header />
          <header class="head">
            <div class="top-bar">
              <v-btn ripple="false">
                <v-img src="../../assets/left-arrow.svg" />
                Prayers
              </v-btn>
              <v-btn ripple="false" icon color="transparent">
                <v-img src="../../assets/filter_2.svg" />
              </v-btn>
              
            </div>
          </header>
        <v-card>
    <v-tabs v-model="activeTab" background-color="primary" dark>
      <v-tab class="red--text">Tab 1</v-tab>
      <v-tab class="red--text">Tab 2</v-tab>
    </v-tabs>

    <v-tabs-items v-model="activeTab">
      <v-tab-item>
        <v-card flat>
          <!-- Show Tab 1 content only when activeTab is 0 -->
          <v-card-text v-if="activeTab === 0">
            This is the content for Tab 1. You can write anything here.
          </v-card-text>
        </v-card>
      </v-tab-item>

      <v-tab-item>
        <v-card flat>
          <!-- Show Tab 2 content only when activeTab is 1 -->
          <v-card-text v-if="activeTab === 1">
            This is different content for Tab 2. Customize this text as needed.
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
        </div>
      </v-main>
    </div>
  </v-app>
</template>

<script>
import Header from "../layout/Header.vue";
import axios from "../../router/axios.js";
export default {
  data: () => ({
     activeTab: 0, // Track the active tab index
     
  }),
  components: {
    Header,
  },
  mounted() {
    
  },
  methods: {
   
  }
};
</script>

<style scoped>
.pray-list .head {
  background: #d95256;
}

.pray-list .top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 30px;
}

.pray-list .v-btn {
  background: transparent;
  box-shadow: none;
  color: #ffffff;
  padding: 0;
}

.pray-list .v-btn .v-img__img {
  padding-left: 10px;
}

.pray-list .v-btn[data-v-7670ea8b]{
    background: transparent;
    box-shadow: none;
    color: #000;
    padding: 0;
}
</style>
