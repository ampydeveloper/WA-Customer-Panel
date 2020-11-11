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
               <span class="bg-custom-thickness">Locate</span> the Wellington Driver.  <br />
               <span class="bg-custom-thickness">Communicate</span> with the Manager & Driver.
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
                <!-- <h5 class="heading2">Job Summary</h5> -->

                <!-- <div class="images-list">
                  <h6>Service Images</h6>
                  <div class="images-all row">
                    <div v-for="image in jobImages">
 <img :src="image" alt="">
                    </div>                   
                  </div>
                </div> -->

                <div class="details-list-outer row">
                  <div class="each-details col-sm-6">
                    <p class="head-item">Pickup ID</p>
                    <p class="detail-item">#JOB{{ job.id }}</p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Service</p>
                    <p class="detail-item">
                      {{ job.service ? job.service.service_name : "" }}
                    </p>
                  </div>
                  <div class="each-details col-sm-6">
                    <p class="head-item">Cost</p>
                    <p class="detail-item">${{ job.amount }}</p>
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
                  <!-- <div class="each-details col-sm-6">
                    <p class="head-item">Date / Est. Time</p>
                    <p class="detail-item">24, May 2020 at 9:30 AM</p>
                  </div> -->

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
                        id="current-user-image"
                        :value="userdata.image_url"
                      />

                      <span class="upload-images-out">
                        <image-icon
                          size="1.5x"
                          class="custom-class"
                        ></image-icon>
                      </span>
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
import mapboxgl from "mapbox-gl";
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
      this.job = response.data.data;
       this.jobImages =JSON.parse(JSON.stringify(response.data.data.images))
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false,
      });
    }

    try {
      const response = await JobService.chatUsers(this.$route.params.jobId);
      var result = response.data.data;
      var users = [];
      users[response.data.customer_id] = result.customer;
      users[response.data.manager_id] = result.manager;
      users[response.data.skidsteer_driver_id] = result.skidsteer_driver;
      users[response.data.truck_driver_id] = result.truck_driver;
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false,
      });
    }

    try {
      const response = await JobService.getJobChatMessages(
        this.$route.params.jobId
      );
      if (response) {
        response.data.data.forEach(function (val, index) {
          const messageElement = document.createElement("div");
          messageElement.className = "chat-receiver";
          messageElement.innerHTML =
            '<div class="chat-msg">' +
            `${val.message}` +
            '</div><div class="chat-img"><img src="' +
            `${environment.baseUrl + "/images/avatar.png"}` +
            '"></div>';
          $(document).find("#message-container").prepend(messageElement);
          $("#message-container .empty-message").remove();
        });
      }
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false,
      });
    }
  },

  mounted() {
    mapboxgl.accessToken =
      "pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA";
    var map = new mapboxgl.Map({
      container: "map",
      style: "mapbox://styles/mapbox/light-v9",
      center: [-122.662323, 45.523751], // starting position
      zoom: 12,
    });

    // initialize the map canvas to interact with later
    var canvas = map.getCanvasContainer();

    // This is truck driver co-ordinates
    var start = [-122.662323, 45.523751];

    // create a function to make a directions request
    function getRoute(start, end) {
      var url =
        "https://api.mapbox.com/directions/v5/mapbox/driving/" +
        start[0] +
        "," +
        start[1] +
        ";" +
        end[0] +
        "," +
        end[1] +
        "?steps=true&geometries=geojson&access_token=" +
        mapboxgl.accessToken;

      var req = new XMLHttpRequest();
      req.open("GET", url, true);
      req.onload = function () {
        var json = JSON.parse(req.response);
        var data = json.routes[0];
        var route = data.geometry.coordinates;
        var geojson = {
          type: "Feature",
          properties: {},
          geometry: {
            type: "LineString",
            coordinates: route,
          },
        };
        // if the route already exists on the map, reset it using setData
        if (map.getSource("route")) {
          map.getSource("route").setData(geojson);
        } else {
          // otherwise, make a new request
          map.addLayer({
            id: "route",
            type: "line",
            source: {
              type: "geojson",
              data: {
                type: "Feature",
                properties: {},
                geometry: {
                  type: "LineString",
                  coordinates: geojson,
                },
              },
            },
            layout: {
              "line-join": "round",
              "line-cap": "round",
            },
            paint: {
              "line-color": "#3c3b3b",
              "line-width": 4,
              "line-opacity": 0.75,
            },
          });
        }
      };
      req.send();
    }

    map.on("load", function () {
      // make an initial directions request that
      // starts and ends at the same location
      getRoute(start, [-122.61365699963287, 45.51773726437733]);

      map.addSource("truck", {
        type: "geojson",
        data: {
          type: "FeatureCollection",
          features: [
            {
              type: "Feature",
              properties: {},
              geometry: {
                type: "Point",
                coordinates: start,
              },
            },
          ],
        },
      });

      map.loadImage(
        `http://wa.customer.leagueofclicks.com/img/car-marker2.png`,
        function (error, image) {
          if (error) throw error;
          map.addImage("car-marker", image);
        }
      );

      // Add starting point to the map
      map.addLayer({
        id: "truck",
        type: "symbol",
        source: "truck",
        layout: {
          "icon-image": "car-marker",
        },
      });

      var el = document.createElement("div");
      el.className = "map-marker";
      new mapboxgl.Marker(el)
        .setLngLat([-122.61365699963287, 45.51773726437733])
        .addTo(map);

      getRoute(start, [-122.61365699963287, 45.51773726437733]);
    });
    window.lo = 45.523751;
    window.setInterval(function () {
      lo = lo - 0.0001;
      start = [-122.662453, lo];

      map.getSource("truck").setData({
        type: "Feature",
        properties: {},
        geometry: {
          type: "Point",
          coordinates: start,
        },
      });
      map.flyTo({
        center: start,
        speed: 0.5,
      });
      getRoute(start, [-122.61365699963287, 45.51773726437733]);
    }, 2000);
    //map END

    // this.getChatMembers();
    // setTimeout(() => {
    //   this.getChatMessages();
    // }, 500);

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

      const socket = io.connect("http://13.235.151.113:3100", { secure: true });
      const messageContainer = document.getElementById("message-container");
      const messageForm = document.getElementById("send-container");
      const messageInput = document.getElementById("message-input");
      const name = document.getElementById("user-details-id");
      const jobId = document.getElementById("job-id");

      socket.emit("new-user", name._value);
      messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });

      socket.on("chat-message", (data) => {
        const userImage = $("#current-user-image").val();
        if (data.name == name._value) {
          appendMessage(
            '<div class="chat-msg">' +
              `${data.message.message}` +
              '</div><div class="chat-img"><img src="' +
              `${userImage}` +
              '"></div>'
          );
        } else {
          appendMessage(
            '<div class="chat-msg">' +
              `${data.message.message}` +
              '</div><div class="chat-img"><img src="' +
              `${environment.baseUrl + "/images/avatar.png"}` +
              '"></div>'
          );
        }
        messageContainerScroll.scroll([0, "100%"], 50, { x: "", y: "linear" });
      });
      $(document).on("submit", "#send-container", function (e) {
        const message = messageInput.value;
        const userImage = $("#current-user-image").val();
        if (message != "") {
          appendMessage(
            '<div class="chat-msg">' +
              `${message}` +
              '</div><div class="chat-img"><img src="' +
              `${userImage}` +
              '"></div>'
          );
          socket.emit("send-chat-message", {
            message: message,
            job_id: jobId._value,
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
      function appendMessage(message) {
        const messageElement = document.createElement("div");
        messageElement.className = "chat-receiver";
        messageElement.innerHTML = message;
        $(document)
          .find("#message-container")
          .find(".os-content")
          .prepend(messageElement);
        $("#message-container .empty-message").remove();
      }
    }, 1000);
  },
  methods: {
    // getChatMembers() {
    //   const response = await JobService.chatUsers(this.$route.params.jobId);
    //   var result = response.data.data;
    //   var users = [];
    //       users[response.data.customer_id] = result.customer;
    //       users[response.data.manager_id] = result.manager;
    //       users[response.data.skidsteer_driver_id] =
    //         result.skidsteer_driver;
    //       users[response.data.truck_driver_id] = result.truck_driver;
    //       // this.chatUsers = users;
    // },
    // getChatMessages() {
    //   const response = await JobService.getJobChatMessages(this.$route.params.jobId);
    //   if (response) {
    //         response.data.data.forEach(function (val, index) {
    //           const messageElement = document.createElement("div");
    //           messageElement.className = "chat-receiver";
    //           messageElement.innerHTML =
    //             '<div class="chat-msg">' +
    //             `${val.message}` +
    //             '</div><div class="chat-img"><img src="' +
    //             `${environment.baseUrl + "/images/avatar.png"}` +
    //             '"></div>';
    //           $(document).find("#message-container").prepend(messageElement);
    //           $("#message-container .empty-message").remove();
    //         });
    //       }
    // },
  },
};
</script>