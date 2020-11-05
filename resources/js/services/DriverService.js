import Axios from "axios";

class DriverService {
    static create(driverRequest) {
        const token = window.localStorage.getItem("token");
        return Axios.post("/api/hauler/driver/create", driverRequest, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static list() {
        const token = window.localStorage.getItem("token");
        return Axios.get(`/api/hauler/driver/all`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static get(driverId) {
        const token = window.localStorage.getItem("token");
        return Axios.get(`/api/hauler/driver/${driverId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static delete(driverId) {
        const token = window.localStorage.getItem("token");
        return Axios.delete(`/api/hauler/driver/${driverId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static update(driverId, editDriverRequest) {
        const token = window.localStorage.getItem("token");
        return Axios.post(`/api/hauler/driver/${driverId}`, editDriverRequest, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }
}

export default DriverService;