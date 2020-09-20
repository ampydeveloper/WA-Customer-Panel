import Axios from "axios";

class JobService {
  static create(jobRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.put("/api/customer/job", jobRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static list() {
    const token = window.localStorage.getItem("token");
    return Axios.get("/api/my/jobs", {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static get(jobId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/job/${jobId}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static update(jobId, editJobRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.patch(`/api/customer/job/${jobId}`, editJobRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static listServices() {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/service/list`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}

export default JobService;
