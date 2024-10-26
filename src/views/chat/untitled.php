<template>
  <v-app>
    <section class="messaging-section">
      <Header/>
      <div>
        <div class="messaging-zone">
          <FriendList/>
          <div class="messages-container">
            <div class="messages">
              <div class="message">
                <Message/>
              </div>
              <div class="message">
                <Message/>
              </div>
              <div class="message self">
                <Message/>
              </div>
              <div class="message self">
                <Message/>
              </div>
            </div>
            <MessageFooter/>
          </div>
        </div>
      </div>
    </section>

    <div class="page messages">
      <div class="inbox-layout">
        <div class="main">
          <div class="body bg-black-111">
            <div class="chat-container">
              <!-- User Chat List Code Here Start -->
              <aside class="friends chats">
                <div class="content-wrapper">
                  <div class="search-box">
                    <input
                      autocomplete="off"
                      type="text"
                      @keyup="searchThis"
                      id="search"
                      placeholder="Friend or Groups"
                    />
                  </div>
                  <div v-if="searchText != ''" class="peoples">
                    <div v-if="searchMessageList?.length != 0">
                      <div v-if="searchMessageList?.length != 0 && userType == 'user'  " >
                        <div
                          class="people"
                          v-for="(chat, index) in searchMessageList"
                          :key="index"
                        >
                          <div
                            class="friend"
                            @click="
                              getChatDetail(
                                chat.id,
                                chat?.is_blocked_count == 0
                                  ? chat?.is_online
                                  : 'no',
                                userType,
                                chat.first_name + ' ' + chat.last_name,
                                chat.profile_pic,
                                chat?.is_blocked_count == 0 ? false : true
                              )
                            "
                          >
                            <div class="info">
                              <div class="profile-info">
                                <div class="dp">
                                  <img
                                    v-if="chat.profile_pic !== null"
                                    :src="chat.profile_pic"
                                  />
                                  <div
                                    v-else-if="
                                      chat.first_name != null &&
                                      chat.last_name != null
                                    "
                                  >
                                    <h3>
                                      {{
                                        chat.first_name.charAt(0).toUpperCase() +
                                        "" +
                                        chat.last_name.charAt(0).toUpperCase()
                                      }}
                                    </h3>
                                  </div>
                                  <div v-else>
                                    <h2>NO</h2>
                                  </div>
                                </div>
                                <div class="middle">
                                  <h5>
                                    {{ chat.first_name }} {{ chat.last_name }}
                                  </h5>
                                </div>
                              </div>
                            </div>
                            <v-btn
                              v-if="chat.chatlistpin_count > 0"
                              class="pin-user"
                              @click="$event.stopPropagation()"
                              ><img src="/src/assets/office-push-pin.png"
                            /></v-btn>
                          </div>
                        </div>
                      </div>
                      <div v-if="searchMessageList?.length != 0 && userType == 'group'">
                        <div
                          class="people"
                          v-for="(chat, index) in searchMessageList"
                          :key="index"
                        >
                          <div
                            class="friend"
                            @click="
                              getChatDetail(
                                chat.group_id,
                                chat?.is_online,
                                userType,
                                chat.name,
                                chat.image
                              )
                            "
                          >
                            <div class="info">
                              <div class="profile-info">
                                <div class="dp">
                                  <img
                                    v-if="chat.image !== null"
                                    :src="chat.image"
                                  />
                                  <div v-else-if="chat.name != null">
                                    <h3>
                                      {{ splitName(chat.name) }}
                                    </h3>
                                  </div>
                                  <div v-else>
                                    <h2>NO</h2>
                                  </div>
                                </div>
                                <div class="middle">
                                  <h5>
                                    {{ chat.name }}
                                  </h5>
                                </div>
                              </div>
                            </div>
                            <v-btn
                              v-if="chat.chatlistpin_count > 0"
                              class="pin-user"
                              @click="$event.stopPropagation()"
                              ><img src="/src/assets/office-push-pin.png"
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
                  <div v-else>
                    <div v-if="messageList.length > 0" class="roll">
                      <div
                        :class="
                          chat.other_data
                            ? other_user_id == chat.other_data.id
                              ? 'people active'
                              : 'people'
                            : other_user_id == chat?.group_data?.group_id
                            ? 'people active'
                            : 'people'
                        "
                        v-for="(chat, index) in messageList"
                        :key="index"
                      >
                        <div
                          v-if="chat.type == 'user'"
                          class="friend"
                          style="cursor: pointer"
                        >
                          <div class="info">
                            <div
                              class="profile-info"
                              @click="
                                getChatDetail(
                                  chat.other_data.id,
                                  chat.other_data.is_blocked_count == 0
                                    ? chat.other_data.is_online
                                    : 'no',
                                  chat.type,
                                  chat.other_data.first_name +
                                    ' ' +
                                    chat.other_data.last_name,
                                  chat.other_data.profile_pic,
                                  chat.other_data.is_blocked_count == 0
                                    ? false
                                    : true
                                )
                              "
                            >
                              <div class="dp">
                                <div
                                  class="dp"
                                  v-if="
                                    chat.other_data !== null &&
                                    chat.other_data.profile_pic != null
                                  "
                                >
                                  <img :src="chat.other_data.profile_pic" />
                                </div>
                                <div v-else-if="chat.other_data != null">
                                  <h3>
                                    {{
                                      chat.other_data.first_name
                                        .charAt(0)
                                        .toUpperCase() +
                                      "" +
                                      chat.other_data.last_name
                                        .charAt(0)
                                        .toUpperCase()
                                    }}
                                  </h3>
                                </div>
                                <div v-else>
                                  <h2>NO</h2>
                                </div>
                                <span
                                  v-if="
                                    chat?.other_data?.is_online == 'yes' &&
                                    chat?.other_data?.is_blocked_count == 0
                                  "
                                  class="active-status"
                                ></span>
                              </div>
                              <div class="middle">
                                <h5 v-if="chat.other_data !== null">
                                  {{ chat.other_data.first_name }}
                                  {{ chat.other_data.last_name }}
                                </h5>
                                <h5 v-else>No Name</h5>
                                <p v-if="chat.other_data !== null">
                                  <span
                                    v-if="
                                      chat.message.deleted_by != null &&
                                      chat.message.deleted_by.includes(
                                        my_user_id
                                      )
                                    "
                                  ></span>
                                  <span v-else>{{ chat.message.message }}</span>
                                </p>
                                <p v-else></p>
                                <v-btn
                                  class="pin-user"
                                  @click="$event.stopPropagation()"
                                  v-if="chat.chatlistpin_count > 0"
                                  ><img src="/src/assets/office-push-pin.png"
                                /></v-btn>
                                <span
                                  v-if="chat.unseen_count > 0"
                                  class="unseen-count"
                                >
                                  <span class="count">{{
                                    chat.unseen_count
                                  }}</span>
                                </span>
                              </div>
                              <span
                                v-if="
                                  userTypingId == chat.other_id &&
                                  chat.is_blocked_count == 0
                                "
                                style="color: green; font-size: 10px"
                                >&nbsp; &nbsp; typing...</span
                              >
                            </div>
                            <div class="actions">
                              <p v-if="chat.message">
                                {{
                                  chat.message.created_at
                                    ? getTime(chat.message.created_at)
                                    : ""
                                }}
                              </p>
                              <p v-else></p>
                              <div class="ellipsis" @click="showMenu(chat.id)">
                                <div class="hamburger">
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                </div>
                                <div
                                  class="options"
                                  :id="`option-id-${chat.id}`"
                                >
                                  <ul>
                                    <li @click="muteUserList(chat.other_id)">
                                      <img src="/src/assets/mute.svg" alt="" />
                                      Mute
                                    </li>
                                    <li
                                      @click="
                                        $router.push({
                                          name: 'friend-info',
                                          params: { id: chat.other_id },
                                        })
                                      "
                                    >
                                      <img src="/src/assets/infor.png" alt="" />
                                      Friend Info
                                    </li>
                                    <li @click="clearChatList(chat.id)">
                                      <img src="/src/assets/clear.svg" alt="" />
                                      Clear Chat
                                    </li>
                                    <li
                                      @click="pinUserList(chat.other_id)"
                                      v-if="chat.chatlistpin_count > 0"
                                    >
                                      <img
                                        src="/src/assets/office-push-pin.svg"
                                        alt=""
                                      />
                                      Unpin
                                    </li>
                                    <li
                                      @click="pinUserList(chat.other_id)"
                                      v-else
                                    >
                                      <img
                                        src="/src/assets/office-push-pin.svg"
                                        alt=""
                                      />
                                      Pin
                                    </li>
                                    <li @click="deleteChatList(chat.id)">
                                      <img
                                        src="/src/assets/delete.svg"
                                        alt=""
                                      />
                                      Delete
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div
                          v-if="chat.type == 'group'"
                          class="friend"
                          :id="`friend-${chat.group_data.id}`"
                        >
                          <div class="info">
                            <div
                              style="cursor: pointer"
                              class="profile-info"
                              @click="
                                getChatDetail(
                                  chat.group_data.group_id,
                                  'no',
                                  chat.type,
                                  chat.group_data.name,
                                  chat.group_data.image
                                )
                              "
                            >
                              <div class="dp">
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

                              <div class="middle">
                                <h5>{{ chat.group_data.name }}</h5>
                                <p v-if="chat.message">
                                  {{ chat.message.message }}
                                </p>
                                <v-btn
                                  class="pin-user"
                                  @click="$event.stopPropagation()"
                                  v-if="chat.chatlistpin_count > 0"
                                >
                                  <img src="/src/assets/office-push-pin.png"
                                /></v-btn>
                                <span
                                  v-if="chat.unseen_count > 0"
                                  class="unseen-count"
                                >
                                  <span class="count">{{
                                    chat.unseen_count
                                  }}</span></span
                                >
                              </div>
                              <span
                                v-if="userTypingId == chat.group_data.group_id"
                                style="color: green; font-size: 10px"
                              >
                                &nbsp; &nbsp; typing...</span
                              >
                            </div>
                            <div class="actions">
                              <p v-if="chat.message">
                                {{
                                  chat.message.created_at
                                    ? getTime(chat.message.created_at)
                                    : ""
                                }}
                              </p>
                              <p v-else></p>
                              <div class="ellipsis" @click="showMenu(chat.id)">
                                <div class="hamburger">
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                </div>
                                <div
                                  class="options"
                                  :id="`option-id-${chat.id}`"
                                >
                                  <ul>
                                    <li @click="muteUserList(chat.other_id)">
                                      <img src="/src/assets/mute.svg" alt="" />
                                      Mute
                                    </li>
                                    <li
                                      @click="
                                        $router.push({
                                          name: 'groupInfo',
                                          query: {
                                            group_id: chat.group_data.group_id,
                                          },
                                        })
                                      "
                                    >
                                      <img src="/src/assets/infor.png" alt="" />
                                      Group Info
                                    </li>
                                    <li @click="clearChatList(chat.id)">
                                      <img src="/src/assets/clear.svg" alt="" />
                                      Clear Chat
                                    </li>
                                    <li
                                      @click="pinUserList(chat.other_id)"
                                      v-if="chat.chatlistpin_count > 0"
                                    >
                                      <img
                                        src="/src/assets/office-push-pin.svg"
                                        alt=""
                                      />
                                      Unpin
                                    </li>
                                    <li
                                      @click="pinUserList(chat.other_id)"
                                      v-else
                                    >
                                      <img
                                        src="/src/assets/office-push-pin.svg"
                                        alt=""
                                      />
                                      Pin
                                    </li>
                                    <li @click="deleteChatList(chat.id)">
                                      <img
                                        src="/src/assets/delete.svg"
                                        alt=""
                                      />
                                      Delete
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="no-record-style" v-else>No records found!</div>
                  </div>
                </div>
              </aside>
              <!-- User Chat List End Code -->

              <!-- Chat Detail Extended View Start -->
              <main class="extend-view" @click="hideOpenMenus">
                <!-- header of chat view start-->
                <header class="child-header">
                  <div class="profile">
                    <div class="dp">
                      <img
                        v-if="userProfile == ''"
                        src="/src/assets/logo.png"
                      />
                      <img
                        v-if="userProfile != null && userProfile != ''"
                        :src="userProfile"
                      />
                      <h3 v-else class="profile-pic">
                        {{ userName ? splitName(userName) : "" }}
                      </h3>
                      <span v-if="userStatus" class="active-status"></span>
                    </div>
                    <div
                      class="pl-2"
                      style="display: flex; flex-direction: column"
                    >
                      <h4 v-if="userName != ''">{{ userName }}</h4>
                      <h4 v-else>New Chat</h4>
                      <span
                        v-if="isOnline && userType != '' && !isBlocked"
                        style="color: green; font-size: 10px"
                        >Online</span
                      >
                    </div>
                  </div>
                </header>
                <!-- Header of chat view end -->
                <div class="prayer-container" v-if="isLoading">
                  <div class="loader"></div>
                </div>
                <div
                  v-else
                  class="chat-with"
                  id="chat-div"
                  v-on:scroll="scrollFunction"
                >
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
                          <span class="box-size-similar">
                            <div class="smiley-emoji">
                              <img
                                src="/src/assets/smile.png"
                                @click="this.showReactionPickerFor(message.id)"
                                alt=""
                              />
                            </div>
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
                              v-if="
                                message.get_msg_reply != null &&
                                replyMsgId == ''
                              "
                            >
                              <span
                                v-if="
                                  message.get_msg_reply.message_to ==
                                  other_user_id
                                "
                                >{{ message.get_msg_by?.first_name }}</span
                              >
                              <span v-else>{{
                                message.get_msg_to.first_name
                              }}</span>
                              <span class="attached-file-section" 
                                v-if="message.get_msg_reply.type == 'image' || message.get_msg_reply.type == 'document' || message.get_msg_reply.type == 'video' || message.get_msg_reply.type == 'audio'"
                              >Attached</span>
                              <span>{{ message.get_msg_reply.message }}</span>
                            </span>
                            <span v-bind:class="getClass(message.status)">
                              <span>{{ message.message }} </span>
                              <span
                                :id="`reaction-on-id-${message.id}`"
                                class="reaction-emoji"
                              >
                                <span
                                  v-if="message.get_msg_reaction.length > 0"
                                >
                                  <span
                                    v-for="(
                                      reaction, index
                                    ) in message.get_msg_reaction"
                                  >
                                    {{ reaction.reaction }}
                                  </span>
                                </span>
                              </span>

                              <div class="ellipsis">
                                <div class="hamburger">
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                  <span class="dots"></span>
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
                              v-if="message.get_msg_reply != null"
                            >
                              <span
                                v-if="
                                  message.get_msg_reply.message_to ==
                                  other_user_id
                                "
                                >{{ message.get_msg_to?.first_name }}</span
                              >
                              <span v-else>{{
                                message.get_msg_by.first_name
                              }}</span>
                              <span class="attached-file-section" 
                                v-if="message.get_msg_reply.type == 'image' || message.get_msg_reply.type == 'document' || message.get_msg_reply.type == 'video' || message.get_msg_reply.type == 'audio'"
                              >Attached</span>
                              <span>{{ message.get_msg_reply.message }}</span>
                            </span>
                            <div
                              v-if="userType == 'group'"
                              class="group-dp small-profile-pic"
                            >
                              <img
                                :src="message.get_msg_by.profile_pic"
                                v-if="message.get_msg_by.profile_pic != null"
                              />
                              <div v-else-if="message.get_msg_by != null">
                                <h3 class="profile_pic">
                                  {{
                                    message.get_msg_by.first_name
                                      .charAt(0)
                                      .toUpperCase()
                                  }}
                                  {{
                                    message.get_msg_by.last_name
                                      .charAt(0)
                                      .toUpperCase()
                                  }}
                                </h3>
                              </div>
                            </div>
                            <span class="reply-other-side-msg">
                              <span>{{ message.message }} </span>
                              <span
                                :id="`reaction-on-id-${message.id}`"
                                class="reaction-emoji"
                              >
                                <span
                                  v-if="message.get_msg_reaction.length > 0"
                                >
                                  <span
                                    v-for="(
                                      reaction, index
                                    ) in message.get_msg_reaction"
                                  >
                                    {{ reaction.reaction }}
                                  </span>
                                </span>
                              </span>
                              <div class="ellipsis">
                                <div class="hamburger">
                                  <span class="dots"></span>
                                  <span class="dots"></span>
                                  <span class="dots"></span>
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
                            </span>
                            <div class="smiley-emoji">
                              <img
                                src="/src/assets/smile.png"
                                @click="this.showReactionPickerFor(message.id)"
                                alt=""
                                height="15"
                              />
                            </div>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div v-if="message.type == 'info'">
                      <div class="chat-info">
                        <span>{{ message.message }}</span>
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
                            <img :src="message.get_chat_file[0].path" />
                            <span
                              v-if="message.message != null"
                              class="chat-file-messag"
                              >{{ message.message }}
                            </span>
                            <span
                              :id="`reaction-on-id-${message.id}`"
                              class="reaction-emoji"
                            >
                              <span v-if="message.get_msg_reaction.length > 0">
                                <span
                                  v-for="(
                                    reaction, index
                                  ) in message.get_msg_reaction"
                                >
                                  {{ reaction.reaction }}
                                </span>
                              </span>
                            </span>
                            <div class="ellipsis">
                              <div class="hamburger">
                                <span class="dots"></span>
                                <span class="dots"></span>
                                <span class="dots"></span>
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
                          </span>
                          <div class="smiley-emoji">
                            <img
                              src="/src/assets/smile.png"
                              @click="this.showReactionPickerFor(message.id)"
                              alt=""
                              height="15"
                            />
                          </div>
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
                            class="chat-file-messag"
                            >{{ message.message }}
                          </span>
                          <span
                            :id="`reaction-on-id-${message.id}`"
                            class="reaction-emoji"
                          >
                            <span v-if="message.get_msg_reaction.length > 0">
                              <span
                                v-for="(
                                  reaction, index
                                ) in message.get_msg_reaction"
                              >
                                {{ reaction.reaction }}
                              </span>
                            </span>
                          </span>
                          <div class="ellipsis">
                            <div class="hamburger">
                              <span class="dots"></span>
                              <span class="dots"></span>
                              <span class="dots"></span>
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
                          <div class="smiley-emoji">
                            <img
                              src="/src/assets/smile.png"
                              @click="this.showReactionPickerFor(message.id)"
                              alt=""
                              height="15"
                            />
                          </div>
                        </span>
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
                        <span
                          v-bind:class="
                            message.message_by == my_user_id && replyMsgId == ''
                              ? getClass(message.status)
                              : ''
                          "
                        >
                          <div class="smiley-emoji">
                            <img
                              src="/src/assets/smile.png"
                              @click="this.showReactionPickerFor(message.id)"
                              alt=""
                              height="15"
                            />
                          </div>
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
                          <audio controls>
                            <source
                              :src="message.get_chat_file[0].path"
                              type="audio/mpeg"
                            />
                          </audio>
                          <span
                            v-if="message.message != null"
                            class="chat-file-messag"
                            >{{ message.message }}
                          </span>
                          <span
                            :id="`reaction-on-id-${message.id}`"
                            class="reaction-emoji"
                          >
                            <span v-if="message.get_msg_reaction.length > 0">
                              <span
                                v-for="(
                                  reaction, index
                                ) in message.get_msg_reaction"
                              >
                                {{ reaction.reaction }}
                              </span>
                            </span>
                          </span>
                          <div class="ellipsis">
                            <div class="hamburger">
                              <span class="dots"></span>
                              <span class="dots"></span>
                              <span class="dots"></span>
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
                          <img
                            src="/src/assets/pdficon.png"
                            class="chat-pdf-icon"
                          />
                          <div class="smiley-emoji">
                            <img
                              src="/src/assets/smile.png"
                              @click="this.showReactionPickerFor(message.id)"
                              alt=""
                              height="15"
                            />
                          </div>
                          <span
                            v-if="message.message != null"
                            class="chat-file-messag"
                            >{{ message.message }}
                          </span>
                          <span
                            :id="`reaction-on-id-${message.id}`"
                            class="reaction-emoji"
                          >
                            <span v-if="message.get_msg_reaction.length > 0">
                              <span
                                v-for="(
                                  reaction, index
                                ) in message.get_msg_reaction"
                              >
                                {{ reaction.reaction }}
                              </span>
                            </span>
                          </span>
                          <div class="ellipsis">
                            <div class="hamburger">
                              <span class="dots"></span>
                              <span class="dots"></span>
                              <span class="dots"></span>
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
                        </span>
                      </div>
                    </div>

                    <div
                      v-if="message.type == 'media'"
                      :class="
                        message.message_by == my_user_id && replyMsgId == ''
                          ? 'chat-image chat chat-me'
                          : 'chat-image  chat-other'
                      "
                    >
                      <div class="smiley-emoji">
                        <img
                          src="/src/assets/smile.png"
                          @click="this.showReactionPickerFor(message.id)"
                          alt=""
                          height="15"
                        />
                      </div>
                      <span
                        v-bind:class="
                          message.message_by == my_user_id && replyMsgId == ''
                            ? getClass(message.status)
                            : ''
                        "
                      >
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
                          <img :src="message.media_data.thumbnail" />
                        </div>
                        <span
                          v-if="message.message != null"
                          class="chat-file-messag"
                          >{{ message.message }}
                        </span>
                        <span
                          :id="`reaction-on-id-${message.id}`"
                          class="reaction-emoji"
                        >
                          <span v-if="message.get_msg_reaction.length > 0">
                            <span
                              v-for="(
                                reaction, index
                              ) in message.get_msg_reaction"
                            >
                              {{ reaction.reaction }}
                            </span>
                          </span>
                        </span>
                        <div class="ellipsis">
                          <div class="hamburger">
                            <span class="dots"></span>
                            <span class="dots"></span>
                            <span class="dots"></span>
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
                      </span>
                    </div>
                  </div>
                  <div class="start-conversation" v-else>
                    <span>Start your conversation</span>
                  </div>
                </div>
                <Picker
                  v-if="showReactionPicker"
                  :data="emojiIndex"
                  set="twitter"
                  @select="this.selectEmoji"
                />
                <div class="image-preview" v-if="previewImage">
                  <v-btn @click="closePreview">+</v-btn>
                  <div class="img-wrapper">
                    <v-img :width="200" cover :src="previewImage"></v-img>
                  </div>
                </div>
                <footer class="footer">
                  <div
                    v-if="
                      isTyping &&
                      typingUserInfo != '' &&
                      other_user_id != '' &&
                      other_user_id == userTypingId &&
                      !isBlocked
                    "
                  >
                    <div class="group-dp" v-if="userType == 'group'">
                      <img
                        :src="typingUserInfo.profile_pic"
                        v-if="typingUserInfo.profile_pic != null"
                        style="height: 10px; width: 10px"
                      />
                      <div class="group-dp" v-else-if="typingUserInfo != null">
                        <h3 class="profile_pic">
                          {{
                            typingUserInfo.first_name.charAt(0).toUpperCase()
                          }}
                          {{ typingUserInfo.last_name.charAt(0).toUpperCase() }}
                        </h3>
                      </div>
                    </div>
                    <ul class="typing-event">
                      <li></li>
                      <li></li>
                      <li></li>
                    </ul>
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
                  <div class="footer-inner" v-if="userName != ''">
                    <div class="send-message">
                      <textarea
                        class="type-message-chat"
                        type="text"
                        name="type_message"
                        v-model="typeMessage"
                        id="type_message"
                        @keypress="typedData($event)"
                        autocomplete="off"
                        placeholder="Type a message"
                      ></textarea>
                      <button
                        class="form-control"
                        @click="storeMsg"
                        v-if="
                          typeMessage.trim() != '' &&
                          !sendMediaBtn &&
                          !showSendButton
                        "
                      >
                        <v-img src="/src/assets/send-button.png"></v-img>
                      </button>
                      <button
                        class="form-control"
                        @click="handleMediaSend"
                        v-if="sendMediaBtn"
                      >
                        <v-img src="/src/assets/send-button.png"></v-img>
                      </button>
                      <button
                        id="send-file-btn"
                        class="form-control"
                        @click="handleFileUploadSend"
                        v-if="showSendButton"
                      >
                        <v-img src="/src/assets/send-button.png"></v-img>
                      </button>
                    </div>
                    <div class="media">
                      <ul>
                        <input
                          type="file"
                          style="display: none"
                          ref="imgInput"
                          @change="handleImgUpload"
                          id="img-video-input"
                          accept="image/*, video/*"
                        />
                        <li title="Gallery" v-if="hideMediaIcons">
                          <v-img
                            @click="openFileInput"
                            src="/src/assets/gallery.svg"
                            height="20px"
                          ></v-img>
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
                          <v-img
                            @click="openAnyFileInput"
                            src="/src/assets/documents.svg"
                            height="20px"
                          ></v-img>
                        </li>
                      </ul>
                    </div>
                  </div>
                </footer>
              </main>
              <!-- Chat Detail Extended View End  -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-app>
