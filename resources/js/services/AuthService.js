import Axios from "axios";

class AuthService {
    static register(registerRequest) {
        return Axios.post("/api/auth/signup", registerRequest);
    }

    static signIn(signInRequest) {
        return Axios.post("/api/auth/login", signInRequest);
    }
}

export default AuthService;
