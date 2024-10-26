<template>
  <v-app>
    <section class="messaging-section">
    <Header/>
    <div class="messaging-zone">
      <div class="message-sidebar">
        <div class="message-sidebar-inner">
          <div class="search-friend">
            <div class="bg-white search-area">
              <v-img src="/src/assets/search.png" class="search-icon" height="16"></v-img> 
              <input autocomplete="off" type="text" @keyup="searchThis" id="search" placeholder="Search friend or groups" />
               <v-btn v-if="searchText!=''" id="clear-search-btn" class="clear-search" @click="clearSearch">
                  <v-img src="/src/assets/cross-fill.png" height="14"></v-img>
                </v-btn>
            </div>
            <div class="button-row">
              <v-btn @click="openCreatePopup" class="create-group-btn" id="create-group" >Create Group</v-btn>
              <v-btn class="fav-group-btn" @click="favGroup()" id="fav-group">Favourite</v-btn>
              <v-btn @click="updateUserChatList()" class="other-group-btn active" id="other-group" >Other</v-btn>
            </div>
          </div>
            <!-- User Chat List Code Here Start -->
            <div class="friends">
              <div v-if="searchText != ''" class="list-view">
                      <div v-if="searchMessageList?.length != 0">
                        <div v-if="searchMessageList?.length != 0 && userType == 'group'">
                          <div
                            class="frnd"
                            v-for="(chat, index) in searchMessageList"
                            :key="index"
                            @click="
                                getChatDetail(
                                  chat.group_id,
                                  chat?.is_online,
                                  userType,
                                  chat.name,
                                  chat.image
                                )
                              ">
                            <div>
                              <div class="group">
                                  <div class="dp"  style="background-color: #5D366D;">
                                    <img
                                      v-if="chat.image !== null"
                                      :src="chat.image"
                                    />
                                    <div v-else="chat.name != null">
                                      <h3>
                                        {{ splitName(chat.name) }}
                                      </h3>
                                    </div>
                                  </div>
                                <div class="info">
                                    <h5 class="text-white frnd-name">
                                      {{ chat.name }}
                                    </h5>
                                  </div>
                              </div>
                              <v-btn
                                v-if="chat.chatlistpin_count > 0"
                                class="pin-user"
                                @click="$event.stopPropagation()"
                                ><img src="/src/assets/Vector.svg"
                              /></v-btn>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div v-else>
                        <p style="color: white; text-align: center">
                          No Record found
                        </p>
                      </div>
                    </div>
                    <template v-else>
                      <div v-if="messageList.length > 0" class="list-view group" id="list-view"  v-on:scroll="scrollListView($event)">
                        <div  :class="other_user_id == chat?.group_data?.group_id ? 'frnd active' : 'frnd'"  
                        v-for="(chat, index) in messageList" :key="index" @contextmenu="showMenu($event,chat.id)">
                          <div v-if="chat.type == 'group'" :id="`friend-${chat.group_data.id}`"
                           @click="getChatDetail(chat.group_data.group_id, 'no',
                            chat.type, chat.group_data.name, chat.group_data.image)">
                            <div class="info" >
                              <div class="frnd-options">
                            <div
                              class="options"
                              :id="`option-id-${chat.id}`"
                            >
                              <ul>
                               <!--  <li @click="muteUserList(chat.other_id)">
                                  Mute
                                </li> -->
                                <!-- <li
                                  @click="
                                    $router.push({
                                      name: 'friend-info',
                                      params: { id: chat.other_id },
                                    })
                                  "
                                >
                                  Friend Info
                                </li> -->
                               <!--  <li>
                                  Clear Chat
                                </li> -->
                                <li
                                  @click="removeFromFav($event,chat.other_id)"
                                  v-if="favApi"
                                >
                                  Remove From Favourites <img src="/src/assets/logout.png" class="ml-2">
                                </li>
                                <li
                                  @click="addToFavourite($event,chat.other_id)"
                                  v-else
                                >
                                  Add to Favourites <img src="/src/assets/add-to-favorite.png" class="ml-2">
                                </li>
                                <!-- <li @click="groupDetails(chat.other_id)">
                                  View Group Info <img src="/src/assets/group-info.png">
                                </li> -->
                                <li @click="openPopup(chat.other_id)">
                                  View Group Info <img src="/src/assets/group-info.png">
                                </li>
                              </ul>
                            </div>  
                          </div>
                              <div class="group">
                                <div class="dp"  style="background-color: #5D366D;">
                                  <img
                                    :src="chat.group_data.image"
                                    v-if="
                                      chat.group_data != null &&
                                      chat.group_data.image != null
                                    "
                                  />
                                  <div v-else-if="chat.group_data != null">
                                    <h3 class="profile_pic">
                                      {{
                                        chat
                                          ? splitName(chat.group_data.name)
                                          : ""
                                      }}
                                    </h3>
                                  </div>
                                  <div v-else>
                                    <h2>NO</h2>
                                  </div>
                                </div>
                                <div class="info">
                                  <h5 class="frnd-name text-white">{{ chat.group_data.name }}</h5>
                                  <!-- <h5 class="frnd-name text-white">{{ chat.group_data.name.length<12?chat.group_data.name:chat.group_data.name.substring(0,12)+"..." }}</h5> -->
                                  <p v-if="chat.message" class="text-white">
                                     <p v-if="chat.message.get_msg_by?.id == my_user_id">You: {{ stripHtmlTags(chat.message.message.length<20 ? chat.message.message : chat.message.message.substring(0,20)+"...")}}</p>
                                       <p v-else>{{ chat.message.get_msg_by?.first_name }}: {{ stripHtmlTags(chat.message.message.length<20 ? chat.message.message : chat.message.message.substring(0,20)+"...")}}</p>
                                  </p>
                                </div>
                                <span
                                  v-if="userTypingId == chat.group_data.group_id"
                                  style="color: white; font-size: 12px; margin:auto;">
                                  &nbsp; &nbsp; typing...</span>
                            </div>
                      </div>
                      <span v-if="chat.unseen_count> 0" class="unseen-count">
                      <span class="count">{{
                        chat.unseen_count
                      }}</span></span>
                      <button
                        class="pin-user"
                        @click="$event.preventDefault()"
                        v-if="chat.chatlistpin_count > 0"  >
                        <img src="/src/assets/Vector.svg"
                      /></button>
                      <div class="hamburger1">
                        <img src="/src/assets/ellipsis-vertical.svg" height="30" width="30">
                    </div>
                          </div>
                            </div>
                            <div class="loader-messageList"> 
                            <div v-if="Load" class="chat-with_loader">
                              <div class="loader-text">Loading more chats...</div>
                              <div class="loader text-white"></div>
                            </div>
                           </div>
                            </div>
                      <div class="no-record-style" v-else-if = "!showLoaderOnList">No records found!</div>
                      <div v-if="showLoaderOnList">
                        <div class="chat-with-loader">
                            <div class="loader"></div>
                        </div>
                      </div>
                    </template>
            </div>
            <!-- User Chat List End Code -->
        </div>
      </div>
      <!-- Chat Detail Extended View Start -->
        <div class="messages-container" @click="reactionPannelChecker($event)">
          <div class="user-chat-info" v-if="nameHeader">
            <div class="profile" v-if="userType=='group'">
              <div class="profile-pic" v-if="groupData.image != null">
                <img
                  :src="groupData.image"
                  alt=""
                >
              </div>
              <div class="profile-pic" v-else>
                <h2>
                  {{ splitName(groupData?.name )}}
                </h2>
              </div>
                <div class="info">
                <h3 class="name">{{groupData.name}} </h3>
                </div>
            </div>
            <div v-else class="profile">
            <div class="profile-pic" v-if="userDetails.profile_pic != null">
              <v-img
                :src="userDetails.profile_pic"
                alt=""
              ></v-img>
            </div>
            <div class="profile-pic" v-else>
              <h2>
                {{ splitName(userDetails?.first_name+' '+userDetails?.last_name )}}
              </h2>
            </div>
              <div class="info">
              <h3 class="name">{{userDetails.first_name}} {{userDetails.last_name}}</h3>
              </div>
          </div>
          </div>
          <div v-if="isLoading" class="chat-with_loader">
            <div class="loader"></div>
          </div>
          <div
            v-else
            class="chat-with"
            id="chat-div"
          >
          <div v-if="moreLoad" class="chat-with_loader h-auto">
            <div class="loader more-loader"></div>
           </div>
            <div
              v-if="userMessages.length >= 1"
              v-for="(message, index) in userMessages.slice().reverse()"
              :key="index"
            >
              <div v-if="message.type == 'text'">
                <div
                  v-if="
                    message.message_by == my_user_id && replyMsgId == ''
                  "
                >
                  <div
                    :class="
                      message.get_msg_reply != null
                        ? 'chat chat-me msg-reply'
                        : 'chat chat-me'
                    "
                  >
                  <div class="message-actions">
                      <div class="smiley-emoji">
                        <img
                          src="/src/assets/smile.png"
                          @click="this.showReactionPickerFor($event,message.id)"
                          alt=""
                          height="40"
                        />
                      </div>
                     <div class="ellipsis">
                          <div class="hamburger">
                            <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                          </div>
                          <div class="options">
                            <ul>
                              <li @click="forwordMessage(message.id)">
                                Forward
                              </li>
                              <li @click="replyMessage($event, message)">
                                Reply
                              </li>
                              <li @click="copyMessage(message.message)">
                                Copy
                              </li>
                              <li @click="deleteMessage(message.id)">
                                Delete
                              </li>
                            </ul>
                          </div>
                        </div>
                  </div>
                
                    <span class="box-size-similar">
                      
                      <span
                        class="forward-msg"
                        v-if="message.forward != null"
                      >
                        <img
                          src="/src/assets/arrow.png"
                          alt=""
                          height="15"
                        />
                        Forwarded
                      </span>
                      <span class="reply-msg" v-if="message.get_msg_reply != null && replyMsgId == ''">
                      <span v-if="message.get_msg_reply.message_to ==other_user_id">
                        <p v-if="message.get_msg_by?.id == my_user_id && userType=='user'">You</p>
                        <p v-else-if="userType == 'group'">{{message.get_msg_reply?.get_msg_by?.first_name}} {{message.get_msg_reply?.get_msg_by?.last_name}}</p>
                        <p v-else>{{ message.get_msg_by?.first_name }} {{ message.get_msg_by?.last_name }}</p>
                      </span>
                      <span v-else>
                        <p v-if="message.get_msg_to?.id == my_user_id">You</p>
                        <p v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</p>
                      </span>
                        <span class="attached-file-section" 
                          v-if="message.get_msg_reply.type == 'image' || message.get_msg_reply.type == 'document' || message.get_msg_reply.type == 'video' || message.get_msg_reply.type == 'audio'"
                        >Attached
                        </span>
                         <span v-else>{{ message.get_msg_reply.message }}</span>
                      </span>
                      <span v-bind:class="getClass(message.status)">
                       <span>
                        <div v-if="message.get_msg_by" class="user-name">
                          <span v-if="message.get_msg_by.id == my_user_id">You</span>
                          <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span><span>{{ getTime(message.created_at) }}</span></div>
                      <div v-else class="user-name">
                        <span v-if="message.get_msg_to.id == my_user_id">You</span>
                        <span v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span><span> {{ getTime(message.created_at) }}</span></div>
                        <span class="reply-message-by-me" style="white-space: pre-line" v-html="getText(message.message)"></span>
                      </span>
                      </span>
                      <span
                          :id="`reaction-on-id-${message.id}`"
                        >
                          <template
                            v-if="message.get_msg_reaction.length > 0"
                          >
                           <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                            <!-- <template
                              v-for="(
                                reaction, index
                              ) in message.get_msg_reaction"
                            >
                              {{ reaction.reaction }}
                            </template> -->
                          </template>
                        </span>
                    </span>
                  </div>
                </div>

                <div v-else>
                  <div
                    :class="
                      message.get_msg_reply != null
                        ? 'chat chat-other msg-reply other-msg-reply'
                        : 'chat chat-other'
                    "
                  >
                  <div class="message-actions">
                    <div class="smiley-emoji">
                        <img
                          src="/src/assets/smile.png"
                          @click="this.showReactionPickerFor($event,message.id)"
                          alt=""
                          height="40"
                        />
                      </div>
                      <div class="ellipsis">
                          <div class="hamburger">
                           <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                          </div>
                          <div class="options">
                            <ul>
                              <li @click="forwordMessage(message.id)">
                                Forward
                              </li>
                              <li @click="replyMessage($event, message)">
                                Reply
                              </li>
                              <li @click="copyMessage(message.message)">
                                Copy
                              </li>
                              <li @click="deleteMessage(message.id)">
                                Delete
                              </li>
                            </ul>
                          </div>
                        </div>
                  </div>
                  
                    <span class="box-size-similar">

                      <span
                        class="forward-msg"
                        v-if="message.forward != null"
                      >
                        <img
                          src="/src/assets/arrow.png"
                          alt=""
                          height="15"
                        />
                        Forwarded
                      </span>
                      <span
                        class="reply-msg"
                        v-if="message.get_msg_reply != null">
                        <span v-if="message.get_msg_reply.message_to == other_user_id">
                          <p v-if="message.get_msg_to?.id == my_user_id && userType == 'user'">You</p>
                          <p v-else-if="userType == 'group'">{{message.get_msg_reply?.get_msg_by?.first_name}} {{message.get_msg_reply?.get_msg_by?.last_name}}</p>
                          <p v-else>{{ message.get_msg_to?.first_name }} {{ message.get_msg_to?.last_name }}</p>
                        </span>
                        <span v-else>
                          <p v-if="message.get_msg_by?.id == my_user_id && userType=='user'">You</p>
                          <p v-else>{{ message.get_msg_by?.first_name }} {{ message.get_msg_by?.last_name }}</p>
                        </span>
                        <span class="attached-file-section" 
                          v-if="message.get_msg_reply.type == 'image' || message.get_msg_reply.type == 'document' || message.get_msg_reply.type == 'video' || message.get_msg_reply.type == 'audio'"
                        >Attached</span>
                        <span>{{ message.get_msg_reply.message }}</span>
                      </span>
                      <div style="display: flex">
                      <div
                        v-if="userType == 'group'"
                        class="group-dp small-profile-pic"
                      >
                        <img
                          :src="message.get_msg_by.profile_pic"
                          v-if="message.get_msg_by?.profile_pic != null"
                        />
                        <div v-else-if="message.get_msg_by != null">
                          <h3 class="profile_pic">
                            {{
                              message.get_msg_by.first_name
                                .charAt(0)
                                .toUpperCase()
                            }}{{
                              message.get_msg_by.last_name
                                .charAt(0)
                                .toUpperCase()
                            }}
                          </h3>
                        </div>
                      </div>
                    
                      <span class="reply-other-side-msg">
                        <div v-if="message.get_msg_by" class="user-name">
                        <span v-if="message.get_msg_by.id == my_user_id">You</span>
                        <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span>
                        <span>{{ getTime(message.created_at) }}</span></div>
                       <div v-else class="user-name">
                        <span v-if="message.get_msg_to?.id == my_user_id">You</span>
                        <span v-else>{{ message.get_msg_to?.first_name }} {{ message.get_msg_by?.last_name }} </span>
                       <span> {{ getTime(message.created_at) }}</span></div>
                        <span class="reply-message-by-me" style="white-space: pre-line" v-html="getText(message.message)"></span>
                        <span
                          :id="`reaction-on-id-${message.id}`"
                        >
                          <template
                            v-if="message.get_msg_reaction.length > 0"
                          >
                           <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                            <!-- <template
                              v-for="(
                                reaction, index
                              ) in message.get_msg_reaction"
                            >
                              {{ reaction.reaction }}
                            </template> -->
                          </template>
                        </span>
                        
                      </span>
                      </div>
                    </span>
                    
                  </div>
                </div>
              </div>

              <div v-if="message.type == 'info'">
                <div class="chat-info">
                  <span v-html="getText(message.message)"></span>
                </div>
              </div>

              <div v-if="message.type == 'image'">
                <div
                  :class="
                    message.message_by == my_user_id && replyMsgId == ''
                      ? 'chat-image chat chat-me'
                      : 'chat-image chat chat-other'
                  "
                >
                <div class="message-actions">
                  <div class="smiley-emoji">
                      <img
                        src="/src/assets/smile.png"
                        @click="this.showReactionPickerFor($event,message.id)"
                        alt=""
                        height="40"
                      />
                    </div>
                     <div class="ellipsis">
                        <div class="hamburger">
                          <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                        </div>
                        <div class="options">
                          <ul>
                            <li @click="forwordMessage(message.id)">
                              Forward
                            </li>
                            <li
                              @click="
                                openFile(message.get_chat_file[0].path)
                              "
                            >
                              Open
                            </li>
                            <li @click="replyMessage($event, message)">
                              Reply
                            </li>
                            <li @click="deleteMessage(message.id)">
                              Delete
                            </li>
                          </ul>
                        </div>
                      </div>
                </div>
                 
               
                  <div class="media image">
                    <span
                      class="forward-msg"
                      v-if="message.forward != null"
                    >
                      <img
                        src="/src/assets/arrow.png"
                        alt=""
                        height="15"
                      />
                      Forwarded
                    </span>
                    <span
                      v-bind:class="
                        message.message_by == my_user_id &&
                        replyMsgId == ''
                          ? getClass(message.status)
                          : ''
                      "
                    >
                      <div v-if="message.get_msg_by" class="user-name">
                        <span v-if="message.get_msg_by.id == my_user_id">You</span>
                        <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span>
                        <span>{{ getTime(message.created_at) }}</span></div>
                        <div v-else class="user-name">
                          <span v-if="message.get_msg_to.id == my_user_id">You</span>
                          <span v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span><span> {{ getTime(message.created_at) }}</span></div>
                          <img :src="message.get_chat_file[0] ? message.get_chat_file[0].path : ''" @dblclick="message.get_chat_file[0] && openFile(message.get_chat_file[0].path)" />
                          <!-- <img :src="message.get_chat_file[0].path" @dblclick="openFile(message.get_chat_file[0].path)"/> -->
                      <span
                        v-if="message.message != null"
                        class="chat-file-messag" style="white-space: pre-line" v-html="getText(message.message)"
                        >
                      </span>
                      <span
                        v-else-if="message.get_chat_file[0].file_msg != null"
                        class="reply-message-by-me" style="white-space: pre-line" v-html="getText(message.get_chat_file[0].file_msg)"
                        >
                      </span>
                      <span
                        :id="`reaction-on-id-${message.id}`"
                        
                      >
                        <template v-if="message.get_msg_reaction.length > 0">
                           <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                          <!-- <template
                            v-for="(
                              reaction, index
                            ) in message.get_msg_reaction"
                          >
                            {{ reaction.reaction }}
                          </template> -->
                        </template>
                      </span>
                     
                    </span>
                    
                  </div>
                  
                </div>
              </div>

              <div v-if="message.type == 'video'">
                <div
                  :class="
                    message.message_by == my_user_id && replyMsgId == ''
                      ? 'chat-video chat chat-me'
                      : 'chat-video chat chat-other'
                  "
                >
                <div class="message-actions">
                  <div class="smiley-emoji">
                      <img
                        src="/src/assets/smile.png"
                        @click="this.showReactionPickerFor($event,message.id)"
                        alt=""
                        height="40"
                      />
                    </div>
                    <div class="ellipsis">
                      <div class="hamburger">
                        <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                      </div>
                      <div class="options">
                        <ul>
                          <li @click="forwordMessage(message.id)">
                            Forward
                          </li>
                          <li
                            @click="
                              openFile(message.get_chat_file[0].path)
                            "
                          >
                            Open
                          </li>
                          <li @click="replyMessage($event, message)">
                            Reply
                          </li>
                          <li @click="deleteMessage(message.id)">
                            Delete
                          </li>
                        </ul>
                      </div>
                    </div>
                </div>
                  <div
                    v-bind:class="
                      message.message_by == my_user_id && replyMsgId == ''
                        ? getClass(message.status)+' '+'media video'
                        : 'media video'
                    "
                  >
                    <span
                      class="forward-msg"
                      v-if="message.forward != null"
                    >
                      <img
                        src="/src/assets/arrow.png"
                        alt=""
                        height="15"
                      />
                      Forwarded
                    </span>
                    <div v-if="message.get_msg_by" class="user-name">
                      <span v-if="message.get_msg_by.id == my_user_id">You</span>
                      <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span>
                      <span>{{ getTime(message.created_at) }}</span></div>
                  <div v-else class="user-name">
                    <span v-if="message.get_msg_to.id == my_user_id">You</span>
                    <span v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span>
                    <span> {{ getTime(message.created_at) }}</span></div>
                    <video
                      width="320"
                      controlslist="nofullscreen"
                      controls
                    >
                      <source
                        :src="message.get_chat_file[0].path"
                        type="video/mp4"
                      />
                    </video>
                   
                    <span
                      v-if="message.message != null"
                      class="chat-file-messag" style="white-space: pre-line" v-html="getText(message.message)"
                      >
                    </span>
                    <span
                      :id="`reaction-on-id-${message.id}`"
                     
                    >
                      <template v-if="message.get_msg_reaction.length > 0">
                         <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                        <!-- <template
                          v-for="(
                            reaction, index
                          ) in message.get_msg_reaction"
                        >
                          {{ reaction.reaction }}
                        </template> -->
                      </template>
                    </span>
                    
                    
                  </div>
                  
                </div>
              </div>
              <div v-if="message.type == 'audio'">
                <div
                  :class="
                    message.message_by == my_user_id && replyMsgId == ''
                      ? 'chat-audio chat chat-me'
                      : 'chat-audio chat chat-other'
                  "
                >
                <div class="message-actions">
                  <div class="smiley-emoji">
                      <img
                        src="/src/assets/smile.png"
                        @click="this.showReactionPickerFor($event,message.id)"
                        alt=""
                        height="40"
                      />
                    </div>
                     <div class="ellipsis">
                      <div class="hamburger">
                        <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                      </div>
                      <div class="options">
                        <ul>
                          <li @click="forwordMessage(message.id)">
                            Forward
                          </li>
                          <li @click="replyMessage($event, message)">
                            Reply
                          </li>
                          <li @click="deleteMessage(message.id)">
                            Delete
                          </li>
                        </ul>
                      </div>
                    </div>
                </div>
                  <span
                    v-bind:class="
                      message.message_by == my_user_id && replyMsgId == ''
                        ? getClass(message.status)
                        : ''
                    "
                  >
                    
                    <span
                      class="forward-msg"
                      v-if="message.forward != null"
                    >
                      <img
                        src="/src/assets/arrow.png"
                        alt=""
                        height="15"
                      />
                      Forwarded
                    </span>
                     <div v-if="message.get_msg_by" class="user-name">
                      <span v-if="message.get_msg_by.id == my_user_id">You</span>
                      <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span>
                      <span>{{ getTime(message.created_at) }}</span></div>
                      <div v-else class="user-name">
                        <span v-if="message.get_msg_to.id == my_user_id">You</span>
                        <span v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span><span> {{ getTime(message.created_at) }}</span></div>
                    <audio controls>
                      <source
                        :src="message.get_chat_file[0].path"
                        type="audio/mpeg"
                      />
                    </audio>
                   
                    <span
                      v-if="message.message != null"
                      class="chat-file-messag" style="white-space: pre-line" v-html="getText(message.message)"
                      >
                    </span>
                    <span
                      :id="`reaction-on-id-${message.id}`"
                     
                    >
                      <template v-if="message.get_msg_reaction.length > 0">
                         <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                        <!-- <template
                          v-for="(
                            reaction, index
                          ) in message.get_msg_reaction"
                        >
                          {{ reaction.reaction }}
                        </template> -->
                      </template>
                    </span>
                   
                  </span>
                  
                </div>
              </div>
              <div v-if="message.type == 'document'">
                <div
                  :class="
                    message.message_by == my_user_id && replyMsgId == ''
                      ? 'chat-image chat chat-me'
                      : 'chat-image chat chat-other'
                  "
                >
                <div class="message-actions">
                  <div class="smiley-emoji">
                      <img
                        src="/src/assets/smile.png"
                        @click="this.showReactionPickerFor($event,message.id)"
                        alt=""
                        height="40"
                      />
                    </div>
                     <div class="ellipsis">
                      <div class="hamburger">
                        <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                      </div>
                      <div class="options">
                        <ul>
                          <li @click="forwordMessage(message.id)">
                            Forward
                          </li>
                          <li
                            @click="
                              openFile(message.get_chat_file[0].path)
                            "
                          >
                            Open
                          </li>
                          <li @click="replyMessage($event, message)">
                            Reply
                          </li>
                          <li @click="deleteMessage(message.id)">
                            Delete
                          </li>
                        </ul>
                      </div>
                    </div>
                </div>
                
                <div class="media pdf">
                  <span
                    v-bind:class="
                      message.message_by == my_user_id && replyMsgId == ''
                        ? getClass(message.status)
                        : ''
                    "
                  >
                    <span
                      class="forward-msg"
                      v-if="message.forward != null"
                    >
                      <img
                        src="/src/assets/arrow.png"
                        alt=""
                        height="15"
                      />
                      Forwarded
                    </span>
                     <div v-if="message.get_msg_by" class="user-name">
                      <span v-if="message.get_msg_by.id == my_user_id">You</span>
                      <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span><span>{{ getTime(message.created_at) }}</span></div>
                      <div v-else class="user-name">
                         <span v-if="message.get_msg_to.id == my_user_id">You</span>
                        <span>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span><span> {{ getTime(message.created_at) }}</span></div>
                    <img
                      src="/src/assets/pdficon.png"
                      class="chat-pdf-icon" 
                      @dblclick="openFile(message.get_chat_file[0].path)"
                    />
                 
                    <p
                      v-if="message.message != null"
                      class="chat-file-messag"  style="white-space: pre-line" v-html="getText(message.message)"
                      >
                    </p>
                    <span
                      :id="`reaction-on-id-${message.id}`"
                      
                    >
                      <template v-if="message.get_msg_reaction.length > 0">
                         <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                        <!-- <template
                          v-for="(
                            reaction, index
                          ) in message.get_msg_reaction"
                        >
                          {{ reaction.reaction }}
                        </template> -->
                      </template>
                    </span>
                   
                  </span>
                   
                </div>
                
                </div>
              </div>

              <div
                v-if="message.type == 'media'"
                :class="
                  message.message_by == my_user_id && replyMsgId == ''
                    ? 'chat-image chat chat-me'
                    : 'chat-image chat chat-other'
                "
              >
                <div class="message-actions">
                   <div class="smiley-emoji">
                      <img
                        src="/src/assets/smile.png"
                        @click="this.showReactionPickerFor($event,message.id)"
                        alt=""
                        height="40"
                      />
                    </div>
                      <div class="ellipsis">
                        <div class="hamburger">
                         <img src="/src/assets/ellipsis-vertical.svg" height="40" width="40">
                        </div>
                        <div class="options">
                          <ul>
                            <li @click="forwordMessage(message.id)">
                              Forward
                            </li>
                            <li @click="replyMessage($event, message)">
                              Reply
                            </li>
                            <li @click="deleteMessage(message.id)">Delete</li>
                          </ul>
                        </div>
                      </div>
                </div>
                <span
                  v-bind:class="
                    message.message_by == my_user_id && replyMsgId == ''
                      ? getClass(message.status)
                      : ''
                  "
                >
               
                
                  <div class="media image">
                    <div v-if="message.get_msg_by" class="user-name">
                    <span v-if="message.get_msg_by.id == my_user_id">You</span>
                    <span v-else>{{ message.get_msg_by.first_name }} {{ message.get_msg_by.last_name }} </span><span>{{ getTime(message.created_at) }}</span></div>
                  <div v-else class="user-name">
                    <span v-if="message.get_msg_to.id == my_user_id">You</span>
                    <span v-else>{{ message.get_msg_to.first_name }} {{ message.get_msg_to.last_name }}</span><span> {{ getTime(message.created_at) }}</span></div>
                    <span
                      class="forward-msg"
                      v-if="message.forward != null"
                    >
                      <img
                        src="/src/assets/arrow.png"
                        alt=""
                        height="15"
                      />
                      Forwarded
                    </span>
                    <img :src="message.media_data?.thumbnail" @dblclick="openFile(message.media_data?.media)" />
                     <span
                    v-if="message.message != null"
                    class="chat-file-messag" v-html="getText(message.message)"
                    >
                  </span>
                  </div>
                  
                 
                  <span
                    :id="`reaction-on-id-${message.id}`"
                   
                  >
                    <template v-if="message.get_msg_reaction.length > 0">
                       <span class="reaction-emoji">
                             {{countEmotion(message.get_msg_reaction)}}
                           </span>
                      <!-- <template
                        v-for="(
                          reaction, index
                        ) in message.get_msg_reaction"
                      >
                        {{ reaction.reaction }}
                      </template> -->
                    </template>
                  </span>
                
                </span>
              </div>
            </div>
            <div class="start-conversation" v-else>
              <span class="text-white">Start your conversation</span>
            </div>
          </div>
          <Picker
            v-if="showReactionPicker"
            :data="emojiIndex"
            set="twitter"
            id="picker-selector"
            @select="this.selectEmoji"
          />
          <div class="image-preview" v-if="previewImage">
            <v-btn @click="closePreview">+</v-btn>
            <div class="img-wrapper">
              <v-img :width="200" cover :src="previewImage"></v-img>
            </div>
          </div>

          <div
            class="reply-msg-text"
            id="reply-msg-text"
            style="display: none"
          >
            <span class="reply-to reply-to-msg" id="reply-user"></span>
            <span @click="closeReply" class="close-reply">+</span>
            <span class="reply-to reply-to-msg" id="reply-msg"></span>
          </div>

          <footer class="message-footer">
            <div
              v-if="
                isTyping &&
                typingUserInfo != '' &&
                other_user_id != '' &&
                other_user_id == userTypingId &&
                !isBlocked
              "
            >
              <span class="typer-name-footer">{{this.typerName}}</span>
              <ul class="typing-event">
                <li></li>
                <li></li>
                <li></li>
              </ul>
            </div>
            <div class="message-footer-inner" v-if="userName != ''">
               <editor-content ref="editor" :editor="editor" v-model="typeMessage" @keydown="typedData($event)" id="type_message"  class="start-typing"/>
              <!-- <button @click="selectText">Select Text</button> -->
              <button @click="toggleTypographyActions" class="toggle-typography">A</button>
              <div :data-open="this.typographyActions" class="typography-actions">
                <ul>
                  <li @click="editor.chain().focus().toggleBold().run()">
                   <v-img src="/src/assets/bold.png" height="12"></v-img>
                  </li>
                  <li  @click="editor.chain().focus().toggleItalic().run()">
                    <v-img src="/src/assets/italic.png" height="12"></v-img>
                  </li>
                  <li @click="editor.chain().focus().toggleUnderline().run()">
                    <v-img src="/src/assets/underline.png" height="12"></v-img>
                  </li>
                  <!-- <li @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
                    <v-img src="/src/assets/list.png" height="12"></v-img>
                  </li>
                  <li @click="editor.chain().focus().toggleCode().run()">
                    <v-img src="/src/assets/link.png" height="12"></v-img>
                  </li> -->
                </ul>
              </div>
              <!-- <textarea
              class="start-typing"
              v-model="typeMessage"
              id="type_message"
              ref="textarea" type="text"
              @input="handleInput"
              @keydown="typedData($event)"
              autocomplete="off"
              placeholder="Type message here"
              :style="{ height: `${textareaHeight}px`, overflowY: 'hidden' }"> -->
              <!-- <textarea
                  class="start-typing"
                  type="text"
                  name="type_message"
                  ref="textarea"
                  @paste="onPasteSetHeight"
                  v-model="typeMessage"
                  id="type_message"
                  :style="{ height: textareaHeight + 'px' }"
                  @input="adjustTextareaHeight"
                  @keydown="typedData($event)"
                  autocomplete="off"
                  placeholder="Type message here"
                > -->
              <!-- </textarea> -->
              <div>
                <ul class="message-footer-actions">
                  <input
                    type="file"
                    style="display: none"
                    ref="imgInput"
                    @change="handleImgUpload"
                    id="img-video-input"
                    accept="image/*, video/*"
                  />
                  <li title="Gallery" v-if="hideMediaIcons">
                    <v-btn @click="openFileInput">
                      <v-img
                        src="/src/assets/gallery.svg"
                        height="20px"
                      ></v-img>
                    </v-btn>
                  </li>
                  <input
                    type="file"
                    style="display: none"
                    ref="anyFileInput"
                    @change="handleAnyFileUpload"
                    accept="application/pdf"
                    id="file-document-input"
                  />
                  <li title="Documents" v-if="hideMediaIcons">
                    <v-btn @click="openAnyFileInput">
                    <v-img
                      src="/src/assets/attachment.png"
                      height="20px"
                    ></v-img>  
                    </v-btn>
                  </li>
                  <li>
                  <v-btn
                  class="form-control"
                  @click="typedData($event)"
                    v-if="!sendMediaBtn &&
                      !showSendButton">
                  <v-img src="/src/assets/send-message.png"></v-img>
                </v-btn>
                <v-btn
                 id="send-file-btn"
                  class="form-control"
                  @click="handleMediaSend"
                  v-else-if="sendMediaBtn"
                >
                  <v-img src="/src/assets/send-message.png"></v-img>
                </v-btn>
                <v-btn
                  id="send-file-btn"
                  class="form-control"
                  @click="typedData($event)"
                  v-else-if="showSendButton"
                >
                  <v-img src="/src/assets/send-message.png"></v-img>
                </v-btn>
                <v-btn
                  id="send-file-btn"
                  class="form-control"
                  v-else="showSendButton"
                >
                  <v-img src="/src/assets/send-message.png"></v-img>
                </v-btn>
                  </li>
                </ul>

              </div>
            </div>
          </footer>
        </div>
      <!-- Chat Detail Extended View End  -->
      <ChatList v-if="openChatList" :id="mId" @close="closeList"/>
      <GroupInfo v-if="isPopupOpen" :id="group_id" @closePopup="closePopup"
       @addParticipant="openFriendPopup"
       @edit="openEditPopup"/>
      <!-- Create Group Popup -->
      <CreateGroup v-if="isCreatePopup" @closePopup="isCreatePopup = false" @groupId="closeCreatePopup" />
      <FriendsList v-if="isFriendPopupOpen" :id="group_id"  @close="friendClosePopup" 
      @groupId="friendClosePopupwithId" />
      <EditGroup v-if="isEditPopupOpen" :id="group_id" @close="editClosePopup" @editId="editClosePopupwithId" />
    </div>
    </section>
  </v-app>
