export default {
  fields: [
    {
      type: "input",
      inputType: "text",
      label: "Address",
      model: "farm_address",
      required: true,
      validator: ["required", "string"],
      styleClasses:'col-md-4'
    },
    {
      type: "input",
      inputType: "text",
      label: "City",
      model: "farm_city",
      required: true,
      validator: ["required"],
      styleClasses:'col-md-4'
    },
    {
      type: "input",
      inputType: "text",
      label: "Province",
      model: "farm_province",
      required: true,
      validator: ["required"],
      styleClasses:'col-md-4'
    },
    {
      type: "input",
      inputType: "text",
      label: "Zipcode",
      model: "farm_zipcode",
      required: true,
      validator: [
        "required",
        (value, field, model) => {
          var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
          return isValidZip ? [] : ["Invalid Zipcode"];
        }
      ],
      styleClasses:'col-md-4'
    },
    // {
    //   type: "input",
    //   inputType: "text",
    //   label: "Latitude*",
    //   model: "latitude",
    //   required: true,
    //   validator: ["required"],
    //   styleClasses:'col-md-4'
    // },
    // {
    //   type: "input",
    //   inputType: "text",
    //   label: "Longitude*",
    //   model: "longitude",
    //   required: true,
    //   validator: ["required"],
    //   styleClasses:'col-md-4'
    // }
  ]
};
