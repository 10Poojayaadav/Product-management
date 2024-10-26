import axios from "axios";
const BASE_URL = import.meta.env.VITE_BASE_URL;

const instance = axios.create({
  baseURL: BASE_URL, // Replace with your API's base URL
  timeout: 10000000, // Adjust timeout as needed
});

instance.defaults.headers.common["Authorization"] =
  "Bearer " + localStorage.getItem("token");
export default instance;
