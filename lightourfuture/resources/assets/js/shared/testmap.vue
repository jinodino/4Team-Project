<template>
    <div class="container">
        <!-- <button @click="geoCoder">ekek</button>
       <div id="map" style="width:500px; height:500px">

       </div> -->
       <label>
      AutoComplete
      <gmap-autocomplete @place_changed="setPlace">

      </gmap-autocomplete>
      <button @click="usePlace">Add</button>
    </label>
    <br/>
<!-- "{lat: this.lat, lng: this.lng}" -->
    <GmapMap style="width: 600px; height: 300px;" :zoom=this.zoom :center=this.aa>
      <GmapMarker v-for="(marker, index) in markers"
        :key="index"
        :position="marker.position"
        />
      <GmapMarker
        v-if="this.place"
        label="★"
        :position="{
          lat: this.place.geometry.location.lat(),
          lng: this.place.geometry.location.lng(),
        }"
        />
    </GmapMap>


    </div>
</template>

<script>
// import VueAxios from 'vue-axios';
// import axios from 'axios';  
// import * as VueGoogleMaps from 'vue2-google-maps';


// 구글 맵스 설정하기.
export default{
    
  data() {
    return {
      markers : [],
      place   : null,
      lat     : 0, //35.8963091,
      lng     : 0,//128.62205110000002,
      zoom    : 5,
      aa      : {lat : 35, lng : 128},
    }
  },
  description: 'Autocomplete Example (#164)',
  methods: {
    setDescription(description) {
      this.description = description;
    },

    setPlace(place) {
      this.place = place;
      this.lat = this.place.geometry.location.lat();
      this.lng = this.place.geometry.location.lng();
      this.aa  = place;
      this.zoom = 15;
      //this.markers.splice(0, 1)  
    },

    usePlace(place) {
      
      let pos = new google.maps.LatLng(this.place.geometry.location.lat(), this.place.geometry.location.lng());
      this.aa = pos;
        
      
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })

      // ADD 눌러서 좌표 디비로 넣을 때
      let uri = '/map';
      let data1 = {
          lat : this.place.geometry.location.lat(),
          lng : this.place.geometry.location.lng(),
      }
      this.axios.post(uri, data1).then((res) => {
          
        
      });
      }
    },

    callde() {

      let uri = '/map';
      let data1 = {
          // lat : this.place.geometry.location.lat(),
          // lng : this.place.geometry.location.lng(),
      }
      this.axios.post(uri, data1).then((res) => {
        // 시작할떄 goolge 객체가 만들어져 있찌 않
        let pos = new google.maps.LatLng(res.data.lat, res.data.lng);
        this.aa = pos;
        
        // this.lat = res.data.lat;
        // this.lng = res.data.lng;
        let test = {
            position : pos
        }
        this.markers.push(test);
          
        
      });
        
    },

  },

 mounted () {
     //this.callde();
 }


    // methods : {
    //     geoCoder ()  {
    //             let map      = new google.maps.Map(document.getElementById('map'), {zoom: 18}); // 구글 맵스 생성
    //             let address  = "東京都 港区 赤坂 1-11-6 赤坂テラスハウス １階";                            // 사장이 입력한 주소 값 더하기
    //             let geocoder = new google.maps.Geocoder();                                      // 지오 코더

    //             // 지오 코드 실행 : 텍스트 주소로 해당 주소 경위도 구하기.
    //             geocoder.geocode( { 'address': address}, function(results, status) {
    //                 if (status == 'OK') {

    //                     map.setCenter(results[0].geometry.location);    // 지도 중앙 값 설정
    //                     let marker = new google.maps.Marker({           // 마크 설정
    //                         map: map,
    //                         position: results[0].geometry.location
    //                     });
    //                 } else {                        

    //                 }
    //             });
    //         },
    // }

}
            



</script>
