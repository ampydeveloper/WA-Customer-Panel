<template>
  <div class="loc-page logged-in-page">

    <div class="main-wrapper">
      <section class="page-section-top" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h2>Job Summary</h2>
            </div>
          </div>
        </div>
      </section>

      <section class="job-details-outer center-content-outer" data-aos="">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 content-left-outer">
              <div class="map-route-outer">
                <div id='map' class='contain' style="float: left; width: 100%; position: relative; height: 300px;"></div>
              </div>

              <div class="job-summary-outer">
                <!-- <h5 class="heading2">Job Summary</h5> -->

                <div class="images-list">
                  <h6>Service Images</h6>
                  <div class="images-all row">
                    
                  </div>
                </div>

                <div class="details-list-outer row">
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job ID</p>
                    <p class="detail-item">#JOB{{job.id}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Service</p>
                    <p class="detail-item">{{ (job.service) ? job.service.service_name : 'N/A'}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Cost</p>
                    <p class="detail-item">${{job.amount}}</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Manager</p>
                    <p class="detail-item">{{ (job.manager) ? job.manager.full_name : '-'}}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Farm Location</p>
                    <p class="detail-item">{{ (job.farm) ? job.farm.full_address : 'N/A' }}</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Date / Est. Time</p>
                    <p class="detail-item">24, May 2020 at 9:30 AM</p>
                  </div>

                  <div class="each-details col-sm-4">
                    <p class="head-item">Quantity</p>
                    <p class="detail-item">{{job.weight}} Ton</p>
                  </div>
                  <div class="each-details col-sm-4">
                    <p class="head-item">Gate Number</p>
                    <p class="detail-item">{{ job.gate_no }}</p>
                  </div>
                  <!-- <div class="each-details">
                    <p class="head-item">Payment Status</p>
                    <p class="detail-item">Unpaid	</p>
                  </div> -->
                  <div class="each-details col-sm-4">
                    <p class="head-item">Job Status</p>
                    <p class="detail-item green-sm-box">{{ job.job_status_name }}</p>
                  </div>

                  <div class="each-details-full col-sm-12">
                    <p class="head-item">Notes</p>
                    <p class="detail-item">{{ job.notes }}</p>
                  </div>
                  
                </div>
              </div>
            </div>

            <div class="col-sm-6 content-right-outer">
              <div class="map-route-outer">CHAT comes here</div>
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
export default {
  components: {
    AppSmallHeader,
    AppSmallFooter,
    //  PlusIcon,
  },
  data() {
    return {
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
          service_name: ''
        }
      }
    };
  },

  created: async function() {
    try {
      const response = await JobService.get(this.$route.params.jobId);
      this.job = response.data.data;
    } catch (error) {
      this.$toast.open({
        message: error.response.data.message,
        type: "error",
        position: "bottom-right",
        dismissible: false
      });
    }
  },

  mounted() {
    mapboxgl.accessToken = 'pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA';
    var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/light-v9',
      center: [-122.662323, 45.523751], // starting position
      zoom: 12
    });

      // initialize the map canvas to interact with later
    var canvas = map.getCanvasContainer();

    // This is truck driver co-ordinates
    var start = [-122.662323, 45.523751];

    // create a function to make a directions request
    function getRoute(start, end) {
      var url = 'https://api.mapbox.com/directions/v5/mapbox/driving/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?steps=true&geometries=geojson&access_token=' + mapboxgl.accessToken;

      var req = new XMLHttpRequest();
      req.open('GET', url, true);
      req.onload = function() {
        var json = JSON.parse(req.response);
        var data = json.routes[0];
        var route = data.geometry.coordinates;
        var geojson = {
          type: 'Feature',
          properties: {},
          geometry: {
            type: 'LineString',
            coordinates: route
          }
        };
        // if the route already exists on the map, reset it using setData
        if (map.getSource('route')) {
          map.getSource('route').setData(geojson);
        } else { 
          // otherwise, make a new request
          map.addLayer({
            id: 'route',
            type: 'line',
            source: {
              type: 'geojson',
              data: {
                type: 'Feature',
                properties: {},
                geometry: {
                  type: 'LineString',
                  coordinates: geojson
                }
              }
            },
            layout: {
              'line-join': 'round',
              'line-cap': 'round'
            },
            paint: {
              'line-color': '#3887be',
              'line-width': 5,
              'line-opacity': 0.75
            }
          });
        }
      };
      req.send();
    }

    // this is where the code for the next step will go
    map.on('load', function() {
      // make an initial directions request that
      // starts and ends at the same location
      getRoute(start, [-122.61365699963287, 45.51773726437733]);

      map.addSource('truck', { 
          type: 'geojson', 
          data: {
          type: 'FeatureCollection',
          features: [{
            type: 'Feature',
            properties: {},
            geometry: {
              type: 'Point',
              coordinates: start
            }
          }]
        }
      });
      // Add starting point to the map
      map.addLayer({
        id: 'truck',
        type: 'symbol',
        source: 'truck',
        layout: {
          'icon-image': 'bus-15'
        }
      });
      // this is where the code from the next step will go

      map.addLayer({
        id: 'end',
        type: 'circle',
        source: {
          type: 'geojson',
          data: {
            type: 'FeatureCollection',
            features: [{
              type: 'Feature',
              properties: {},
              geometry: {
                type: 'Point',
                coordinates: [-122.61365699963287, 45.51773726437733]
              }
            }]
          }
        },
        paint: {
          'circle-radius': 10,
          'circle-color': '#f30'
        }
      });

      getRoute(start, [-122.61365699963287, 45.51773726437733]);

    });
    window.lo = 45.523751;
    window.setInterval(function () {
      lo = lo - .0001
      start = [-122.662453, lo];
      
      map.getSource('truck').setData({
            type: 'Feature',
            properties: {},
            geometry: {
              type: 'Point',
              coordinates: start
            }
        });
      map.flyTo({
        center: start,
        speed: 0.5
      });
      getRoute(start, [-122.61365699963287, 45.51773726437733]);
    }, 2000);
  },
};
</script>