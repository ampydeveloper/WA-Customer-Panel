import VueFormGenerator from "vue-form-generator";

export default {
    fields: [
        {
            type: "input",
            inputType: "text",
            label: "Email*",
            model: "email",
            min: 6,
            required: true,
            validator: VueFormGenerator.validators.email
        }
    ]
};
