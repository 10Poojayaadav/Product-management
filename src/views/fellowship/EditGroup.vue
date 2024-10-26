<template>
  <div class="_modal create-group group">
    <div class="group edit-group">
      <div class="modal-content-wrapper">
        <div class="container-sm">
          <h4 class="text-center text-white mb-4">Edit Group</h4>
         <button class="cross-btn" @click="$emit('close')">+</button>
          <div class="content">
            <div class="text-white">
              <label class="d-block w-100 text-center" for="change-image">
                <div class="user-pic mb-5">
                    <h2  v-if="image == null" class="text-h3 text-center text-white">
                      {{
                        splitName(name)
                      }}
                    </h2>
                    <v-img v-else :width="100" cover :src="image"> </v-img>
                  </div>
                </label>
                <input
                  type="file"
                  id="change-image"
                  accept="image/*"
                  @change="uploadImage"
                />
              <div class="info">
                <div class="fields-wrapper">
                  <div class="fields">
                    <label for="name">Group Name</label>
                    <input v-model="name" cols="80" rows="2" id="name" />
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
                  <div class="text-center">
                    <v-btn @click="editGroup()">SAVE</v-btn>
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
  name: "imageUpload",
  data() {
    return {
      name: "",
      description: "",
      image: '',
      selectedFile: null,
      group_details: [],
    };
  },
  props:{
    id:{
      type:String,
      default:()=> '',
    }
  },
  methods: {
      splitName(groupName) {
      var group = groupName?.split(" ");
      if (group.length == 1) {
        return (
          group[0].charAt(0)?.toUpperCase() +
          "" +
          group[0].charAt(1)?.toUpperCase()
        );
      } else {
        let a = group[0]?.charAt(0);
        let b = group[1]?.charAt(0);
        return a?.toUpperCase() + "" + b?.toUpperCase();
      }
    },
   closeFunction(id)
    {
      this.$emit('editId', id);
    },
    editGroup() {
      const Id = this.id;
      const groupId =this.group_details.id
      const formData = new FormData();
      formData.append("image", this.selectedFile);
      formData.append("type", "edit");
      formData.append("name", this.name);
      formData.append("description", this.description);
      formData.append("id", groupId);
      axios
        .post("add-chat-group", formData)
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, 
            position: "top-right",
            theme: "sugar", 
          });
          this.closeFunction(this.id);
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, 
            position: "top-right",
            theme: "sugar", 
          });
        });
    },
    uploadImage(e) {
      const image = e.target.files[0];
      this.selectedFile = image;
      if (!this.selectedFile.type.includes("image")) {
        this.$toast.error("Please Select Image Only.", {
          duration: 4000, 
          position: "top-right",
          theme: "sugar", 
        });
        this.selectedFile = null;
        return false;
      }
      else
      {
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
  mounted() {
    axios
      .post("group-view", { other_id: this.id })
      .then((response) => {
        this.group_details = response.data.data.group;
        this.name = this.group_details.name;
        this.image = this.group_details.image;
        this.description = this.group_details.description == 'null' ? "" : this.group_details.description;
      })
      .catch((error) => {
        if (error.response) {
          console.error("Server Error:", error.response.data);
        }
      });
  },
 
};
</script>
