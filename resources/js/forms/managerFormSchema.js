import VueFormGenerator from "vue-form-generator";
import FarmService from "../services/FarmService";

export default {
    fields: [{
            type: "input",
            inputType: "text",
            label: "First Name",
            model: "manager_first_name",
            required: true,
            validator: ["required"],
            styleClasses: 'col-md-4 mb-0 pb-0'
        },
        {
            type: "input",
            inputType: "text",
            label: "Last Name",
            model: "manager_last_name",
            required: true,
            validator: ["required"],
            styleClasses: 'col-md-4 mb-0 pb-0'
        },
        {
            type: "input",
            inputType: "email",
            label: "Email",
            model: "email",
            required: true,
            validator: [
                VueFormGenerator.validators.email,
                async(value, field, model) => {
                    let result = [];
                    try {
                        await FarmService.isEmailUnique(value);
                    } catch (error) {
                        result = error.response.data.data.email;
                    }
                    return result;
                }
            ],
            styleClasses: 'col-md-4 mb-0 pb-0'
        },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Phone Number",
        //     model: "manager_phone",
        //     required: true,
        //     validator: ["required"],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Address",
        //     model: "manager_address",
        //     required: true,
        //     validator: ["required"],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "City",
        //     model: "manager_city",
        //     required: true,
        //     validator: ["required"],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Province",
        //     model: "manager_province",
        //     required: true,
        //     validator: ["required"],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Zipcode",
        //     model: "manager_zipcode",
        //     required: true,
        //     validator: [
        //         "required",
        //         (value, field, model) => {
        //             var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
        //             return isValidZip ? [] : ["Invalid Zipcode"];
        //         }
        //     ],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Card ID",
        //     model: "manager_id_card",
        //     required: true,
        //     validator: ["required"],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // },
        // {
        //     type: "input",
        //     inputType: "text",
        //     label: "Salary",
        //     model: "salary",
        //     required: true,
        //     validator: [
        //         "required",
        //         (value, field, model) => {
        //             let result = [];
        //             if (isNaN(parseInt(value))) {
        //                 result = ["Invalid Salary Value"];
        //             }
        //             return result;
        //         }
        //     ],
        //     styleClasses: 'col-md-4 mb-0 pb-0'
        // }
    ]
};