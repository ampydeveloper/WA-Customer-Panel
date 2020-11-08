import Axios from "axios";

class FarmService {
    static create(farmRequest) {
        const token = window.localStorage.getItem("token");
        return Axios.post("/api/customer/farm", farmRequest, {
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
        // return Axios.get(`/api/customer/farm/${farmId}/managers`, {
        return Axios.get(`/api/my/managers`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static getManager(managerId) {
        const token = window.localStorage.getItem("token");
        return Axios.get(`/api/my/managers/${managerId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }

    static createManager(managerRequest, farmId) {
        const token = window.localStorage.getItem("token");
        let farm = managerRequest.farm_id;
        if (typeof(farm_id) == 'undefined') { farm = farmId; }
        return Axios.post(
            `/api/customer/farm/${farm}/manager`,
            managerRequest, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        );
    }

    static saveManager(managerRequest, farmId, managerId) {
        const token = window.localStorage.getItem("token");
        let farm = managerRequest.farm_id;
        let manager = managerRequest.id;
        if (typeof(farm_id) == 'undefined') { farm = farmId; }
        if (typeof(manager) == 'undefined') { manager = managerId; }
        return Axios.post(
            `/api/customer/farm/${farm}/manager/${manager}`,
            managerRequest, {
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

    static changeManager(farmId, managerId) {
        const token = window.localStorage.getItem("token");
        return Axios.get(`api/customer/farm/${farmId}/change-manager/${managerId}`, {
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
        return Axios.post(`/api/customer/farm/${farmId}`, editFarmRequest, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    }
}

export default FarmService;