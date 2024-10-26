<template>
  <div class="page broadcast">
    <div class="main">
      <div class="body bg-black-111">
        <div class="container-sm">
          <div class="broadcast-container">
            <div class="content">
             
              <div class="wrapper text-center text-white">
                <div class="user-profile mb-5">
                 
                </div>

                <div class="info">
                 <p><b>Report</b> Testing Reaction</p> <br>
                  <p>You are about to report this group to SQ. Please provide us with 
                      information on abusive or inappropriate behaviour or content in the space below.
                  </p>
                </div>
              </div>
             
              <div>
                <div>
                  <h4 style="color: white">DETAILS</h4>
                  <textarea
                    v-model="message"
                    cols="80"
                    rows="10"
                    style="border: 2px solid white; color: white"
                  ></textarea>
                </div>
              </div>
              <v-btn @click="reportGroup()">Report Group</v-btn>
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
     
      message: "",
      
    };
  },
  methods: {
    reportGroup() {
      axios
        .post("report-user", {
          type: "group",
         message:this.message,
         report_id:this.$route.query.group_id
        })
        .then((response) => {
          console.log(response);
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
       

          this.$router.push({
            name: "groupInfo",
            query: { group_id: this.$route.query.group_id},
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
   
  },
  mounted() {
    // this.createGroup();
  },
};
</script>
