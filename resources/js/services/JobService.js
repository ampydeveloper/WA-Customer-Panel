import Axios from "axios";

class JobService {
  static create(jobRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.post("/api/customer/job", jobRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static list(farmId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/farm/${farmId}/jobs`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static upcomingJobsList(farmId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/farm/${farmId}/jobs/upcoming`, {
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

  static cancel(jobId) {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/customer/job/${jobId}/cancel`, {
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

  static myJobs() {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/my/jobs`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static myUpcomingJobs() {
    const token = window.localStorage.getItem("token");
    return Axios.get(`/api/my/jobs/upcoming`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

}

export default JobService;
