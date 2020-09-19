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
        }
    ]
};
