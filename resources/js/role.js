export const Role = {
  Admin: 1,
  AdminManager: 2,
  TruckDriver: 3,
  Customer: 4,
  CustomerManager: 5,
  Hauler: 6
};

// define a mixin object
export const authorizationMixin = {
  data: function() {
    return {
      currentUser: {},
      isHauler: false,
      isCustomer: false,
      isManager: false
    };
  },
  created: function() {
    this.currentUser = JSON.parse(window.localStorage.getItem("user"));
    const userRole = this.currentUser ? this.currentUser.role_id : 0;
    this.isHauler = userRole === Role.Hauler;
    this.isCustomer = userRole === Role.Customer;
    this.isManager = userRole === Role.CustomerManager;
  }
};
