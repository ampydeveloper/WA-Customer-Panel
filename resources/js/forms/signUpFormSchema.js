import VueFormGenerator from "vue-form-generator";

export default {
    fields: [
        {
            type: "input",
            inputType: "text",
            label: "First Name*",
            model: "first_name",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Last Name*",
            model: "last_name",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "email",
            label: "Email*",
            model: "email",
            required: true,
            validator: VueFormGenerator.validators.email
        },
        {
            type: "select",
            label: "Role",
            model: "role_id",
            required: true,
            values: function() {
                return [
                    { id: 4, name: "Customer" },
                    { id: 6, name: "Hallower" }
                ];
            },
            default: 4,
            validator: ["required"]
        },
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
        },
        {
            type: "input",
            inputType: "text",
            label: "Phone Number*",
            model: "phone",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Address*",
            model: "address",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "City*",
            model: "city",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Province*",
            model: "province",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Zipcode*",
            model: "zipcode",
            required: true,
            validator: [
                "required",
                (value, field, model) => {
                    var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
                    return isValidZip ? [] : ["Invalid Zipcode"];
                }
            ]
        }
    ]
};
