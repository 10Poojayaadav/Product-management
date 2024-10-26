// Composables
import { createRouter, createWebHistory } from "vue-router";
//comented Pages
// import SignUp from "../views/login-signup/SignUp.vue";
// import RegistrationPage from "../views/login-signup/RegistrationPage.vue";
// import ScanQr from "../views/login-signup/ScanQr.vue";
// import InvitedFriend from "../views/login-signup/InvitedFriend.vue";
import LoginPage from "../views/login-signup/LoginPage.vue";
import ForgotPassword from "../views/login-signup/ForgotPassword.vue";
import ContactUs from "../views/login-signup/ContactUs.vue";
import CheckYourEmail from "../views/login-signup/CheckYourEmail.vue";
import ResetPassword from "../views/login-signup/ResetPassword.vue";
import ConfirmationPage from "../views/login-signup/ConfirmationPage.vue";

// import Welcome from "../views/dashboard/Welcome.vue";
import HomePage from "../views/dashboard/HomePage.vue";
import MessagesPage from "../views/chat/MessagesPage";
// import FriendInfo from "../views/chat/FriendInfo";
// import EditFriendInfo from "../views/chat/EditFriendInfo";
// import Report from "../views/chat/Report";
import ChatList from "../views/chat/ChatList";
import Proclaim from "../views/proclaim/index";
import AddFriend from "../views/addFriend/index";
import Friend from "../views/addFriend/friend";
import FellowList from "../views/fellowship/index";
import GroupChat from "../views/chat/GroupChat";
import FriendsList from "../views/fellowship/FriendsList";
import FriendPage from "../views/fellowship/FriendPage";
import Meditate from "../views/meditate/index";
import MeditateLibrary from "../views/meditate/MeditateLibrary";
// import AudioPage from "../views/common/AudioPage";
import SliderPage from "../views/common/SliderPage";
import VideoPage from "../views/common/VideoPage";
// import LibraryPage from "../views/common/LibraryPage";
import Pdf from "../views/common/Pdf";
import ImagesPage from "../views/common/ImagesPage";
import InsightUser from "../views/common/insightUser";
// import UserList from "../views/common/UserList";
// import CreateGroup from "../views/fellowship/CreateGroup";
// import GroupInfo from "../views/fellowship/GroupInfo";
// import EditGroup from "../views/fellowship/EditGroup";
// import ReportGroup from "../views/fellowship/ReportGroup";
// import MyPrayers from "../views/pray/old/MyPrayers";
// import AnsPrayer from "../views/pray/AnsPrayer";
import AddPrayer from "../views/pray/old/AddPrayer";
// import PrayTogether from "../views/pray/PrayTogether";
// import PrayImages from "../views/common/ImagesPage";
// import EditPrayer from "../views/pray/EditPrayer";
// import PrayTogetherEdit from "../views/pray/PrayTogetherEdit";
import QrImage from "../views/invite/QrImage";
import Setting from "../views/setting/index";
import Emergency from "../views/setting/Emergency";
import ChangePassword from "../views/setting/ChangePassword";
import DeleteAccount from "../views/setting/DeleteAccount";
import FAQ from "../views/setting/Faq";
import FAQDetails from "../views/setting/FaqDetails";
import Notification from "../views/setting/Notification";
import Additional from "../views/setting/Additional";
import Privacy from "../views/setting/Privacy";
import AskSq from "../views/setting/AskSq";
import BroadCast from "../views/broadcast/index";
import EditProfile from "../views/setting/EditProfile";
import auth from "@/middleware/auth";
import guest from "@/middleware/guest";
import store from "../store";
// New
import FrontPage from "../views/dashboard/FrontPage.vue";
import MyPrayer from "../views/pray/MyPrayer.vue";
import MyPrayList from "../views/pray/MyPrayList.vue";
import Library from "../views/pray/Library.vue";
import Library2 from "../views/pray/Library2.vue";
import NewLibrary from "../views/common/NewLibrary.vue";
function middlewarePipeline(context, middleware, index) {
  const nextMiddleware = middleware[index];
  if (!nextMiddleware) {
    return context.next;
  }
  return () => {
    const nextPipeline = middlewarePipeline(context, middleware, index + 1);
    nextMiddleware({ ...context, next: nextPipeline });
  };
}

