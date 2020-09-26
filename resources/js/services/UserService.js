import Axios from "axios";

class UserService {
  static getProfile() {
    const token = window.localStorage.getItem("token");
    return Axios.get("/api/auth/profile", {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static updateProfile(profileUpdateRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.post("/api/auth/profile", profileUpdateRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}

export default UserService;
