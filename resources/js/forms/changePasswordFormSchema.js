import VueFormGenerator from "vue-form-generator";

export default {
    fields: [
        {
            type: "input",
            inputType: "password",
            label: "Password*",
            model: "password",
            min: 6,
            required: true,
            validator: ["required", "string"]
        },
        {
            type: "input",
            inputType: "password",
            label: "Confirm Password*",
            model: "password_confirmation",
            min: 6,
            required: true,
            validator: [
                "required",
                VueFormGenerator.validators.string,
                (confPass, field, model) => {
                    let pass = model.password;
                    let error = [];
                    if (pass !== confPass) {
                        error = ["Passwords don't match."];
                    }
                    return error;
                }
            ]
        }
    ]
};