const routes = [
  {
    path: "/",
    component: () => import("@/layouts/default/Default.vue"),
    children: [
      // {
      //       path: "/",
      //       name: "SignUp",
      //       component: SignUp,
      //       meta: {
      //         middleware: [guest],
      //       },
      //     },
      //     {
      //       path: "/registration/:code",
      //       name: "registration",
      //       component: RegistrationPage,
      //       meta: {
      //         middleware: [guest],
      //       },
      //     },
      //     {
      //       path: "/scan-qr",
      //       name: "scan",
      //       component: ScanQr,
      //       meta: {
      //         middleware: [guest],
      //       },
      //     },
      //     {
      //       path: "/invite-friend/:code",
      //       name: "invite-friend",
      //       component: InvitedFriend,
      //       meta: {
      //         middleware: [guest],
      //       },
      //     },
      {
        path: "/",
        name: "login",
        component: LoginPage,
        meta: {
          middleware: [guest],
        },
      },
      {
        path: "/forgot-password",
        name: "forgot-password",
        component: ForgotPassword,
        meta: {
          middleware: [guest],
        },
      },
      {
        path: "/contact-us",
        name: "contact-us",
        component: ContactUs,
        meta: {
          middleware: [guest],
        },
      },
      {
        path: "/check-your-email/:email",
        name: "check-your-email",
        component: CheckYourEmail,
        meta: {
          middleware: [guest],
        },
      },
      {
        path: "/reset-password",
        name: "reset-password",
        component: ResetPassword,
        meta: {
          middleware: [guest],
        },
      },
      {
        path: "/confirmation-page",
        name: "confirmation-page",
        component: ConfirmationPage,
        meta: {
          middleware: [guest],
        },
      },
      // {
      //   path: "/welcome-screen",
      //   name: "welcome-screen",
      //   component: Welcome,
      // },
      {
        path: "home-page",
        name: "home-page",
        component: HomePage,
      },
      {
        path: "messages",
        name: "messages",
        component: MessagesPage,
      },
      // {
      //   path: "friend-info/:id",
      //   name: "friend-info",
      //   component: FriendInfo,
      // },
      // {
      //   path: "edit-friend-info/:id",
      //   name: "edit-friend-info",
      //   component: EditFriendInfo,
      // },
      // {
      //   path: "report-user/:id",
      //   name: "report-user",
      //   component: Report,
      // },
      {
        path: "chat-list",
        name: "chat-list",
        component: ChatList,
      },
      {
        path: "/proclaim",
        name: "proclaim",
        component: Proclaim,
      },

      {
        path: "/addFriend",
        name: "addFriend",
        component: AddFriend,
      },
      {
        path: "/friend/:code",
        name: "friend",
        component: Friend,
      },
      {
        path: "/fellowship",
        name: "fellowship",
        component: FellowList,
      },
      {
        path: "/group-chat",
        name: "group-chat",
        component: GroupChat,
      },
      {
        path: "/friend-list",
        name: "friend-list",
        component: FriendsList,
      },
      {
        path: "/friend-page",
        name: "friend-page",
        component: FriendPage,
      },
      {
        path: "/meditate",
        name: "meditate",
        component: Meditate,
      },
      {
        path: "/meditateLibrary/:id/:section",
        name: "meditateLibrary",
        component: MeditateLibrary,
      },
      // Common pages route
      // {
      //   path: ":section/audio/:id",
      //   name: "audio",
      //   component: AudioPage,
      // },
      {
        path: ":section/slider/:id",
        name: "slider",
        // component: SliderPage,
      },
      {
        path: ":section/video/:id",
        name: "video",
        component: VideoPage,
      },
      // {
      //   path: ":section/library/:id",
      //   name: "library",
      //   component: LibraryPage,
      // },
      {
        path: ":section/pdf/:id",
        name: "pdf",
        component: Pdf,
        props:true,
      },
      {
        path: ":section/images/:id",
        name: "images",
        component: ImagesPage,
      },
      {
        path: "/share-user-list",
        name: "share-user-list",
        component: InsightUser,
      },
      // {
      //   path: "/user",
      //   name: "user",
      //   component: UserList,
      // },
      // {
      //   path: "/create-group",
      //   name: "create-group",
      //   component: CreateGroup,
      //   props: true,
      // },
      // {
      //   path: "/groupInfo",
      //   name: "groupInfo",
      //   component: GroupInfo,
      // },
      // {
      //   path: "/editGroup",
      //   name: "editGroup",
      //   component: EditGroup,
      // },
      // {
      //   path: "/reportGroup",
      //   name: "reportGroup",
      //   component: ReportGroup,
      // },

      // {
      //   path: "/pray-list",
      //   name: "pray-list",
      //   component: MyPrayers,
      // },
      // {
      //   path: "/answerPrayer",
      //   name: "ansPrayer",
      //   component: AnsPrayer,
      // },
      // {
      //   path: "/add-prayers",
      //   name: "add-prayers",
      //   // component: AddPrayer,
      // },
      // {
      //   path: "/pray-together",
      //   name: "pray-together",
      //   component: PrayTogether,
      // },

      // {
      //   path: "/pray-image",
      //   name: "PrayImages",
      //   component: PrayImages,
      // },
      // {
      //   path: "/edit-prayer/:id",
      //   name: "edit-prayer",
      //   component: EditPrayer,
      // },
      // {
      //   path: "/edit-prayTogether-prayer/:id",
      //   name: "edit-prayTogether-prayer",
      //   component: PrayTogetherEdit,
      // },
      {
        path: "/qr-image",
        name: "qr-image",
        component: QrImage,
      },
      {
        path: "/setting",
        name: "setting",
        component: Setting,
      },
      {
        path: "/emergency",
        name: "emergency",
        component: Emergency,
      },
      {
        path: "/change-password",
        name: "change-password",
        component: ChangePassword,
      },
      {
        path: "/delete-account",
        name: "delete-account",
        component: DeleteAccount,
      },
      {
        path: "/faq",
        name: "faq",
        component: FAQ,
      },
      {
        path: "/faqDeatils/:id",
        name: "faqDetails",
        component: FAQDetails,
      },
      {
        path: "/notification",
        name: "notification",
        component: Notification,
      },
      {
        path: "/additional",
        name: "additional",
        component: Additional,
      },
      {
        path: "/privacy",
        name: "privacy",
        component: Privacy,
      },
      {
        path: "/askSq",
        name: "askSq",
        component: AskSq,
      },
      {
        path: "/broadcast",
        name: "broadcast",
        component: BroadCast,
      },

      {
        path: "/:section/edit-profile",
        name: "edit-profile",
        component: EditProfile,
      },
      // {
      //   path: "pray",
      //   name: "pray",
      //   component: FrontPage,
      // },
      {
        path: "pray",
        name: "pray",
        component: MyPrayer,
      },
      {
        path: "pray-list",
        name: "pray-list",
        component: MyPrayList,
      },
      {
        path: '/library/:id/:section',
        name: "library",
        component: Library,
      },
      {
        path: '/new-library/:id/:section',
        name: "new-library",
        component: NewLibrary,
      },
      {
      path: '/library2/:id/:section',
      name: 'library2',
      component: Library2,
      props: route => ({ id: route.params.id, section: route.params.section, pdfId: route.query.pdfId }),
    },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const context = {
    to,
    from,
    next,
    store,
  };

  if (!to.meta.middleware) {
    auth({ ...context, next: next });
  }
  const middleware = to.meta.middleware;

  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1),
  });
});

export default router;
