import Axios from "axios";

class AuthService {
    static register(registerRequest) {
        return Axios.post("/api/auth/signup", registerRequest);
    }

    static signIn(signInRequest) {
        return Axios.post("/api/auth/login", signInRequest);
    }

    static forgotPassword(forgotPasswordRequest) {
        return Axios.post("/api/auth/forgot-password", forgotPasswordRequest);
    }

    static changePassword(changePasswordRequest) {
        return Axios.post("/api/auth/change-password", changePasswordRequest);
    }
}

export default AuthService;
