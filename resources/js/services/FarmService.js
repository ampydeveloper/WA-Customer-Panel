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
}

export default FarmService;
