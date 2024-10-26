<template>
  <router-view />
</template>

<script>

import axios from "./router/axios.js";

export default {
    name: 'App',
      created: function() {
        this.joinToSocket();
        this.$socket.on('connect', this.joinToSocket);
    },
    methods: {
        joinToSocket() {        	
            const { id } = this.$socket;
            if (id) {
                axios.post('user-socket-join', { socket_id: id });
            }
        }
    },
    watch: {
        authId(newVal) {
            if (newVal) {
                this.joinToSocket();
            }
        }
    }
};
</script>
