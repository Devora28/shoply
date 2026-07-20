import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
export const getHomeData = async () => {
  const {data} = await api.get(endpoints.home);
  return data;
}
