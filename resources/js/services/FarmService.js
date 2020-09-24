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

  static deleteManager(farmId, managerId) {
    const token = window.localStorage.getItem("token");
    return Axios.delete(`api/customer/farm/${farmId}/manager/${managerId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static delete(farmId) {
    const token = window.localStorage.getItem("token");
    return Axios.delete(`api/customer/farm/${farmId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static isEmailUnique(email) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/farm/manager/is-unique/${email}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static get(farmId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/farm/${farmId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static update(farmId, editFarmRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.patch(`/api/customer/farm/${farmId}`, editFarmRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}

export default FarmService;
