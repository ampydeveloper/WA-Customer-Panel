<template>
  <div class="loc-page logged-in-page">
    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h2>
                Pickup
                <br />
                <span class="bg-custom-thickness"> Summary </span>
              </h2>
            </div>
            <div class="col-md-6">
              <div class="desc-details">
                <h2>
                  <span class="bg-custom-thickness">Locate</span> the Wellington
                  Driver. <br />
                  <span class="bg-custom-thickness">Communicate</span> with the
                  Manager & Driver.
                </h2>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="job-details-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="map-route-outer">
                <div id="map" class="contain"></div>
              </div>
            </div>

            <div class="col-sm-4 content-left-outer">
              <div class="job-summary-outer">
                                <div class="images-list" v-if='jobImages!=""'>
                  <h6>Service Images</h6>
                  <div class="images-all row">
                    <div v-for="image in jobImages">
                      <img :src="image" alt="" />
                    </div>
                  </div>
                </div>

                <div class="details-list-outer row">
                  <div class="each-details col-sm-6">
                    <p class="head-item">Pickup ID</p>
                    <p class="detail-item">#PICKUP{{ job.id }}</p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Service</p>
                    <p class="detail-item">
                      {{ job.service ? job.service.service_name : "" }}
                    </p>
                  </div>

                  <div class="each-details col-sm-6">
                    <p class="head-item">Manager</p>
                    <p class="detail-item">
                      {{ job.manager ? job.manager.full_name : "" }}
                    </p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Farm Location</p>
                    <p class="detail-item">
                      {{ job.farm ? job.farm.full_address : "" }}
                    </p>
                  </div>
                  <div class="each-details-full col-sm-12">
                    <p class="head-item">Techs</p>
                    <p class="detail-item">
                      <span v-if="job.skidsteer_driver"
                  >{{
                    job.skidsteer_driver.first_name +
                    " " +
                    job.skidsteer_driver.last_name +
                    ", "
                  }}
                </span>
                <span v-if="job.skidsteer_driver">
                  {{
                    job.truck_driver.first_name +
                    " " +
                    job.truck_driver.last_name
                  }}
                </span>
                    </p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Quantity</p>
                    <p class="detail-item">{{ job.weight }} Ton</p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Gate Number</p>
                    <p class="detail-item">{{ job.gate_no }}</p>
                  </div>
                  <!-- <div class="each-details">
                    <p class="head-item">Payment Status</p>
                    <p class="detail-item">Unpaid	</p>
                  </div> -->
                  <div class="each-details col-sm-6">
                    <p class="head-item">Job Status</p>
                    <p class="detail-item green-sm-box">
                      {{ job.job_status_name }}
                    </p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Total</p>
                    <p class="detail-item">${{ job.amount ? job.amount : "0" }}</p>
                  </div>

                  <div class="each-details-full col-sm-12">
                    <p class="head-item">Notes</p>
                    <p class="detail-item">{{ job.notes }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-8 content-right-outer">
              <v-col cols="12" md="12" class="main_box chat-area-outer">
                <div class="chat-area" id="message-container">
                  <div class="empty-message">
                    <p>
                      Lets start conversing <br />
                      & <br />
                      find solutions.
                    </p>
                  </div>
                  <div class="uploading-image-out">
                    <loader-icon size="1.5x" class="custom-class"></loader-icon>
                    <p>Uploading image</p>
                  </div>
                </div>
              </v-col>

              <v-col cols="12" md="12" class="main_box chat-form-outer">
                <div class="type_msg">
                  <form id="send-container" autocomplete="off">
                    <div class="input_msg_write">
                      <input
                        type="hidden"
                        id="user-details-id"
                        :value="userdata.id"
                      />
                      <input type="hidden" id="job-id" :value="job.id" />
                      <input
                        type="hidden"
                        id="job-lat"
                        :value="job.farm != null ? job.farm.latitude : ''"
                      />
                      <input
                        type="hidden"
                        id="job-long"
                        :value="job.farm != null ? job.farm.longitude : ''"
                      />
                      <input
                        type="hidden"
                        id="current-user-image"
                        :value="userdata.image_url"
                      />
                      <input
                        type="hidden"
                        id="all-user-data"
                        :value="JSON.stringify(chatUsers)"
                      />
                      <input type="file" id="image-file" name="chat-image" />
                      <label class="upload-images-out" for="image-file">
                        <image-icon
                          size="1.5x"
                          class="custom-class"
                        ></image-icon>
                      </label>
                      <input
                        type="text"
                        class="write_msg"
                        placeholder="Type your message"
                        id="message-input"
                      />
                      <button
                        class="msg_send_btn"
                        id="send-button"
                        type="submit"
                      >
                        <send-icon size="1.5x" class="custom-class"></send-icon>
                      </button>
                    </div>
                  </form>
                </div>
              </v-col>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import AppSmallHeader from "../../../shared/components/AppSmallHeader";
import AppSmallFooter from "../../../shared/components/AppSmallFooter";
import JobService from "../../../services/JobService";
import router from "../../../router";
import { SendIcon, ImageIcon, LoaderIcon } from "vue-feather-icons";
export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
    SendIcon,
    ImageIcon,
    LoaderIcon,
  },
  data() {
    return {
      userdata: [],
      jobImages: [],
      chatUsers: "",
      job: {
        farm_id: "",
        service_id: "",
        time_slots_id: "",
        job_providing_date: "2020-01-01",
        weight: 1,
        amount: 0,
        notes: "",
        is_repeating_job: false,
        repeating_days: 0,
        service: {
          service_name: "",
        },
      },
    };
  },

  created: async function () {
    this.userdata = JSON.parse(window.localStorage.getItem("user"));

    try {
      const response = await JobService.get(this.$route.params.jobId);
      // console.log(response.data);
      // console.log(response.data.images);
      this.job = response.data.data;
      // this.jobImages = JSON.parse(response.data.images);
      this.getChatMembers();
    } catch (error) {
      console.log(error);
      // this.$toast.open({
      //   message: error.response.data.message,
      //   type: "error",
      //   position: "bottom-right",
      //   dismissible: false,
      // });
    }

    $(document).ready(function () {
      feather.replace();
    });
  },

  mounted() {
    var wellOffice = "26.695145,-80.244859";
    var icons = {
      start: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/car-marker2.png",
        new google.maps.Size(72, 100),
        new google.maps.Point(0, 0),
        new google.maps.Point(22, 32)
      ),
      end: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/map-icon2.png",
        new google.maps.Size(55, 55),
        new google.maps.Point(0, 0),
        new google.maps.Point(22, 32)
      ),
    };
    var map;
    function initMap() {
      const directionsService = new google.maps.DirectionsService();
      const directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
      });
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 41.85, lng: -87.65 },
        mapTypeControl: false,
        draggable: false,
        scaleControl: false,
        scrollwheel: false,
        navigationControl: false,
        streetViewControl: false,
        styles: [
          {
            featureType: "landscape.natural",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "landscape.natural",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.text",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.stroke",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "geometry.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.bus",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.rail",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry.fill",
            stylers: [
              {
                color: "#e2f6fe",
              },
            ],
          },
        ],
      });
      directionsRenderer.setMap(map);

      calculateAndDisplayRoute(directionsService, directionsRenderer);
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
      const jobLat = document.getElementById("job-lat")._value;
      const joblong = document.getElementById("job-long")._value;
      directionsService.route(
        {
          origin: {
            query: wellOffice,
          },
          destination: {
            query: jobLat + "," + joblong,
          },
          travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
          if (status === "OK") {
            directionsRenderer.setDirections(response);
            var leg = response.routes[0].legs[0];
            makeMarker(leg.start_location, icons.start, "Wellington Office");
            makeMarker(leg.end_location, icons.end, "Farm");
          }
        }
      );
    }
    function makeMarker(position, icon, title) {
      new google.maps.Marker({
        position: position,
        map: map,
        icon: icon,
        title: title,
      });
    }

    setTimeout(function () {
      initMap();
    }, 6000);

    let socketScript = document.createElement("script");
    socketScript.setAttribute(
      "src",
      "https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.1/socket.io.js"
    );
    document.head.appendChild(socketScript);
    let scrollScript = document.createElement("script");
    scrollScript.setAttribute(
      "src",
      "https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.12.0/js/OverlayScrollbars.min.js"
    );
    document.head.appendChild(scrollScript);
    let scrollScript2 = document.createElement("link");
    scrollScript2.setAttribute(
      "href",
      "https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.12.0/css/OverlayScrollbars.min.css"
    );
    scrollScript2.setAttribute("rel", "stylesheet");
    document.head.appendChild(scrollScript2);

    var messageContainerScroll;
    setTimeout(function () {
      messageContainerScroll = OverlayScrollbars(
        document.querySelectorAll("#message-container"),
        {}
      );

      const socket = io.connect("https://wa.customer.leagueofclicks.com:3100", {
        secure: true,
      });
      const messageContainer = document.getElementById("message-container");
      const messageForm = document.getElementById("send-container");
      const messageInput = document.getElementById("message-input");
      const name = document.getElementById("user-details-id");
      const jobId = document.getElementById("job-id");

      socket.emit("new-user", name._value);
      messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });
