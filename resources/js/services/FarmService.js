import Axios from "axios";

class FarmService {
  static create(farmRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.put("/api/customer/farm", farmRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static list() {
    const token = window.localStorage.getItem("token");
    return Axios.get("/api/my/farms", {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static listManagers(farmId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/farm/${farmId}/managers`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static createManager(managerRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.put(
      `/api/customer/farm/${managerRequest.farm_id}/manager`,
      managerRequest,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    );
  }
}

export default FarmService;
