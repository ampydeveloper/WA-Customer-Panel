<template>
  <div>
    <dashboard-header />
    <div id='map' class='contain'>
   <!-- <MglMap
      :accessToken="accessToken"
      :mapStyle.sync="mapStyle"
      :zoom="zoom"
      :center="truckLocation"
    >
      
      <MglNavigationControl position="top-right" />
      
      <MglGeojsonLayer
        :sourceId="geoJsonSource.id"
        :source="geoJsonSource"
        layerId="myLayer"
        :layer="geoJsonLayer"
      />
    </MglMap> -->
    </div>
  </div>
</template>

<script>
import { MglMap, MglNavigationControl, MglGeojsonLayer } from "vue-mapbox";
import DashboardHeader from "../../shared/components/DashboardHeader";
import mapboxgl from "mapbox-gl";
// var geojson = {
//   type: "FeatureCollection",
//   features: [
//     {
//       type: "Feature",
//       geometry: {
//         type: "LineString",
//         properties: {},
//         coordinates: [
//           [-77.0366048812866, 38.89873175227713],
//           [-77.03364372253417, 38.89876515143842],
//           [-77.03364372253417, 38.89549195896866],
//           [-77.02982425689697, 38.89549195896866],
//           [-77.02400922775269, 38.89387200688839],
//           [-77.01519012451172, 38.891416957534204],
//           [-77.01521158218382, 38.892068305429156],
//           [-77.00813055038452, 38.892051604275686],
//           [-77.00832366943358, 38.89143365883688],
//           [-77.00818419456482, 38.89082405874451],
//           [-77.00815200805664, 38.88989712255097]
//         ]
//       }
//     }
//   ]
// };

export default {
  components: {
    MglMap,
    DashboardHeader,
    MglNavigationControl,
    MglGeojsonLayer
  },
  data() {
    return {
      accessToken:
        "pk.eyJ1IjoibGFyYXZlbGNoZCIsImEiOiJja2ZiNTVraWkwdWdsMnBweGFubnBxMWZtIn0.xY-ky0EqYfVZJmNI5Io4ew", // your access token. Needed if you using Mapbox maps
      mapStyle: "mapbox://styles/mapbox/streets-v11",
      zoom: 12,
      truckLocation: [-83.093, 42.376],
      warehouseLocation: [-83.083, 42.363],
      coordinates: [-122.486052, 37.830348],
      keepTrack: [],
      currentSchedule: [],
      currentRoute: null,
      pointHopper: {},
      pause: true,
      speedFactor: 50,
      geoJsonSource: {
        id: "LineString",
        type: "geojson",
        data: {
          type: "FeatureCollection",
          features: [
            {
              type: "Feature",
              properties: {},
              geometry: {
                type: "LineString",
                coordinates: [
                  [-122.48369693756104, 37.83381888486939],
                  [-122.48348236083984, 37.83317489144141],
                  [-122.48339653015138, 37.83270036637107],
                  [-122.48356819152832, 37.832056363179625],
                  [-122.48404026031496, 37.83114119107971],
                  [-122.48404026031496, 37.83049717427869],
                  [-122.48348236083984, 37.829920943955045],
                  [-122.48356819152832, 37.82954808664175],
                  [-122.48507022857666, 37.82944639795659],
                  [-122.48610019683838, 37.82880236636284],
                  [-122.48695850372314, 37.82931081282506],
                  [-122.48700141906738, 37.83080223556934],
                  [-122.48751640319824, 37.83168351665737],
                  [-122.48803138732912, 37.832158048267786],
                  [-122.48888969421387, 37.83297152392784],
                  [-122.48987674713133, 37.83263257682617],
                  [-122.49043464660643, 37.832937629287755],
                  [-122.49125003814696, 37.832429207817725],
                  [-122.49163627624512, 37.832564787218985],
                  [-122.49223709106445, 37.83337825839438],
                  [-122.49378204345702, 37.83368330777276]
                ]
              }
            }
          ]
        }
      },
      geoJsonLayer: {
        id: "LineString",
        type: "line",
        source: "LineString",
        layout: {
          "line-join": "round",
          "line-cap": "round"
        },
        paint: {
          "line-color": "#BF93E4",
          "line-width": 5
        }
      },
      response:{},
    };
  },

   created: async function() {
    try {
      this.response = await JobService.get(this.$route.params.jobId);
      this.job = this.response;
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
    mapboxgl.accessToken = 'pk.eyJ1IjoibGFyYXZlbGNoZCIsImEiOiJja2ZiNTVraWkwdWdsMnBweGFubnBxMWZtIn0.xY-ky0EqYfVZJmNI5Io4ew';
      var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v10',
        center: [-122.662323, 45.523751], // starting position
        zoom: 14
      });
      // set the bounds of the map
      var bounds = [[-123.069003, 45.395273], [-122.303707, 45.612333]];
      map.setMaxBounds(bounds);

      // initialize the map canvas to interact with later
      var canvas = map.getCanvasContainer();

      // an arbitrary start will always be the same
      // only the end or destination will change
      var start = [-122.662323, 45.523751];


      // create a function to make a directions request
      function getRoute(end) {
        // make a directions request using cycling profile
        // an arbitrary start will always be the same
        // only the end or destination will change
        var start = [-122.662323, 45.523751];
        var url = 'https://api.mapbox.com/directions/v5/mapbox/cycling/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?steps=true&geometries=geojson&access_token=' + mapboxgl.accessToken;

        // make an XHR request https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
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
          } else { // otherwise, make a new request
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
          // add turn instructions here at the end
        };
        req.send();
      }

      // this is where the code for the next step will go
      map.on('load', function() {
        // make an initial directions request that
        // starts and ends at the same location
        getRoute(start);

        // Add starting point to the map
        map.addLayer({
          id: 'point',
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
                  coordinates: start
                }
              }
              ]
            }
          },
          paint: {
            'circle-radius': 10,
            'circle-color': '#3887be'
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
        getRoute([-122.61365699963287, 45.51773726437733]);

      });
  }
};
</script>