</template>
<script>
import axios from "../../router/axios.js";
import { Picker, EmojiIndex } from "emoji-mart-vue-fast/src";
import data from "emoji-mart-vue-fast/data/all.json";
import "emoji-mart-vue-fast/css/emoji-mart.css";
import $ from "jquery";
import Header from "../layout/Header.vue";
import FriendList from "../chat/FriendList.vue";
import Message from "../chat/Message.vue";
import MessageFooter from "../chat/MessageFooter.vue";
let emojiIndex = new EmojiIndex(data);
let timeoutId = 0;

export default {
  data() {
    return {
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
      userType: "user",
      userMessages: [],
      my_user_id: "",
      typeMessage: "",
      renderComponent: true,
      isTyping: false,
      isBlocked: false,
      isOnline: false,
      userTypingId: "",
      page: 1,
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
      previousOption: "",
      toggle_id: "",
    };
  },

  components: {
    Picker,
    Header,
    FriendList,
    Message,
    MessageFooter,
  },

  mounted() {
    this.showReactionPicker = false;
    if (this.$route.query.user_id && !this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("user-data?id=" + atob(this.$route.query.user_id), {})
        .then((response) => {
          console.log("userdata", response);
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
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    } else if (this.$route.query.group_id && !this.$route.query.media_id) {
      this.messagePage();
      axios
        .post("group-view", {
          other_id: atob(this.$route.query.group_id),
        })
        .then((response) => {
          this.groupData = response.data.data.group;
          console.log(response);
          this.getChatDetail(
           atob(this.$route.query.group_id),
            "no",
            "group",
            this.groupData.name,
            this.groupData.image
          );
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    } else if (this.$route.query.group_id && this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("get-content/" + this.$route.query.media_id)
        .then((response) => {
          console.log("getcontent", response);
          this.sendMediaBtn = true;
          this.mediaData = response.data.data;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
      axios
        .post("group-view", {
          other_id: atob(this.$route.query.group_id),
        })
        .then((response) => {
          console.log("grroup", response);
          this.groupData = response.data.data.group;
          this.getChatDetail(
            atob(this.$route.query.group_id),
            "no",
            "group",
            this.groupData.name,
            this.groupData.image
          );
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    } else if (this.$route.query.user_id && this.$route.query.media_id) {
      this.messagePage();
      axios
        .get("get-content/" + this.$route.query.media_id)
        .then((response) => {
          this.sendMediaBtn = true;
          this.mediaData = response.data.data;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
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
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    } else {
      this.messagePage();
    }
    this.socketcall();
    this.user();
    this.joinToSocket();
    this.$socket.on("connect", this.joinToSocket);
  },

  created() {
    this.joinToSocket();
    this.$socket.on("connect", this.joinToSocket);
  },

  methods: {
    hideOpenMenus(e) {
      if (this.toggle_id != "") {
        $(".options").hide();
        this.toggle_id = "";
      }
    },
    showMenu(chat_id) {
      if (this.toggle_id == chat_id) {
        $("#option-id-" + chat_id).toggle();
      } else {
        $(".options").hide();
        $("#option-id-" + chat_id).toggle();
      }
      this.toggle_id = chat_id;
    },

    showReactionPickerFor(message_id) {
      if (this.showReactionPickerId != "") {
        this.showReactionPickerId = "";
        this.showReactionPicker = false;
      } else {
        this.showReactionPicker = true;
        this.showReactionPickerId = message_id;
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
            console.log(response);
            this.selectedFile = null;
            this.sendMediaBtn = false;
          })
          .catch((error) => {
             this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
            console.log("Error fetching data:", error);
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
            console.log(response);
            this.selectedFile = null;
            this.sendMediaBtn = false;
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
            console.log("Error fetching data:", error);
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
          console.log(response);
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
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
      console.log(this.selectedFile);
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
          if (this.selectedFile.size / 1024 / 1024 > 3) {
            this.$toast.error(
              "Size is too large of this file. Max file size is 3 MB.",
              {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              }
            );
            event.preventDefault();
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
        }

        if (this.selectedFile.type.includes("image")) {
          this.fileType = "image";
          const reader = new FileReader();
          reader.onload = (event) => {
            this.previewImage = event.target.result;
          };
          if (this.selectedFile) {
            reader.readAsDataURL(this.selectedFile);
          }
        }
        this.showSendButton = true;
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
      this.typeMessage = "";
      axios
        .post("user-store-message-web", formData)
        .then((response) => {
          this.selectedFile = null;
          this.updateUserChatList();
        })
        .catch((error) => {
          this.$toast.error(error.response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          console.log("Error fetching data:", error);
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
          this.updateUserChatList();
          this.updateUserDetailListNoLoad();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    copyMessage(msg) {
      this.$toast.success("Message copied!!!", {
        duration: 4000, // Duration in milliseconds
        position: "top-right", // Toast position
        theme: "sugar", // Toast theme
      });
      const textField = document.createElement("textarea");
      textField.innerText = msg;
      document.body.appendChild(textField);
      textField.select();
      document.execCommand("copy");
      textField.remove();
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
        document.getElementById("reply-msg").textContent = msg.message;
      }
      document.getElementById("type_message").focus();
      document.getElementById("reply-msg-text").style.display = "block";

      document.getElementById("reply-user").textContent =
        msg.get_msg_by.first_name;
      this.replyMsgIdCk = msg.id;
      event.preventDefault();
    },
    openFile(file_url) {
      window.open(file_url, "_blank");
    },
    forwordMessage(m_id) {
      this.$router.push({
        name: "chat-list",
        query: { message_id: m_id, other: this.other_user_id },
      });
    },
    muteUserList(other_id) {
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
          this.updateUserChatList();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    clearChatList(id) {
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
          this.updateUserChatList();
          this.updateUserDetailListNoLoad();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    pinUserList(other_id) {
      axios
        .post("user-pin-chat-list", {
          other_id: other_id,
        })
        .then((response) => {
          this.$toast.success(response.data.message, {
            duration: 4000, // Duration in milliseconds
            position: "top-right", // Toast position
            theme: "sugar", // Toast theme
          });
          this.updateUserChatList();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    deleteChatList(id) {
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
          this.updateUserChatList();
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },

    scrollFunction(event) {
      var obj = document.getElementById("chat-div");
      if (event.currentTarget.scrollTop == 0) {
        if (
          this.arr.length
            ? this.arr.length == 20
            : this.userMessages.length == 20
        ) {
          this.page++;
          this.updateUserDetailList();
        }
      } else if (obj.scrollTop === obj.scrollHeight - obj.offsetHeight) {
        if (this.page < 0) {
          this.page = 1;
        } else {
          this.page--;
        }
        axios
          .post("user-detail-message", {
            user_id: this.other_user_id,
            type: this.userType,
            page: this.page,
          })
          .then((response) => {
            this.userMessages = response.data.data;
            if (this.userMessages.length == 0) {
              this.page = this.page - 1;
            }
          })
          .catch((error) => {
            console.log("Error fetching data:", error);
          });
      } else {
        if (this.page < 0) {
          this.page = 1;
        }
      }
    },
    getTime(time) {
      var inputDate = new Date(time);
      var min =
        inputDate.getMinutes() <= 9
          ? "0" + inputDate.getMinutes()
          : inputDate.getMinutes();
          console.log(min);
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
        return "message-delivered";
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
    async typedData(event) {
      if (event.keyCode == 13 && this.selectedFile != null) {
        event.stopPropagation();
        event.preventDefault();
        document.getElementById("send-file-btn").click();
      } else {
        if (event.keyCode == 13) {
          var content = event.target.value;
          var caret = this.getCaret(event);
          if (event.shiftKey) {
            event.target.value =
              content.substring(0, caret - 1) +
              "\n" +
              content.substring(caret, content.length);
            event.stopPropagation();
          } else {
            event.target.value =
              content.substring(0, caret - 1) +
              content.substring(caret, content.length);
            event.preventDefault();
            if (this.typeMessage.trim() != "") {
              await this.storeMsg();
            }
          }
        } else if (event.key === "Enter" && this.typeMessage.trim() != "") {
          event.preventDefault();
          await this.storeMsg();
        }
        axios
          .post("user-typing-status-web", {
            chat_type: this.userType,
            other_id: this.other_user_id,
          })
          .then((response) => {})
          .catch((error) => {
            console.log("Error fetching data:", error);
          });
      }
    },

    async sendMessageEvent(response) {
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
      axios
        .post("user-chat-list-web")
        .then((response) => {
          this.messageList = response.data.data.data;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    updateUserChatListOnMount() {
      axios
        .post("user-chat-list")
        .then((response) => {
          this.messageList = response.data.data.data;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    updateUserDetailListNoLoad() {
      this.isLoading = false;
      axios
        .post("user-detail-message", {
          user_id: this.other_user_id,
          type: this.userType,
          page: this.page,
        })
        .then((response) => {
          this.userMessages = response.data.data;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },
    updateUserDetailList() {
      this.isLoading = true;
      axios
        .post("user-detail-message", {
          user_id: this.other_user_id,
          type: this.userType,
          page: this.page,
        })
        .then((response) => {
          console.log("detailsmessage", response);
          this.arr = response.data.data;
          if (this.arr.length == 0) {
            this.page = this.page - 1;
          } else {
            this.userMessages = this.userMessages.concat(this.arr);
          }
          this.isLoading = false;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
    },

    socketcall() {
      this.$socket.on("ChatLara.App\\Events\\ChatDetailReaction", (event) => {
        console.log("ChatDetailReaction event");
        console.log(event);
        var length = event.data.data.get_msg_reaction.length;
        this.chat_msg_id = event.data.data?.get_msg_reaction[0]?.chat_data_id;
        if (length > 0) {
          $("#reaction-on-id-" + this.chat_msg_id).text("");
          this.emojiOutput = "";
          for (var i = 0; i < length; i++) {
            this.emojiOutput += event.data.data?.get_msg_reaction[i]?.reaction;
          }
          $("#reaction-on-id-" + this.chat_msg_id).text(this.emojiOutput);
        } else {
          $("#reaction-on-id-" + this.chat_msg_id).text("");
        }
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxOffline", (event) => {
        console.log("ChatInboxOffline");
        this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxMessage", (event) => {
        console.log("ChatInboxMessage");
        this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxOnline", (event) => {
        console.log("ChatInboxOnline");
        this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxTyping", (event) => {
        this.sendTypingEvent(event);
      });
      this.$socket.on("ChatLara.App\\Events\\ChatNewMessage", (event) => {
        console.log("ChatNewMessage event");
        this.sendMessageEvent(event);
        this.updateUserChatList();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailMsgStatus", (event) => {
        console.log("ChatDetailMsgStatus event");
        this.updateUserChatList();
        this.updateUserDetailListNoLoad();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatInboxMsgStatus", (event) => {
        console.log("ChatInboxMsgStatus event");
        this.updateUserDetailListNoLoad();
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailTyping", (event) => {
        console.log("ChatDetailTyping event");
        this.sendTypingEvent(event);
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailOnline", (event) => {
        console.log("ChatDetailOnline event, ");
        console.log(event);
        if (this.other_user_id == event?.data?.data?.other_id) {
          this.isOnline = true;
          this.updateUserChatList();
        } else {
          this.isOnline = false;
        }
      });
      this.$socket.on("ChatLara.App\\Events\\ChatDetailOffline", (event) => {
        console.log("ChatDetailOffline event");
        console.log(event?.data?.data?.other_id);
        if (this.other_user_id == event?.data?.data?.other_id) {
          this.isOnline = false;
          this.updateUserChatList();
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
          await logout(error);
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
          })
          .catch((error) => {
            this.$toast.error(error.response.data.message, {
                duration: 4000, // Duration in milliseconds
                position: "top-right", // Toast position
                theme: "sugar", // Toast theme
              });
            console.log("Error fetching data:", error);
          });
      } else {
        axios
          .post("user-store-message-web", {
            chat_type: this.userType,
            type: "text",
            other_id: this.other_user_id,
            message: msg,
          })
          .then((response) => {})
          .catch((error) => {
           this.$toast.error(error.response.data.message, {
          duration: 4000, // Duration in milliseconds
          position: "top-right", // Toast position
          theme: "sugar", // Toast theme
        });
            console.log("Error fetching data:", error);
          });
      }
    },
    async getChatDetail(
      user_id,
      is_online,
      user_type,
      user_name,
      user_profile,
      is_blocked = false
    ) {
      if (typeof user_id == "string" && user_id.includes("0.")) {
        this.$router.replace({ query: { group_id: btoa(user_id) } });
      } else {
        this.$router.replace({ query: { user_id:  btoa(user_id) } });
      }
      if (is_blocked) {
        this.isBlocked = true;
      } else {
        this.isBlocked = false;
      }

      this.showReactionPicker = false;
      this.updateUserChatList();
      this.sendMediaBtn = false;
      this.showSendButton = false;
      this.isLoading = true;
      this.previewImage = null;
      this.typeMessage = "";
      this.replyMsgId = "";
      if (is_online == "yes") {
        this.isOnline = true;
      } else {
        this.isOnline = false;
      }
      this.page = 1;
      this.userName = user_name;
      this.userProfile = user_profile;
      this.other_user_id = user_id;
      this.userType = user_type;
      await axios
        .post("user-detail-message", {
          user_id: user_id,
          type: user_type,
          page: this.page,
        })
        .then((response) => {
          this.updateUserChatList();
          this.userMessages = response.data.data;
          console.log(response);
          this.joinToSocket();
          this.$socket.on("connect", this.joinToSocket);
          this.isLoading = false;
        })
        .catch((error) => {
          console.log("Error fetching data:", error);
        });
      const collection = document.getElementsByClassName("chat-with");
      if (collection.length > 0) {
        collection[0].scrollTo(0, collection[0].scrollHeight);
      }
    },
    searchThis(event) {
      this.searchText = event.target.value;
      if (this.searchText == "") {
        this.messagePage();
      } else {
        axios
          .post("user-chat-list-all", {
            search: this.searchText,
          })
          .then((response) => {
            console.log("chatlistall", response);
            if (response.data.data.group.length > 0) {
              this.searchMessageList = response.data.data.group;
              this.userType = "group";
            } else {
              this.searchMessageList = response.data.data.user.data;
              this.userType = "user";
            }
            console.log(this.searchMessageList);
          })
          .catch((error) => {
            console.log("Error fetching data:", error);
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
      this.updateUserChatListOnMount();
    },
  },
};
</script>
