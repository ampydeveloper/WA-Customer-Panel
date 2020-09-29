import Axios from "axios";

class CardService {
  static list() {
    const token = window.localStorage.getItem("token");
    return Axios.get("/api/payment/customer/cards", {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static create(cardRequest) {
    const token = window.localStorage.getItem("token");
    return Axios.put("/api/payment/customer/add-card", cardRequest, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }

  static delete(cardId) {
    const token = window.localStorage.getItem("token");
    return Axios.delete("/api/payment/customer/card/"+cardId, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}

export default CardService;
