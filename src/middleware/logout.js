import store from '@/store/index';
import router from "@/router/index";
export default async function guest(error) {
    if (error.response.data.status_code === 401) {
        localStorage.removeItem("token");
        await store.dispatch("logout");
        router.push("login");
      }
  }

  
  

 