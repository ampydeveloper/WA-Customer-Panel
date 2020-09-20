<template>
  <div>
    <dashboard-header />
    <MglMap
      :accessToken="accessToken"
      :mapStyle.sync="mapStyle"
      :zoom="zoom"
      :center="coordinates"
    >
      <!-- Adding navigation control -->
      <MglNavigationControl position="top-right" />
      <!-- Adding GeoJSON layer -->
      <MglGeojsonLayer
        :sourceId="geoJsonSource.id"
        :source="geoJsonSource"
        layerId="myLayer"
        :layer="geoJsonLayer"
      />
    </MglMap>
  </div>
</template>

<script>
import Mapbox from "mapbox-gl";
import { MglMap, MglNavigationControl, MglGeojsonLayer } from "vue-mapbox";
import DashboardHeader from "../../shared/components/DashboardHeader";

var geojson = {
  type: "FeatureCollection",
  features: [
    {
      type: "Feature",
      geometry: {
        type: "LineString",
        properties: {},
        coordinates: [
          [-77.0366048812866, 38.89873175227713],
          [-77.03364372253417, 38.89876515143842],
          [-77.03364372253417, 38.89549195896866],
          [-77.02982425689697, 38.89549195896866],
          [-77.02400922775269, 38.89387200688839],
          [-77.01519012451172, 38.891416957534204],
          [-77.01521158218382, 38.892068305429156],
          [-77.00813055038452, 38.892051604275686],
          [-77.00832366943358, 38.89143365883688],
          [-77.00818419456482, 38.89082405874451],
          [-77.00815200805664, 38.88989712255097]
        ]
      }
    }
  ]
};

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
      coordinates: [-77.0214, 38.897],
      geoJsonSource: {
        id: "LineString",
        type: "geojson",
        data: geojson
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
      }
    };
  },

  created() {
    // We need to set mapbox-gl library here in order to use it in template
    this.mapbox = Mapbox;
  }
};
</script>
