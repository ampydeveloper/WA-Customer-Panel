import VueFormGenerator from "vue-form-generator";

export default {
    legend: "Personal Info",
    fields: [
        {
            type: "input",
            inputType: "text",
            label: "User Name",
            model: "username",
            required: true,
            validator: ["string", "required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Full Name",
            model: "full_name",
            required: true,
            validator: ["string", "required"]
        },
        {
            type: "input",
            inputType: "text",
            label: "Company Name",
            model: "company_name",
            required: true,
            validator: ["string", "required"]
        },
        {
            type: "input",
            inputType: "email",
            label: "Email Address",
            model: "email",
            required: true,
            validator: VueFormGenerator.validators.email
        },
        {
            type: "input",
            inputType: "text",
            label: "Phone Number",
            model: "phone_number",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "password",
            label: "Password",
            model: "password",
            required: true,
            validator: ["required"]
        },
        {
            type: "input",
            inputType: "password",
            label: "Confirm Password",
            model: "confirm_password",
            required: true,
            validator: ["required"]
        }
    ]
};
