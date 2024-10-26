<template>
  <!-- ansprayer page -->
  <div class="page group">
    <div class="inbox">
      <div class="group-tabs">
        <h3 class="text-white">My Groups</h3>
        <ul class="menus">
          <li :class="pageid=='fav-group'? 'active' : ''" @click="openFavouriateGroup()">
            <span>Favourite</span>
          </li>
          <li :class="pageid=='other'? 'active' : ''" @click="openOtherGroup()">
            <span>Other</span>
          </li>
          <li @click="$router.push({ name: 'create-group', params: { section: 'fellowship' }})">
            <span class="plus">+</span>
          </li>
        </ul>
      </div>
      <div v-if="pageid == 'other'" class="group-content">
        <div class="groups">
          <div class="listings" style="cursor:pointer;">
            <div
              class="group"
              v-for="groups in filteredList()"
              :key="groups.id"
            >
              <div
                class="profile"
                @click="
                  $router.push({
                    name: 'messages',
                    query: { group_id: groups.group_id },
                  })
                "
              >
                <div class="profile-pic" v-if="groups ? groups.image : 'N/A'">
                  <v-img
                    :src="groups.image"
                    alt=""
                    style="object-fit: cover"
                  ></v-img>
                </div>
                <div>
                  <p class="name">
                    {{ groups.name }}
                  </p>
                  <p class="description">
                    {{ groups.description }}
                  </p>
                </div>
              </div>
              <p class="participants">
                <img src="/src/assets/multiple-users.svg" />{{
                  participantString(groups.participants)
                }}
              </p>
              <div  class="group-options">
                <v-btn @click="showMenu(groups.id)">
                  <img src="/src/assets/ellipsis-vertical.svg" />
                </v-btn>
                <ul class="options" :id="`option-${groups.id}`">
                  <li @click="addToFavourite(groups.group_id)">
                    <span>Add to Favourite</span>
                    <img src="/src/assets/Group 1967.svg" />
                  </li>
                  <li @click="groupPrayer()">
                    <span>Create Group Prayer</span
                    ><img src="/src/assets/MaskGroupp.png" height="20" />
                  </li>
                  <li @click="groupDetails(groups.group_id)">
                    <span>View Group Info</span>
                    <img src="/src/assets/info.png" height="20" />
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="group-content">
        <div class="groups">
          <div class="listings" style="cursor:pointer;">
            <div
              class="group"
              v-for="groups in filteredList()"
              :key="groups.id"
            >
              <div
                class="profile"
                @click="
                  $router.push({
                    name: 'messages',
                    query: { group_id: groups.group_id },
                  })
                "
              >
                <div class="profile-pic" v-if="groups ? groups.image : 'N/A'">
                  <v-img
                    :src="groups.image"
                    alt=""
                    style="object-fit: cover"
                  ></v-img>
                </div>
                <div>
                  <p class="name">
                    {{ groups.name }} AAA
                  </p>
                  <p class="description">
                    {{ groups.description }}
                  </p>
                </div>
              </div>
              <p class="participants">
                <img src="/src/assets/multiple-users.svg" />{{
                  participantString(groups.participants)
                }}
              </p>
              <div class="group-options" @click="showMenu(groups.id)">
                <v-btn>
                  <img src="/src/assets/ellipsis-vertical.svg" />
                </v-btn>
                <ul class="options" :id="`option-${groups.id}`">
                  <li v-if="pageid=='other'" @click="addToFavourite(groups.group_id)">
                    <span>Add to Favourite</span>
                    <img src="/src/assets/Group 1967.svg" />
                  </li>
                  <li v-if="pageid=='fav-group'" @click="removeFromFav(groups.group_id)">
                    <span class="tooltiptext">Remove from favourite</span>
                    <img src="/src/assets/Group 1967.svg" />
                  </li>
                  <li @click="groupPrayer()">
                    <span>Create Group Prayer</span
                    ><img src="/src/assets/MaskGroupp.png" height="20" />
                  </li>
                  <li @click="groupDetails(groups.group_id)">
                    <span>View Group Info</span>
                    <img src="/src/assets/info.png" height="20" />
                  </li>
                </ul>
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
      group_list: [],
      member: [],
      searchQuery: "",
      pageid:"fav-group"
    };
  },
  methods: {
    filteredList() {
      let filteredList = this.group_list;
      const query = this.searchQuery.toLowerCase();
      filteredList = filteredList.filter((groups) =>
        groups.name.toLowerCase().includes(query)
      );
      return filteredList;
    },
    openOtherGroup()
    {
      this.pageid = "other";
      this.group();
    },
    openFavouriateGroup()
    {
      this.pageid = "fav-group";
      this.favGroup();
    },
    group() {
      this.pageid = 'other';
      axios.post("group-list-all").then((response) => {
        console.log(response.data.data.data);
        this.group_list = response.data.data.data;
      });
    },
    favGroup() {
      this.pageid = 'fav-group';
      axios.post("favourite-groups").then((response) => {
        console.log(response.data.data.data);
        this.group_list = response.data.data.data;
      });
    },
    addToFavourite(g_id) {
      axios
        .post("mark-as-favourite", {
          group_id: g_id,
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.group("");
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
    groupDetails(group_id) {
      this.$router.push({ name: "groupInfo", query: { group_id: group_id } });
    },
    participantString(participants) {
      var memberString = "";
      participants.forEach((element) => {
        memberString += element.user_data.first_name + ", ";
      });
      return memberString;
    },
     removeFromFav(g_id) {
      axios
        .post("remove-from-favourite", {
          group_id: g_id,
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.favGroup("");
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
    },
    showMenu(id){
      $(".options").hide();
      $("#option-" +id).toggle();
    },
    // groupPrayer(participants) {
    //   // alert("coming soon!!");
    //   // const p = btoa(participants);
    //   // const s = btoa(this.participantString);
    //   //   this.$router.push({
    //   //     name: "add-prayers",
    //   //     query: { participantArray: JSON.stringify(p), pids:  JSON.stringify(s) }
    //   //   });
    // },
  },
  mounted() {
    this.openFavouriateGroup();
  },
};
</script>
