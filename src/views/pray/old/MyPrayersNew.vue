<template>
  <v-app>
    <div class="page">
      <div class="main">
        <v-main class="body prayers">
          <div class="content">
            <div class="content-area">
              <div class="container-sm">
                <div class="mb-2">
                  <div class="hero-section" id="hero-section">
                    <div class="hero-image">
                      <img
                        class="image"
                        src="/src/assets/praying-that-woks.png"
                        alt="Praying That Works"
                      />
                    </div>
                  </div>
                </div>

                <div class="nested-tabs">
                  <div class="sq-tabs">
                    <v-tabs v-model="tab" class="parent-tabs">
                      <v-tab value="one">LEARN TO PRAY</v-tab>
                      <v-tab value="two">MY PRAYERS</v-tab>
                      <v-tab value="three">ANSWERED PRAYERS</v-tab>
                      <v-tab value="four">WRITE A PRAYER</v-tab>
                    </v-tabs>

                    <v-card-text class="parent-tab-contents">
                      <v-window v-model="tab">
                        <v-window-item value="one">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs varient-white have-icons"
                          >
                            <v-tab value="one"
                              ><v-img src="/src/assets/MaskGroupp.png"></v-img
                              >My Prayers</v-tab
                            >
                            <v-tab value="two"
                              ><v-img src="/src/assets/MaskGroup.png"></v-img
                              >Pray Together</v-tab
                            >
                          </v-tabs>

                          <v-card-text class="child-tab-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one">
                                <div class="search-module">
                                  <div class="search-area">
                                    <v-img
                                      src="/src/assets/magnifying-glass.svg"
                                      alt="Search Icon"
                                    ></v-img>
                                    <input
                                      type="search"
                                      placeholder="Search in My Prayers"
                                    />
                                    <v-btn>
                                      <v-img
                                        src="/src/assets/filter.svg"
                                        alt="Sort Icon"
                                      ></v-img>
                                    </v-btn>
                                  </div>
                                </div>

                                <div class="prayer-module">
                                  <div class="prayer-wrapper">
                                    <v-btn
                                      ><v-img
                                        src="/src/assets/refresh-arrow.svg"
                                      ></v-img
                                    ></v-btn>
                                    <div class="prayers">
                                      <div class="pray unread">
                                        <div class="text">
                                          text-- So, whether you eat or drink,
                                          or whatever you do, do all to the
                                          glory of God. -...
                                        </div>
                                        <ul class="pray-actions">
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/Group 1967.svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/office-push-pin.png"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/healthcare (2).svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/Layer.svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/delete (5).svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                        </ul>
                                        <p class="date">Oct 03</p>
                                      </div>
                                      <div class="pray">
                                        <div class="text">
                                          text-- So, whether you eat or drink,
                                          or whatever you do, do all to the
                                          glory of God. -...
                                        </div>
                                        <ul class="pray-actions">
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/Group 1967.svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/office-push-pin.png"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/healthcare (2).svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/Layer.svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                          <li>
                                            <v-btn
                                              ><v-img
                                                src="/src/assets/delete (5).svg"
                                              ></v-img
                                            ></v-btn>
                                          </li>
                                        </ul>
                                        <p class="date">Oct 03</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="two"> </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="two">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs varient-white have-icons"
                          >
                            <v-tab value="one"
                              ><v-img src="/src/assets/MaskGroupp.png"></v-img
                              >My Prayers</v-tab
                            >
                            <v-tab value="two"
                              ><v-img src="/src/assets/MaskGroup.png"></v-img
                              >Pray Together</v-tab
                            >
                          </v-tabs>

                          <v-card-text class="child-tab-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one"> </v-window-item>

                              <v-window-item value="two"> </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="three">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs varient-white have-icons"
                          >
                            <v-tab value="one"
                              ><v-img src="/src/assets/MaskGroupp.png"></v-img
                              >My Prayers</v-tab
                            >
                            <v-tab value="two"
                              ><v-img src="/src/assets/MaskGroup.png"></v-img
                              >Pray Together</v-tab
                            >
                          </v-tabs>

                          <v-card-text class="child-tabs-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one"> </v-window-item>

                              <v-window-item value="two"> </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="four">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs varient-white have-icons"
                          >
                            <v-tab value="one"
                              ><v-img src="/src/assets/MaskGroupp.png"></v-img
                              >My Prayers</v-tab
                            >
                            <v-tab value="two"
                              ><v-img src="/src/assets/MaskGroup.png"></v-img
                              >Pray Together</v-tab
                            >
                          </v-tabs>

                          <v-card-text class="child-tabs-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one"> </v-window-item>

                              <v-window-item value="two"> </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>
                      </v-window>
                    </v-card-text>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </v-main>
      </div>
    </div>
  </v-app>
</template>
<script>

export default {
  data: () => ({
    tab: null,
    childtab: null,
  }),
  mounted() {},
  methods: {},
};
</script>