var chatUsersList;
      chatUsersList = JSON.parse(document.getElementById("all-user-data").value);
      const emitChannel = "chat-message"; //"chatmessage"+jobId
      socket.on(emitChannel, (data) => {
        const userImage = $("#current-user-image").val();
        var messageText;
        if (data.message.message.indexOf("uploads") > -1) {
           messageText =
            '<img class="chat-image-in" src="' +
            `${data.message.message}` +
            '">';
            var imageClass = 'inc-img';
        } else {
           messageText = data.message.message;
           var imageClass = '';
        }
        if (data.job_id == jobId._value) {
          if (data.name == name._value) {
            if($('.'+data.message_id).length == 0 && $("." + data.message.check_string).length == 0){
            appendMessage(
              '<div class="chat-msg '+data.message_id +' '+imageClass+'">' +
                `${messageText}` +
                '</div><div class="chat-img"><img src="' +
                `${userImage}` +
                '"></div>',
              "chat-receiver"
            );
            }
          } else {
            chatUsersList = JSON.parse(document.getElementById("all-user-data").value);
            if (typeof chatUsersList[data.name] != "undefined") {
              var userImageLink = chatUsersList[data.name].user_image;
            } else {
              var userImageLink = "/images/avatar.png";
            }
             if($('.'+data.message_id).length == 0){
            appendMessage(
              '<div class="chat-msg '+data.message_id+ " " + imageClass +'">' +
                `${messageText}` +
                '</div><div class="chat-img"><img src="' +
                `${userImageLink}` +
                '"></div>',
              "chat-sender"
            );
             }
          }
          messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
        }
      });
      $(document).on("submit", "#send-container", function (e) {
        const message = messageInput.value;
        const userImage = $("#current-user-image").val();
        const check_string = Math.random().toString(36).substring(3);
        if (message != "") {
          appendMessage(
            '<div class="chat-msg '+check_string+'">' +
              `${message}` +
              '</div><div class="chat-img"><img src="' +
              `${userImage}` +
              '"></div>',
            "chat-receiver"
          );
          socket.emit("send-chat-message", {
            message: message,
            job_id: jobId._value,
            username: name._value,
            check_string:check_string
          });
          messageInput.value = "";
          $("#send-button").attr("disabled", false);
          messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
        }
        e.preventDefault();
      });
      function appendMessage(message, className) {
        const messageElement = document.createElement("div");
        messageElement.className = className;
        messageElement.innerHTML = message;
        $(document)
          .find("#message-container")
          .find(".os-content")
          .prepend(messageElement);
        $("#message-container .empty-message").remove();
      }
      $(document).on("change", "#image-file", function (e) {
        var $this = $(this);
        if ($this.val() != "" && !self.fired) {
          self.fired = true;
          const currentToken = window.localStorage.getItem("token");
          const userImage = $("#current-user-image").val();
          var imageData = new FormData();
          imageData.append("uploadImage", $("#image-file").prop("files")[0]);
          $.ajax({
            url: `http://wa.customer.leagueofclicks.com/api/auth/uploadImage`,
            headers: {
              Authorization: "Bearer " + currentToken,
            },
            data: imageData,
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            success: function (result) {
              var check_string = Math.random().toString(36).substring(3);
              const messageElement = document.createElement("div");
              messageElement.className = "chat-receiver"; //"chat-receiver"
              messageElement.innerHTML =
                '<div class="chat-msg inc-img '+check_string+'"><img class="chat-image-in" src="' +
                `${result}` +
                '"></div><div class="chat-img"><img src="' +
                `${userImage}` +
                '"></div>';
              $(document)
                .find("#message-container")
                .find(".os-content")
                .prepend(messageElement);
              socket.emit("send-chat-message", {
                message: result,
                job_id: jobId._value,
                username: name._value,
                check_string: check_string,
              });
              messageContainerScroll.scroll([0, "100%"], 50, {
            x: "",
            y: "linear",
          });
            },
            complete: function () {
              $("#image-file").val("");
              self.fired = false;
            },
          });
        }
      });
     
    }, 2000);
  },
  methods: {
    getChatMembers(){
      JobService.chatUsers(this.$route.params.jobId).then((response2) => {
        var result = response2.data.data;
        this.chatUsers = result;
        this.getChatMsgs();
      })
    },
    getChatMsgs(){
      const chatUsersList = this.chatUsers;
      const currentUserDetails = this.userdata;
      const response3 = JobService.getJobChatMessages({
        jobId: this.$route.params.jobId,
      }).then((response3) => {
        if (response3) {
          response3.data.data.forEach(function (val, index) {
            if (typeof val.username != "undefined") {
              if (typeof chatUsersList[val.username] != "undefined") {
                var userImageLink = chatUsersList[val.username].user_image;
              } else {
                var userImageLink =  "/images/avatar.png";
              }
                if (val.message.indexOf("uploads") > -1) {
                  var messageText =
                    '<img class="chat-image-in" src="' +
                    `${val.message}` +
                    '">';
                    var imageClass = 'inc-img';
                } else {
                  var messageText = val.message;
                  var imageClass = '';
                }
              const messageElement = document.createElement("div");
              if (currentUserDetails.id == val.username) {
                messageElement.className = "chat-receiver";
              } else {
                messageElement.className = "chat-sender";
              }
              messageElement.innerHTML =
                '<div class="chat-msg '+imageClass+'">' +
                `${messageText}` +
                '</div><div class="chat-img"><img src="' +
                `${userImageLink}` +
                '"></div>';
              $(document)
                .find("#message-container")
                .find(".os-content")
                .prepend(messageElement);
              $("#message-container .empty-message").remove();
            }
          });
        }
      })
    }
  }
};
</script>