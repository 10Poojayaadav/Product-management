<template>
  <v-app>
    <div class="page">
      <div class="main">
        <SideBar />
        <v-main class="body presence-prayer-series">
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
                            class="child-tabs"
                          >
                            <v-tab value="one">Prayer Starter Pack</v-tab>
                            <v-tab value="two">Presence pray series</v-tab>
                            <v-tab value="three">Person pray series</v-tab>
                            <v-tab value="four">Purpose pray series</v-tab>
                          </v-tabs>

                          <v-card-text class="child-tab-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="two">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="three">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="four">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="two">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs"
                          >
                            <v-tab value="one">Prayer Starter Pack</v-tab>
                            <v-tab value="two">Presence pray series</v-tab>
                            <v-tab value="three">Person pray series</v-tab>
                            <v-tab value="four">Purpose pray series</v-tab>
                          </v-tabs>

                          <v-card-text class="child-tab-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="two">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="three">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="four">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="three">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs"
                          >
                            <v-tab value="one">Prayer Starter Pack</v-tab>
                            <v-tab value="two">Presence pray series</v-tab>
                            <v-tab value="three">Person pray series</v-tab>
                            <v-tab value="four">Purpose pray series</v-tab>
                          </v-tabs>

                          <v-card-text class="child-tabs-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="two">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="three">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="four">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                            </v-window>
                          </v-card-text>
                        </v-window-item>

                        <v-window-item value="four">
                          <v-tabs
                            v-model="childtab"
                            center-active
                            class="child-tabs"
                          >
                            <v-tab value="one">Prayer Starter Pack</v-tab>
                            <v-tab value="two">Presence pray series</v-tab>
                            <v-tab value="three">Person pray series</v-tab>
                            <v-tab value="four">Purpose pray series</v-tab>
                          </v-tabs>

                          <v-card-text class="child-tabs-contents">
                            <v-window v-model="childtab">
                              <v-window-item value="one">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="two">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>

                              <v-window-item value="three">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
                              <v-window-item value="four">
                                <div class="tiles">
                                  <div class="tiles-wrapper">
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                    <article class="tile">
                                      <div class="tile-inner"></div>
                                    </article>
                                  </div>
                                </div>
                              </v-window-item>
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
import SideBar from "../layouts/SideBar.vue";

export default {
  data: () => ({
    tab: null,
    childtab: null,
  }),
  components: {
    SideBar,
  },
  mounted() {},
  methods: {},
};
</script>
