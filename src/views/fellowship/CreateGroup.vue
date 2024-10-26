<template>
  <div class="_modal create-group group">
    <div class="modal-content-wrapper">
    <h4 class="text-center text-white mb-4">Create New Group</h4>
    <button class="cross-btn" @click="$emit('closePopup')">+</button>
    <div class="content">
      <div class="text-white">
        <div class="user-pic mb-5">
          <label class="d-block w-100 text-center" for="change-image">
            <v-img :width="100" v-if="image == null" cover src="/src/assets/info.png"></v-img>
            <v-img v-else :width="100" cover :src="image"> </v-img>
          </label>
        </div>
        <input type="file" id="change-image" accept="image/*" @change="uploadImage" />
        <div class="info">
          <div class="fields-wrapper">
            <div class="fields">
              <label for="name">Group Name</label>
              <input autocomplete="off" v-model="name" cols="80" rows="2" id="name" />
            </div>
            <div class="fields mb-5">
              <label for="description">Group Description</label>
              <textarea
                v-model="description"
                cols="80"
                rows="5"
                id="description"
              ></textarea>
            </div>
            <center>
              <v-btn @click="createGroup()">Save</v-btn>
            </center>
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
  name: "imageUpload",
  data() {
    return {
      name: "",
      description: "",
      image: null,
      selectedFile: null,
    };
  },
  methods: {
    closeFunction(id)
    {
      this.$emit('groupId', id);
    },
    createGroup() {
      const formData = new FormData();
      formData.append("image", this.selectedFile);
      formData.append("type", "add");
      formData.append("name", this.name);
      formData.append("description", this.description);
      axios
        .post("add-chat-group", formData)
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.closeFunction(response.data.data.group_id);
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
        });
    },
    uploadImage(e) {
      // document.getElementById("type_message").focus();
      const image = e.target.files[0];
      this.selectedFile = image;
      if (!this.selectedFile.type.includes("image")) {
        this.$toast.error("Please Select Image Only.", {
          duration: 4000, // Duration in milliseconds
          position: "top-right", // Toast position
          theme: "sugar", // Toast theme
        });
        this.selectedFile = null;
        this.image = null;
        e.preventDefault();
      }
      else {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.image = e.target.result;
        };
        if (image) {
          reader.readAsDataURL(image);
        }
      }
    },
  },
};
</script>
