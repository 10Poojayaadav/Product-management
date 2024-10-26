<template>
  <div class="page users">
    <div class="main">
      <div class="body">
        <div class="container-sm">
          <div class="users-container">
            <div class="search-pray">  
              <input
                v-model="searchQuery"
                type="search"   
                placeholder="Search a friend"
              />
            </div>
            <div class="listings">
              <div v-if="paginatedList.length === 0">
                <p style="color: white; text-align: center">No data found</p>
              </div>
              <div v-else>
                <div class="user" v-for="(user, index) in paginatedList" :key="user.i">
                  <div class="profile">
                    <div class="profile-pic" v-if="user.profile_pic != null">
                      <v-img :src="user.profile_pic" alt=""></v-img>
                    </div>
                    <div class="profile-pic" v-else>
                      <h2>
                        {{ user.first_name.charAt(0).toUpperCase()
                        }}{{ user.last_name.charAt(0).toUpperCase() }}
                      </h2>
                    </div>

                    <p class="name">
                      {{ user.first_name }} {{ user.last_name }}
                    </p>
                  </div>
                  <div>
                    <v-btn :id="`btn-${index}`" class="add">
                      <img v-if="shareUser.includes(''+user.id+'')" @click="removeFromArray(user)" src="/src/assets/tick.png" />
                      <img  v-else @click="insertInArray(user)" src="/src/assets/add.png" />
                    </v-btn>
                  </div>
                </div>
              </div>
              <v-btn
                @click="gotToBackPage(shareUser)"
                :disabled="shareUser.length === 0"
                >done</v-btn
              >
              <div class="pagination" style="color: #fff">
                <button @click="prevPage" :disabled="currentPage === 1">
                  <img src="/src/assets/back-arrow.svg" />
                  <img src="/src/assets/back-arrow.svg" /> Previous
                </button>
                <span>{{ currentPage }}</span>
                <button @click="nextPage" :disabled="currentPage == totalPages">
                  Next <img src="/src/assets/arrow-back.svg" />
                  <img src="/src/assets/arrow-back.svg" />
                </button>
              </div>
            </div>
            <button class="back-button-bottom" @click="backFuntion()">Back</button>
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
      searchQuery: "",
      user_list: [],
      isClick: false,
      user_ids: [],
      itemPerPage: 5,
      currentPage: 1,
      prayId: "",
      shareUser:[],
      string:[],
    };
  },
  computed: {
    filteredList() {
      let filteredList = this.user_list;
      const query = this.searchQuery.toLowerCase();
      // return this.filteredList.filter((user) =>
      //   user.first_name.toLowerCase().includes(query) ||
      //     user.last_name.toLowerCase().includes(query)
      // );
      filteredList = filteredList.filter((user) =>
        user.first_name.toLowerCase().includes(query) ||
          user.last_name.toLowerCase().includes(query)
      );
      return filteredList;
    },
    totalPages() {
      return Math.ceil(this.filteredList.length / this.itemPerPage);
    },
    paginatedList() {
      const start = (this.currentPage - 1) * this.itemPerPage;
      const end = start + this.itemPerPage;
      return this.filteredList.slice(start, end);
    },
  },
  methods: {
    // userAPI
     userList() {
      axios.post("friend-list-all").then((response) => {
        console.log(response);
        this.user_list = response.data.data.data;
      });
    },
    //for shared user
// for pagination 
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
   
// to manage the click 
    insertInArray(u_id) {
      
      this.shareUser.push(''+u_id.id+'');
      this.string.push(u_id.first_name+" "+u_id.last_name);
      console.log(this.shareUser);
    },
    removeFromArray(u_id)
    {
        const indexToRemove = this.shareUser.indexOf(u_id.id);
        this.shareUser.splice(indexToRemove, 1);
        const removeString = this.string.indexOf(u_id.first_name+" "+u_id.last_name);
        this.string.splice(removeString, 1);
        console.log(this.shareUser);
    },
// for go back to that page where it come from 
     gotToBackPage(user_ids) {
       console.log(this.user_ids);
      if (this.prayId && !this.$route.query.section) {
        axios
          .post("shared-prayers", {
            prayer_id: this.prayId,
            to_user: user_ids,
          })
          .then((response) => {
            this.$toast.success(response.data.message, {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
              theme: "sugar", // Toast theme
            });
            this.$router.push({ name:"pray"});
          })
          .catch((error) => {    
            console.error("Error fetching data:", error);
          });
      } 
       else if(this.$route.query.section =='filter'){

          this.$router.push({
          name: "pray",
          params: { userIds: this.shareUser },
        });
      }
       else if(this.$route.query.section =='edit'){
          const uu = btoa(this.shareUser);
        const ss = btoa(this.string);
          this.$router.push({
          name: "edit-prayer",
          params:{id:this.prayId},
          query: { array: JSON.stringify(uu), uids:  JSON.stringify(ss) , edit:true},
        });
      }
      else if(this.$route.query.section =='filterTogether'){
          this.$router.push({
          name: "pray-together",
          params: { userIds: this.shareUser },
        });
      }    
       else {
        const u = btoa(this.shareUser);
        const s = btoa(this.string);
        this.$router.push({
          name: "add-prayers",
          query: { array: JSON.stringify(u), uids:  JSON.stringify(s) }
        });
      }
    },

// back button function     
    backFuntion() {  
      var route = this.$route.path.split("/");
      if (this.prayId) {
        this.$router.push({ name: "pray" });
      }
      else if(this.$route.query.section =='filter'){
      this.$router.push({ name: "pray" });
      }
       else if(this.$route.query.section =='filterTogether'){
      this.$router.push({ name: "pray-together" });
      }
       else if(this.$route.query.section =='edit'){
      this.$router.push({ name: "pray" });
      }
       else {
        this.$router.push({ name: "add-prayers" });
      }
    },
    sharedUserList()
    {
      axios.post("shared-users",{
        prayer_id:this.$route.query.prayId,
      })
      .then((response)=>{
        this.shareUser=response.data.data;
      });
    }
  },
  mounted() {
    this.prayId = this.$route.query.prayId;
    this.userList();
    this.sharedUserList();
  },
};
</script>