</template>
<script>
import ChatList from "./ChatList.vue";
import FriendsList from '../fellowship/FriendsList.vue'; 
import EditGroup from '../fellowship/EditGroup.vue'; 
import GroupInfo from '../fellowship/GroupInfo.vue'; 
import CreateGroup from '../fellowship/CreateGroup.vue'; 
import axios from "../../router/axios.js";
import { Picker, EmojiIndex } from "emoji-mart-vue-fast/src";
import data from "emoji-mart-vue-fast/data/all.json";
import "emoji-mart-vue-fast/css/emoji-mart.css";
import $ from "jquery";
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import CustomParagraph from './CustomParagraph'
import logOut from "@/middleware/logout";
import Header from "../layout/Header.vue";
let emojiIndex = new EmojiIndex(data);
let timeoutId = 0;

export default {
  data() {
    return {
      moreLoad:false,
      Load:false,
      textareaHeight: 40,
      nameHeader:false,
      mId:'',
      openChatList:false,
      isEditPopupOpen:false,
      isFriendPopupOpen:false,
      group_id:null,
      isPopupOpen: false,
      isCreatePopup: false,
      showLoaderOnList:true,
      typerName:'',
      lastMessageId:'',
      lastMessageReact:'',
      checker:false,
      hideMediaIcons: true,
      userDetails: [],
      groupData: [],
      messageList: [],
      searchMessageList: [],
      searchText: "",
      userName: "",
      other_user_id: "",
      userProfile: "",
      userStatus: false,
      userType: "group",
      userMessages: [],
      my_user_id: "",
      typeMessage: "",
      renderComponent: true,
      isTyping: false,
      isBlocked: false,
      isOnline: false,
      userTypingId: "",
      page: 1,
      frndListPage: 1,
      last_page:1,
      replyMsgId: "",
      replyMsgIdCk: "",
      selectedFile: null,
      fileType: "",
      groupTyping: false,
      typingUserInfo: [],
      emojiIndex: emojiIndex,
      mediaData: [],
      isLoading: false,
      showSendButton: false,
      sendMediaBtn: false,
      previewImage: null,
      showReactionPickerId: "",
      showReactionPicker: false,
      arr: [],
      arrFrnd: [],
      previousOption: "",
      toggle_id: "",
      favApi:false,
      favfrndListPage:1,
      height:40,
      goTop: false,
      typographyActions: false,
    };
  },

  components: {
    Picker,
    Header,
    GroupInfo,
    CreateGroup,
    FriendsList,
    EditGroup,
    ChatList,
    EditorContent
  },
  mounted() {
     this.editor = new Editor({
      content: '',
      extensions: [
        StarterKit,
        Underline,
        CustomParagraph,
      ],
    })




    const obj = document.querySelector("#chat-div");
    let prevScrollTop = obj.scrollTop;
    obj.addEventListener('scroll', () => {
    const scrollTop = obj.scrollTop;
    if (scrollTop < prevScrollTop && scrollTop <= 10 && !this.moreLoad && scrollTop !== 0) {
    console.log("Scrolling up, near top");
    this.moreLoad = true;
    const previousScrollHeight = obj.scrollHeight;
    this.getChatDetail(
      this.other_user_id,
      this.isOnline ? "yes" : "no",
      this.userType,
      this.userName,
      this.userProfile,
      this.isBlocked
    ).then(() => {
      this.moreLoad = false;
      const newScrollHeight = obj.scrollHeight;
      obj.scrollTop = newScrollHeight - previousScrollHeight;
    });
   }
   prevScrollTop = scrollTop;
   });








    axios.post("update-on-chat-screen", {user_id: null });
    this.arrFrnd = [];
    this.selectedFile=null;
    this.showReactionPicker = false;
    if (this.$route.query.user_id && !this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("user-data?id=" + atob(this.$route.query.user_id), {})
        .then((response) => {
          this.userDetails = response.data.data;
          this.nameHeader = true;
          this.getChatDetail(
            atob(this.$route.query.user_id),
            this.userDetails.is_online,
            "user",
            this.userDetails.first_name + " " + this.userDetails.last_name,
            this.userDetails.profile_pic,
            this.isBlocked
          );
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    } else if (this.$route.query.group_id && !this.$route.query.media_id) {
      this.messagePage();
      axios
        .post("group-view", {
          other_id: atob(this.$route.query.group_id),
        })
        .then((response) => {
          this.groupData = response.data.data.group;
          this.nameHeader = true;
          this.getChatDetail(
           atob(this.$route.query.group_id),
            "no",
            "group",
            this.groupData.name,
            this.groupData.image
          );
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
        });
    } else if (this.$route.query.group_id && this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("get-content/" + this.$route.query.media_id)
        .then((response) => {
          this.sendMediaBtn = true;
          this.mediaData = response.data.data;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
      axios
        .post("group-view", {
          other_id: atob(this.$route.query.group_id),
        })
        .then((response) => {
          this.groupData = response.data.data.group;
          this.getChatDetail(
            atob(this.$route.query.group_id),
            "no",
            "group",
            this.groupData.name,
            this.groupData.image
          );
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    } else if (this.$route.query.user_id && this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("get-content/" + this.$route.query.media_id)
        .then((response) => {
          this.sendMediaBtn = true;
          this.mediaData = response.data.data;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
      axios
        .get("user-data?id=" + atob(this.$route.query.user_id), {})
        .then((response) => {
          this.userDetails = response.data.data;
          this.getChatDetail(
            atob(this.$route.query.user_id),
            this.userDetails.is_online,
            "user",
            this.userDetails.first_name + " " + this.userDetails.last_name,
            this.userDetails.profile_pic,
            this.isBlocked
          );
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    } else {
      this.messagePage();
    }
    this.socketcall();
    this.user();
    this.joinToSocket();
    this.$socket.on("connect", this.joinToSocket);
    window.addEventListener('beforeunload',this.removeSocket);
  },

  beforeDestroy(){
    window.addEventListener('beforeunload', this.removeSocket);
  },

  created() {
    window.addEventListener('beforeunload', this.removeSocket);
    this.joinToSocket();
    this.$socket.on("connect", this.joinToSocket);
  },


  methods: {
    countEmotion(reactions){
       const reactionCounts = {};
      reactions.forEach(element => {
        const reaction = element.reaction;
        if (reactionCounts[reaction]) {
            reactionCounts[reaction]++;
        } else {
            reactionCounts[reaction] = 1;
        }
    });
    const formattedReactions = [];
    for (const reaction in reactionCounts) {
        if (reactionCounts[reaction] > 1) {
            formattedReactions.push(`${reaction}${reactionCounts[reaction]}`);
        } else {
            formattedReactions.push(reaction);
        }
    }
    return formattedReactions.join('  ');
    },
     stripHtmlTags(html) {
      const div = document.createElement('div');
      div.innerHTML = html;
      return div.innerText;
    },
    handleInput() {
      this.$refs.textarea.style.height = 'auto';
      this.$refs.textarea.style.height = (this.$refs.textarea.scrollHeight) + 'px';
      this.textareaHeight = this.$refs.textarea.scrollHeight;
    },
    userData(user_id, userType){
      if(userType=='user'){
        axios
        .get("user-data?id="+user_id, {})
        .then((response) => {
          this.userDetails = response.data.data;
          this.nameHeader = true;
        });
      }
      else{
        axios
        .post("group-view", {
          other_id: user_id,
        })
        .then((response) => {
          this.groupData = response.data.data.group;
          this.nameHeader = true;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
        });
      }
    },
    closeList(){
      this.openChatList=false;
    },
    openCreatePopup() {
      this.isCreatePopup= true;
    },
    closeCreatePopup(id)
    {
      this.group_id=id;
      this.isCreatePopup = false;
      this.isPopupOpen = true;
      this.updateUserChatListOnMount();
    },
    openPopup(id) {
      $('.options').hide();
      event.preventDefault();
      event.stopPropagation();
      this.group_id=id;
      this.isPopupOpen = true;
    },
    closePopup() {
      this.isPopupOpen = false;
    },
     openFriendPopup(g_id) {
      this.group_id=g_id;
      this.isPopupOpen = false;
      this.isFriendPopupOpen = true;
    },
    friendClosePopup() {
      this.isFriendPopupOpen = false;
      this.isPopupOpen = true;
    },
    friendClosePopupwithId(id) {
      this.group_id=id;
      this.isFriendPopupOpen = false;
      this.isPopupOpen = true;
     
    },
    // edit popup
    openEditPopup(g_id) {
      this.group_id=g_id;
      this.isPopupOpen = false;
      this.isEditPopupOpen=true;
    },
    editClosePopup() {
      this.isEditPopupOpen=false;
    },
    editClosePopupwithId(id) {
      this.updateUserChatListOnMount();
      this.group_id=id;
      this.userData(this.group_id, "group");
      this.isEditPopupOpen=false;
      this.isPopupOpen = true;
    },
    onPasteSetHeight(){
      const textarea = this.$refs.textarea;
      this.textareaHeight = textarea.scrollHeight;
    },
    removeSocket(){
      this.removeToSocket();
    },
    reactionPannelChecker(e){
      if(this.checker && !document.getElementById('picker-selector').contains(e.target)){
        this.showReactionPicker = false;
        this.checker = false;
        this.showReactionPickerId = "";
        e.preventDefault();
      }
    },
    clearSearch(){
      $("#search").val("");
      $("#search").focus();
      this.searchThis();
    },
    showMenu(event,chat_id) {
      if (this.toggle_id == chat_id) {
        $("#option-id-" + chat_id).toggle();
      } else {
        $(".options").hide();
        $("#option-id-" + chat_id).toggle();
      }
      this.toggle_id = chat_id;
      event.preventDefault();
    },

    showReactionPickerFor(e,message_id) {
      if (this.showReactionPickerId != "") {
        this.showReactionPickerId = "";
        this.showReactionPicker = false;
      } else {
        this.showReactionPicker = true;
        this.checker = true;
        this.showReactionPickerId = message_id;
        e.stopPropagation();
        e.preventDefault();
      }
    },
    closePreview() {
      this.previewImage = false;
      this.showSendButton = false;
      this.sendMediaBtn = false;
    },
    closeReply() {
      this.hideMediaIcons = true;
      this.replyMsgIdCk = "";
      this.replyMsgId = "";
      document.getElementById("reply-msg-text").style.display = "none";
    },
    handleMediaSend() {
      if (this.$route.query.user_id && this.$route.query.media_id) {
        axios
          .post("user-store-message-web", {
            chat_type: "user",
            type: "media",
            other_id: atob(this.$route.query.user_id),
            message: this.typeMessage,
            media_id: this.$route.query.media_id,
          })
          .then((response) => {
            console.log("handlemediaif");
            this.selectedFile = null;
            this.sendMediaBtn = false;
            this.textareaHeight = 40;
          })
          .catch(async (error) => {
             this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
             console.log("Error fetching data:", error);
              if(error.response.data.status_code == 401)
              {
                await logOut(error);
              }
          });
      } else if (this.$route.query.group_id && this.$route.query.media_id) {
        axios
          .post("user-store-message-web", {
            chat_type: "group",
            type: "media",
            other_id: atob(this.$route.query.group_id),
            message: this.typeMessage,
            media_id: this.$route.query.media_id,
          })
          .then((response) => {
            console.log("handlemediaelse");
            this.selectedFile = null;
            this.sendMediaBtn = false;
            this.textareaHeight = 40;
          })
          .catch(async (error) => {
            this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
             console.log("Error fetching data:", error);
              if(error.response.data.status_code == 401)
              {
                await logOut(error);
              }
          });
      }
    },

    selectEmoji(emoji) {
      var chat_data_id = this.showReactionPickerId;
      this.showReactionPicker = false;
      this.showReactionPickerId = "";
      axios
        .post("user-react-chat", {
          chat_type: this.userType,
          other_id: this.other_user_id,
          chat_data_id: chat_data_id,
          reaction: emoji.native,
        })
        .then((response) => {
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    openAnyFileInput() {
      this.showReactionPicker = false;
      document.getElementById("file-document-input").value = null;
      this.$refs.anyFileInput.click();
    },
    handleAnyFileUpload(event) {
      document.getElementById("type_message").focus();
      this.selectedFile = event.target.files[0];
      if (
        !this.selectedFile.type.includes("audio") &&
        !this.selectedFile.type.includes("application/pdf")
      ) {
        this.$toast.error("Please Select Pdf File Only.", {
          duration: 4000, // Duration in milliseconds
          position: "top-right", // Toast position
          theme: "sugar", // Toast theme
        });
        event.preventDefault();
      } else {
        if (this.selectedFile.type.includes("audio")) {
          this.fileType = "audio";
          const reader = new FileReader();
          reader.onload = (event) => {
            this.previewImage = "/src/assets/audio-preview.png";
          };

          if (this.selectedFile) {
            reader.readAsDataURL(this.selectedFile);
          }
        }
        if (this.selectedFile.type.split("/")[0] == "application") {
          this.fileType = "document";
          const reader = new FileReader();
          reader.onload = (event) => {
            this.previewImage = "/src/assets/pdficon.png";
          };
          if (this.selectedFile) {
            reader.readAsDataURL(this.selectedFile);
          }
        } else {
          this.fileType = this.selectedFile.type.split("/")[0];
        }
        this.showSendButton = true;
      }
    },
    openFileInput() {
      this.showReactionPicker = false;
      document.getElementById("img-video-input").value = null;
      this.$refs.imgInput.click();
    },
    handleImgUpload(event) {
      document.getElementById("type_message").focus();
      this.selectedFile = event.target.files[0];
      if (
        !this.selectedFile.type.includes("video") &&
        !this.selectedFile.type.includes("image")
      ) {
        this.$toast.error("Please Select Image and Video File Only.", {
          duration: 4000, // Duration in milliseconds
          position: "top-right", // Toast position
          theme: "sugar", // Toast theme
        });
        event.preventDefault();
      } else {
        if (this.selectedFile.type.includes("video")) {
          if (this.selectedFile.size / 1024 / 1024 > 30) {
            this.$toast.error(
              "Size is too large of this file. Max file size is 30 MB.",
              {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              }
            );
            event.preventDefault();
            return false;
          } else {
            this.fileType = "video";
            const reader = new FileReader();
            reader.onload = (event) => {
              this.previewImage = "/src/assets/video-preview.png";
            };
            if (this.selectedFile) {
              reader.readAsDataURL(this.selectedFile);
            }
          }
           this.showSendButton = true;
        }

        if (this.selectedFile.type.includes("image")) {
          if (this.selectedFile.size / 1024 / 1024 > 10) {
            this.$toast.error(
              "Size is too large of this file. Max file size is 10 MB.",
              {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              }
            );
            event.preventDefault();
            return false;
          }else{
            this.fileType = "image";
            const reader = new FileReader();
            reader.onload = (event) => {
              this.previewImage = event.target.result;
            };
            if (this.selectedFile) {
              reader.readAsDataURL(this.selectedFile);
            }
            this.showSendButton = true;
          }
        }
      }
    },
    handleFileUploadSend(event) {
      this.showSendButton = false;
      this.previewImage = null;
      const formData = new FormData();
      formData.append("file", this.selectedFile);
      formData.append("chat_type", this.userType);
      formData.append("type", this.fileType);
      formData.append("other_id", this.other_user_id);
      formData.append("message", this.typeMessage);
      formData.append("file_msg", this.typeMessage);
      this.typeMessage = "";
      axios
        .post("user-store-message-web", formData)
        .then((response) => {
          this.selectedFile = null;
          this.updateUserChatList();
          this.textareaHeight = 40;
        })
        .catch(async (error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    deleteMessage(msg_id) {
      axios
        .post("user-delete-message", {
          chat_id: msg_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
          this.updateUserDetailListNoLoad();
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    copyMessage(msg) {
      this.$toast.success("Message copied", {
        duration: 4000, // Duration in milliseconds
        position: "top-right", // Toast position
        theme: "sugar", // Toast theme
      });
      navigator.clipboard.writeText(msg);
      this.showCopyMessage = true; // Show the message
      setTimeout(() => {
        this.showCopyMessage = false; // Hide the message after 2 seconds (adjust as needed)
      }, 2000);
    },
    replyMessage(event, msg) {
      this.hideMediaIcons = false;
      if (msg.get_chat_file.length > 0) {
        document.getElementById("reply-msg").textContent = "Attachment";
      } else {
        document.getElementById("reply-msg").textContent = msg.message.substring(0,20)+"..";
      }
      document.getElementById("type_message").focus();
      document.getElementById("reply-msg-text").style.display = "block";

      document.getElementById("reply-user").textContent =
        msg.get_msg_by.id == this.my_user_id ? 'You' : msg.get_msg_by.first_name;
      this.replyMsgIdCk = msg.id;
      event.preventDefault();
    },
    openFile(file_url) {
      window.open(file_url, "_blank");
    },
    forwordMessage(m_id) {
      this.mId=m_id;
      this.openChatList=true;
    },
    muteUserList(other_id) {
      $(".options").hide();
      axios
        .post("user-mute-chat-list", {
          other_id: other_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    clearChatList(id) {
      $(".options").hide();
      axios
        .post("user-clear-chat-list", {
          id: id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
          this.updateUserDetailListNoLoad();
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    pinUserList(other_id,nomsg=false) {
      $(".options").hide();
      axios
        .post("user-pin-chat-list", {
          other_id: other_id,
        })
        .then((response) => {
          if(nomsg == false) {
            this.$toast.success(response.data.message, {
              duration: 4000, // Duration in milliseconds
              position: "top-right", // Toast position
              theme: "sugar", // Toast theme
            });
          }
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    deleteChatList(id) {
      $(".options").hide();
      this.pinUserList(this.other_user_id,true);
      axios
        .post("user-delete-chat-list", {
          id: id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.$router.replace({ query: {} });
          this.userName = "";
          this.userProfile = "";
          this.isOnline = false;
          this.userMessages = [];
          this.previewImage = false;
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    scrollListView(event) {
      var obj = document.getElementById("list-view");
      if (this.goTop == true) {
        obj.scrollTop = 0;
        this.goTop = false;
        this.favfrndListPage = 1;
      } else {
        console.log("coming in else of goto");
        if(this.favApi == true)
          {
          console.log("fav");
          console.log("scrolled");
          console.log("event", event);
          console.log("scrollTop", obj.scrollTop);
          console.log("scrollHeight", obj.scrollHeight)
          console.log("offsetHeight", obj.offsetHeight);
          console.log("currentPage", this.favfrndListPage);
          console.log("last_page", this.last_page);
          console.log("Load", this.Load);
          console.log(obj.scrollTop, (obj.scrollHeight - obj.offsetHeight));
          if ((parseInt(obj.scrollTop + 2) >= (obj.scrollHeight - obj.offsetHeight)) 
          && this.favfrndListPage != this.last_page && !this.Load  ) {
            this.Load = true;
          if (this.arrFrnd.length >= 0 ) {
            this.favfrndListPage++;
              axios 
                .post("fav-group-chat-list-web",{page:this.favfrndListPage})
                .then((response) => {
                  console.log("fav-group-Response", response);
                  if(this.favfrndListPage != 1)
              {
                // this.Load = true;
                var arr = response.data.data.data;
                this.messageList = this.messageList.concat(arr);
                this.last_page = response.data.data.last_page;
                this.Load = false;
              }
              else
              {
                this.messageList = response.data.data.data;
                this.last_page = response.data.data.last_page;
                console.log("fav-last-page", response.data.data.last_page);
                this.Load = false;
              }
                  // this.arrFrnd = response.data.data.data;
                  // if (this.arrFrnd.length == 0) {
                  //   this.favfrndListPage = this.favfrndListPage - 1;
                  // } else {
                  //   console.log(this.messageList);
                  //   this.messageList = this.messageList.concat(this.arrFrnd);
                  // }
                })
                .catch(async (error) => {
                  console.log("Error fetching data:", error);
                  if(error.response.data.status_code == 401)
                  {
                    await logOut(error);
                  }
                });
          }
          }
        }
        else
        { 
          console.log("other");
          console.log("scrolled");
          console.log("event", event);
          console.log("scrollTop", obj.scrollTop);
          console.log("scrollHeight", obj.scrollHeight)
          console.log("offsetHeight", obj.offsetHeight);
          console.log("currentPage", this.frndListPage);
          console.log("last_page", this.last_page);
          console.log("Load", this.Load);
          
          console.log(obj.scrollTop, (obj.scrollHeight - obj.offsetHeight));
          if ((parseInt(obj.scrollTop + 2) >= (obj.scrollHeight - obj.offsetHeight)) 
          && this.frndListPage != this.last_page && !this.Load  ) {
          this.Load = true;
          this.frndListPage++;
          axios
            .post("other-group-chat-list-web",{page:this.frndListPage})
            .then((response) => {
              console.log("other-group-Response", response);
              if(this.frndListPage > 1)
            {
              // this.Load = true;
              var arr = response.data.data.data;
              this.messageList = this.messageList.concat(arr);
              this.Load = false;
            }
            else
            {
              this.Load = true;
              this.messageList = response.data.data.data;
              this.last_page = response.data.data.last_page;
              console.log("other-last-page", response.data.data.last_page);
              this.Load = false;
            }
              // this.arrFrnd = response.data.data.data;
              // if (this.arrFrnd.length == 0) {
              //   this.frndListPage = this.frndListPage - 1;
              //   this.Load = false;
              // } else {
              //   this.messageList = this.messageList.concat(this.arrFrnd);
              //   this.Load = false;
              // }

            })
            .catch(async (error) => {
              console.log("Error fetching data:", error);
              if(error.response.data.status_code == 401)
              {
                await logOut(error);
              }
            });
        
            }
        }
      }
    },
    getTime(time) {
      var inputDate = new Date(time);
      var min =
        inputDate.getMinutes() <= 9
          ? "0" + inputDate.getMinutes()
          : inputDate.getMinutes();
      var hour =  inputDate.getHours() <= 9
          ? "0" + inputDate.getHours()
          : inputDate.getHours();
      var time = hour + ":" + min;
      var time2 = this.tConvert(time);
      return time2;
    },
    tConvert(time) {
      // Check correct time format and split into components
      time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
      if (time.length > 1) {
        // If time format correct
        time = time.slice(1); // Remove full string match value
        time[5] = +time[0] < 12 ? " AM" : " PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
      }
      return time.join(""); // return adjusted time or original string
    },
    getClass(msg_status) {
      if (msg_status == "seen") {
        return "message-seen";
      } else if (msg_status == "sent") {
        return "message-sent";
      } else if (msg_status == "delivered") {
        return "message-sent";
      } else {
        return "message-deleted";
      }
    },
    joinToSocket() {
      const { id } = this.$socket;
      if (id) {
        axios.post("user-socket-join", { socket_id: id });
      }
    },
    removeToSocket() {
      const { id } = this.$socket;
      if (id) {
        axios.post("user-socket-remove", { socket_id: id });
      }
    },
    getCaret(el) {
      if (el.selectionStart) {
        return el.selectionStart;
      } else if (document.selection) {
        el.focus();
        var r = document.selection.createRange();
        if (r == null) {
          return 0;
        }
        var re = el.createTextRange(),
          rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint("EndToStart", re);
        return rc.text.length;
      }
      return 0;
    },
    async typedData(event)
     { 
          var target = $(".tiptap");
          var firstParagraph = target.find('p:first-child');
          var content = firstParagraph.html();
          const sendMessage = async () => {
          this.typeMessage = content;
          if (this.typeMessage.trim() !== "") {
          target.html(''); //to clear the editor content
          if (this.selectedFile != null) {
            await this.handleFileUploadSend();
          } else {
            await this.storeMsg();
          }
          }
          };
        if (event.type === 'click') {
          this.selectText();
          await sendMessage();
          return;
        }
         
       if (event.keyCode == 13 && this.selectedFile != null) {
               if (event.keyCode == 13) {
                this.typeMessage = content;
                 var caret = this.getCaret(event);
                 if (event.shiftKey) {
                     content.substring(caret, content.length);
                     var str = this.typeMessage;
                     var trimmedStr = str.trimStart();
                     this.typeMessage = trimmedStr;
                 } 
                 else {
                   event.target.value =
                   content?.substring(0, caret - 1) +
                   content?.substring(caret, content.length);
                   event.stopPropagation();
                   event.preventDefault();
                   this.selectText();
                   document.getElementById("send-file-btn").click();
                   event.target.innerHTML= "";
                 }
               } 
       }
       else
       {
               if (event.keyCode == 13) {   
                 event.preventDefault();
                 this.typeMessage = content;             
                 var caret = this.getCaret(event);
                 if (event.shiftKey) {
                     console.log("shiftkey");
                     content.substring(caret, content.length);
                     var str = this.typeMessage;
                     var trimmedStr = str.trimStart();
                     this.typeMessage = trimmedStr;
                 } 
                 else {
                   event.preventDefault();
                   event.target.innerText =
                   content?.substring(0, caret - 1) + 
                   content?.substring(caret, content.length);
                   event.preventDefault();
                   if (this.typeMessage.trim() != "" ) {
                     event.target.innerHTML= "";
                     this.selectText();
                     await this.storeMsg();
                   }
                    event.preventDefault();
                  }
               } 
         axios
          .post("user-typing-status-web", {
            chat_type: this.userType,
            other_id: this.other_user_id,
          })
          .then((response) => {})
          .catch(async (error) => {
            console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
          });
      }
    },
    selectText() {
      if (this.editor) {
        const docSize = this.editor.state.doc.content.size;
        this.editor.commands.setTextSelection({
          from: 0, // Start position (0-based index)
          to: docSize // End position (inclusive of all content)
        });
        this.editor.commands.focus(); // Focus the editor to make the selection visible
      }
    },
    async sendMessageEvent(response) {
      $('#type_message').css('height','auto');
      $('#type_message').css('height',40 + 'px');
      const collection = document.getElementsByClassName("chat-with");
      //New message insert into message list
      let message = response.data;
      if (this.userMessages.length > 0) {
        await this.userMessages.splice(0, 0, message.data);
      } else {
        await this.userMessages.push(message.data);
      }
      if (collection.length > 0) {
        collection[0].scrollTo(0, collection[0].scrollHeight);
      }
    },

    async sendTypingEvent(response) {
      this.userTypingId = response.data.data.other_id;
      this.typingUserInfo = response.data.data?.get_msg_by;
      this.isTyping = true;
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        this.isTyping = false;
        this.userTypingId = "";
      }, 1000);
    },
    updateUserChatList() {
      this.favApi = false;
      console.log(this.favApi);
      this.frndListPage = 1;
      $("#fav-group").removeClass('active');
      $("#other-group").addClass('active');
      axios
        .post("other-group-chat-list-web")
        .then((response) => {
          this.showLoaderOnList = false;
          this.messageList = response.data.data.data;
          this.last_page = response.data.data.last_page;
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    updateUserChatListOnMount() {
      if(this.favApi == false){
      this.showLoaderOnList = true;
      $("#fav-group").removeClass('active');
      $("#other-group").addClass('active');
      this.frndListPage = 1;
      axios
        .post("other-group-chat-list-web")
        .then((response) => {
          console.log(response);
          this.showLoaderOnList = false;
          this.messageList = response.data.data.data;
          this.last_page = response.data.data.last_page;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        }); 
      }
      else{
      this.frndListPage = 1;
      $("#other-group").removeClass('active');
      $("#fav-group").addClass('active');
      axios
        .post("fav-group-chat-list-web")
        .then((response) => {
          console.log(response);
          this.showLoaderOnList = false
          this.messageList = response.data.data.data;
          this.last_page = response.data.data.last_page;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
      }
      
    },
    favGroup() {
      this.favApi = true;
      console.log(this.favApi);
      this.frndListPage = 1;
      $("#other-group").removeClass('active');
      $("#fav-group").addClass('active');
      axios
        .post("fav-group-chat-list-web")
        .then((response) => {
          console.log(response);
          this.showLoaderOnList = false
          this.messageList = response.data.data.data;
          this.last_page = response.data.data.last_page;
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    updateUserDetailListNoLoad() {
      this.isLoading = false;
      axios
        .post("user-detail-message", {
          user_id: this.other_user_id,
          type: this.userType,
          page: 'all',
        })
        .then((response) => {
          this.userMessages = response.data.data;
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },
    updateUserDetailList() {
      this.isLoading = true;
      axios
        .post("user-detail-message", {
          user_id: this.other_user_id,
          type: this.userType,
          page: 'all',
        })
        .then((response) => {
          this.arr = response.data.data;
          if (this.arr.length == 0) {
            this.page = this.page - 1;
          } else {
            this.userMessages = this.userMessages.concat(this.arr);
          }
          this.isLoading = false;
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },

    socketcall() {
      this.$socket.on("ChatLara.App\\Events\\ChatDetailReaction", (event) => {
        console.log("ChatDetailReaction event");
        axios
        .post("user-detail-message", {
          user_id: this.other_user_id,
          type: this.userType,
          page: 'all',
        })
        .then((response) => {
          this.userMessages = response.data.data;
        })
        .catch(async (error) => {
           console.log("Error fetching data:", error);
        });
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxOffline", (event) => {
        console.log("ChatInboxOffline");
        this.favApi == true ? this.favGroup() : this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxMessage", (event) => {
        console.log("ChatInboxMessage");
        this.favApi == true ? this.favGroup() : this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxOnline", (event) => {
        console.log("ChatInboxOnline");
        this.favApi == true ? this.favGroup() : this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxTyping", (event) => {
        this.sendTypingEvent(event);
      });
      this.$socket.on("ChatLara.App\\Events\\ChatNewMessage", (event) => {
        console.log("ChatNewMessage event");
        this.updateUserDetailListNoLoad();
        this.sendMessageEvent(event);
        this.favApi == true ? this.favGroup() : this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailMsgStatus", (event) => {
        console.log("ChatDetailMsgStatus event");
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxMsgStatus", (event) => {
        console.log("ChatInboxMsgStatus event");
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailTyping", (event) => {
        this.typerName = event.data.data?.get_msg_by.first_name;
        this.sendTypingEvent(event);
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailOnline", (event) => {
        console.log("ChatDetailOnline event, ");
        this.updateUserDetailListNoLoad();
        if (this.other_user_id == event?.data?.data?.other_id) {
          this.isOnline = true;
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        } else {
          this.isOnline = false;
        }
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailOffline", (event) => {
        console.log("ChatDetailOffline event");
        if (this.other_user_id == event?.data?.data?.other_id) {
          this.isOnline = false;
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        }
      });
    },

    user() {
      axios
        .get("user-profile")
        .then((response) => {
          this.my_user_id = response.data.data.id;
        })
        .catch(async (error) => {
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
    },

    storeMsg() {
      var msg = this.typeMessage;
      this.typeMessage = "";
      this.hideMediaIcons = true;
      const reply_message_id = this.replyMsgIdCk;
      if (this.replyMsgIdCk != "") {
        document.getElementById("reply-msg-text").style.display = "none";
        axios
          .post("user-store-message-web", {
            chat_type: this.userType,
            type: "text",
            other_id: this.other_user_id,
            message: msg,
            reply_message_id: reply_message_id,
          })
          .then((response) => {
            this.replyMsgId = "";
            this.replyMsgIdCk = "";
            this.textareaHeight = 40;
          })
          .catch(async (error) => {
            this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
            if(error.response.data.status_code == 401)
            {
              await logOut(error);
            }
          });
      } else {
        axios
          .post("user-store-message-web", {
            chat_type: this.userType,
            type: "text",
            other_id: this.other_user_id,
            message: msg,
          })
          .then((response) => {
            console.log("message sent");
            this.textareaHeight = 40;
          })
          .catch(async (error) => {
          this.$toast.error(error.response.data.message, {
          duration: 4000, // Duration in milliseconds
          position: "top-right", // Toast position
          theme: "sugar", // Toast theme
        });
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
          });
      }
      this.goTop = true;
    },
    async getChatDetail(
      user_id,
      is_online,
      user_type,
      user_name,
      user_profile,
      is_blocked = false
    ) {
      //alert(this.page);
      // if(this.page === 0){
      //  this.isLoading = true;
      // }
      // else{
      // this.moreLoad=true;
      // this.isLoading = false;
      // }
      if (this.other_user_id !== user_id) {
      $('#type_message').css('height','auto');
      $('#type_message').css('height',40 + 'px');
      this.closeReply();
      this.showReactionPicker = false;
      this.sendMediaBtn = false;
      this.showSendButton = false;
      this.previewImage = null;
      this.typeMessage = "";
      this.replyMsgId = "";
      this.page = 1;
      this.userMessages = [];
      }
      if (is_online == "yes") {
        this.isOnline = true;
      } else {
        this.isOnline = false;
      }
      if (this.other_user_id !== user_id) {
      this.userName = user_name;
      this.userProfile = user_profile;
      this.other_user_id = user_id;
      this.userType = user_type;
      if (typeof user_id == "string" && user_id.includes("0.")) {
            // this.nameHeader= false;
            this.$router.replace({ query: { group_id: btoa(user_id) } });
            this.userData(user_id, user_type);
          } else {
            // this.nameHeader= false;
            this.$router.replace({ query: { user_id:  btoa(user_id) } });
            this.userData(user_id, user_type);
          }  
      }    
      await axios
        .post("user-detail-message", {
          user_id: user_id,
          type: user_type,
          page: this.page++,
        })
        .then((response) => {
          for(let i =0; i<response.data.data.length; i++){
          this.userMessages.push(response.data.data[i]);
          }
          console.log(this.userMessages);
          this.joinToSocket();
          this.$socket.on("connect", this.joinToSocket);
          this.isLoading = false;
          if (is_blocked) {
            this.isBlocked = true;
          } else {
            this.isBlocked = false;
          }
        })
        .catch(async (error) => {
          console.log("Error fetching data:", error);
          if(error.response.data.status_code == 401)
          {
            await logOut(error);
          }
        });
      const collection = document.getElementsByClassName("chat-with");
      if (collection.length > 0) {
        collection[0].scrollTo(0, collection[0].scrollHeight);
      }
    },
    searchThis() {
      this.searchText = $("#search").val();
      if (this.searchText == "") {
        this.messagePage();
      } else {
        axios
          .post("user-chat-list-all", {
            search: this.searchText,
          })
          .then((response) => {
            if (response.data.data.group.length > 0) {
              this.searchMessageList = response.data.data.group;
              this.userType = "group";
            } else{
              this.searchMessageList = [];
            }
          })
          .catch(async (error) => {
            console.log("Error fetching data:", error);
            if(error.response.data.status_code == 401)
            {
              await logOut(error);
            }
          });
      }
    },
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
    messagePage() {
      this.favfrndListPage = 1;
      this.updateUserChatListOnMount();
    },
    addToFavourite(event,g_id) {
      $(".options").hide();
      event.preventDefault();
      event.stopPropagation();
      axios
        .post("mark-as-favourite", {
          group_id: g_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
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
     toggleTypographyActions() {
      this.typographyActions = !this.typographyActions;
    },
    getText(message)
    {
      var  arr = message.split(" ");
      var newValue = "";
      var urlRegex = /((http|https):\/\/)?[^\s$.?#].[^\s]*\.(com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|pro|tel|travel|arpa)\b(?:\/[^\\s]*)?/gi;
      $.each(arr, function (key, value) {
        var string = urlRegex.test(value);
        if(string == true)
        {
          newValue = newValue + '<a href="'+value+'" target="_blank">'+value+'</a> ';
        }
        else
        {
            newValue = newValue + value+' ';
        }
      });
      return newValue.trim();
    },
    removeFromFav(event,g_id) {
      $(".options").hide();
      event.preventDefault();
      event.stopPropagation();
      axios
        .post("remove-from-favourite", {
          group_id: g_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
          this.favApi == true ? this.favGroup() : this.updateUserChatList();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000,
            position: "top-right",
            theme: "sugar",
          });
        });
        
    },
  },
};
</script>
<style scoped>

textarea {
  width: 300px;
  padding: 10px;
  resize: none;
  transition: height 0.3s;
}
</style>
