<template>
    <header class="header" id="header">
        <nav class="nav">
            <div class="menus">
                <a href="#" class="logo text-white text-decoration-none">
                    <v-img src="/src/assets/brand.png" style="height: 46px; width:46px"></v-img>
                    Spiritual Quotient
                </a>
                <ul>
                    <li :class="$route.name == 'messages' ? 'active' : ''" @click="$router.push({name : 'messages'})">Messages 
                        <span v-if="count > 0" class="circle"></span></li>
                    <li :class="$route.name == 'friend-page' ? 'active' : ''" @click="$router.push({name : 'friend-page'})">Friends</li>
                    <li :class="$route.name == 'group-chat' || $route.name == 'groupInfo' || $route.name == 'editGroup' || $route.name == 'create-group' ? 'active' : '' || $route.name == 'friend-list' ? 'active' : ''" @click="$router.push({name : 'group-chat'})">Groups</li>
                    <li :class="$route.name == 'pray' || $route.name == 'meditate' || $route.name == 'proclaim' || $route.name == 'fellowship' || $route.name == 'library' || $route.name == 'library2' || $route.name == 'meditateLibrary' || $route.name == 'new-library'? 'active' : '' " @click="viewAllMenus">More</li>
                    <li class="remaining-menus" v-if="isMenuExpaned">
                        <ul>
                            <li @click="$router.push({name : 'pray'})" :class="$route.name == 'pray' ? 'active' : ''">Pray</li>
                            <li @click="$router.push({name : 'meditate'})" :class="$route.name == 'meditate' ? 'active' : ''">Meditate</li>
                            <li @click="$router.push({name : 'proclaim'})" :class="$route.name == 'proclaim' ? 'active' : ''">Proclaim</li>
                            <li @click="$router.push({name : 'fellowship'})" :class="$route.name == 'fellowship' ? 'active' : ''">Fellowship</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="logged-in-user">
                <div class="group">
                    <p class="text-white">{{ userData.first_name }} {{ userData.last_name }}</p>
                   <div class="user-profilepic">
                    <img v-if="userData.profile_pic != null" :src="userData.profile_pic" height="54" width="54"/>
                    <h3 v-else class="default-profile">{{ userData.first_name?.charAt(0).toUpperCase() }}{{ userData.last_name?.charAt(0).toUpperCase() }}</h3>
                   </div>
                    <v-btn @click="logout()" class="tooltip">
                        <v-img src="/src/assets/log-out.png"></v-img>
                        <span class="tooltiptext-logout">Logout</span>
                    </v-btn>
                </div>
            </div>
        </nav>
    </header>
</template>
<script>
import axios from "../../router/axios.js";
import logOut from "@/middleware/logout";

export default 
{
    data() {
        return {
            userData:{},
            count:0,
            isMenuExpaned: false,
        };
    },
    mounted()
    {
        this.socketcall();
        this.user();
        this.getUnreadMsgCount();
        this.joinToSocket();
        this.$socket.on("connect", this.joinToSocket);
        
    },
    created() 
    {
        this.joinToSocket();
        this.$socket.on("connect", this.joinToSocket);
    },
    methods:{
        socketcall() {
          this.$socket.on("ChatLara.App\\Events\\ChatInboxMessage", (event) => {
             this.getUnreadMsgCount();
          });
          this.$socket.on("ChatLara.App\\Events\\ChatDetailMsgStatus", (event) => {
            console.log("ChatDetailMsgStatus");
             this.getUnreadMsgCount();
          });
        },
        joinToSocket() {
          const { id } = this.$socket;
          if (id) {
            axios.post("user-socket-join", { socket_id: id });
          }
        },
        user() {
            axios
            .get("user-profile")
            .then((response)=>{
                this.userData = response.data.data;
                console.log(this.userData.first_name);
            })
        },
        getUnreadMsgCount()
        {
            axios
              .post("get_unread_message_count")
              .then(async (response) => {
                this.count = response.data.data;
              })
              .catch(async (error) => {
              });
        },
        viewAllMenus() {
            this.isMenuExpaned = !this.isMenuExpaned;
        },
        logout()
        {
            axios
              .post("logout")
              .then(async (response) => {
                localStorage.removeItem("token");
                await this.$store.dispatch("logout");
                this.$router.push({ name: "login" });
              })
              .catch(async (error) => {
                await logOut(error);
              });
        },
        },
    watch: {
       
        "$route.query" : function (value) {
            // alert(value);
          if(value)
          {
            this.getUnreadMsgCount();
          }
        }
    },
}
</script>