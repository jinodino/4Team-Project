<template>

  <v-container fluid grid-list-lg grid-list-xs grid-list-md text-xs-center>
    
    <v-card style="background:#E3F2FD">
      <v-container fluid grid-list-sm wrap>
        <v-layout row >
          <v-flex xs12 md12 lg12>
            <p class="display-1">
              学校情報{{status.label}}
            </p>
            <div>ホスティングエージェント  : {{orgAgent.org_name}}({{orgAgent.org_name_kana}})</div>
            <div style="color : red;  float:right">'☆'は必ず作成してください。</div>
          </v-flex>
        </v-layout>

        <v-card style="margin-bottom : 8px">
          <v-layout row justify-center >
            <!-- 프로필 이미지 -->
            <v-flex xs12 md4 lg4 style="margin-top : 10px" justify-center>
              <h4>学校イメージ</h4>
              <profile-image-uploader
                style="margin-left: auto; margin-right: auto; display: block;"
                :width ="profileImageWidth"
                :height="profileImageHeight"
               :value="info.photo_url" 
                @removeProfileImage="removeProfileImage"
                @createProfileImage="createProfileImage" 
              ></profile-image-uploader>
            </v-flex>

            <!-- 기본정보 -->
            <v-flex xs12 md8 lg8 >
              <v-container fluid>
                <v-layout row >
                  <v-flex xs6>
                    <v-text-field 
                    label="学校名☆"
                    v-model="info.org_name"
                    ></v-text-field>
                  </v-flex>

                  <v-flex xs6>
                    <v-text-field 
                    label="学校名（カナ）☆"
                    v-model="info.org_name_kana"
                    ></v-text-field>
                  </v-flex>

                  <v-flex xs4>
                    <v-text-field 
                      label="略称☆(英文3字)"
                      v-model="info.college_alias"
                      :rules="[(v) => v.length < 4 || 'Max 3 characters']"
                    ></v-text-field>
                  </v-flex>

                  <v-flex xs4>
                    <v-select
                    label="分類☆"
                    :items="division.options"
                    v-model="info.college_category"
                    item-text="label"
                    item-value='value'
                    ></v-select> 
                  </v-flex>

                  <v-flex xs4>
                    <v-select
                      label="成績満点"
                      :items="scoretotal.options"
                      v-model="info.credit_total"
                    ></v-select>
                  </v-flex>

                  <v-flex xs12>
                    <v-text-field 
                      label="ホームページurl"
                      v-model="info.homepage_url"
                    ></v-text-field>
                  </v-flex>

                  <v-flex xs4>
                      <v-select
                      :items="continents"
                      label="大陸"
                      item-text="title"
                      item-value="key"
                      v-model="curr_continent_code"
                      ></v-select>
                  </v-flex>

                  <v-flex xs4>
                      <v-select 
                      label="国家☆"
                      :items="countryList[curr_continent_code]"
                      item-text="country_kana"
                      item-value="country_code"
                      v-model="info.country_code"
                      ></v-select>
                  </v-flex>

                  <v-flex xs4>
                    <v-text-field 
                    label="都市"
                    v-model="info.address_city"
                  ></v-text-field>
                  </v-flex>

                  <!-- 자동완성 주소 넣는곳 -->
                  <v-flex xs12>
                    <gmap-autocomplete id="mapstyle" @place_changed="setPlace" v-model="info.address">
                    </gmap-autocomplete>
                    
                    <!-- <v-btn @click="usePlace" color="success">주소 확정</v-btn> -->
                    <!-- <button @click="usePlace">Add</button> -->
                    
                    <!-- 주소 넣는 곳 끝 -->
                    <!-- <v-text-field 
                      label="주소"
                      v-model="info.address"
                    ></v-text-field> -->
                  </v-flex>
                  
                </v-layout>             
              </v-container>
                
            </v-flex>
          </v-layout>

          <!-- 구글 지도 -->
          <v-layout row justify-center style="margin-top : 70px">
            <v-flex xs12>
              <v-card>
                <GmapMap style="width: 100%; height: 500px;" :zoom=6 :center=this.center>
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
              </v-card>
            </v-flex>
          </v-layout>

          <!-- 학과 정보 -->
          <v-layout row>
            <v-flex xs12>
              <v-card flat>
                <v-card-title>
                  <v-btn color="success" dark @click.stop="dialog = !dialog" class="mb-2">学部／学科／クラス追加</v-btn>
                </v-card-title>

                <b-table :fields="header" responsive :items="facultyList" item-key="faculty_id"  striped>
                  <template slot="no" slot-scope="data">
                    <p>
                      {{++data.index}}
                      <v-icon  v-if="data.item.faculty_id == null && status.mode == 'update'" color="red" large>fiber_new</v-icon>
                    </p>
                  </template> 
                  <template slot="major" slot-scope="data">
                    <p><v-chip disabled=ture>{{ majorList[data.item.major_id-1].content }}</v-chip></p>
                  </template>
                  <template slot="department_name" slot-scope="data">
                    <p>{{ data.item.department_name }}</p>
                  </template> 
                  <template slot="major_name" slot-scope="data">
                    <p>{{ data.item.major_name }}</p>
                  </template>
                  <template slot="class_name" slot-scope="data">
                    <p>{{ data.item.class_name }}</p>
                  </template> 
                  <template slot="college_category_sub" slot-scope="data">
                    <p>{{ data.item.college_category_sub }}</p>
                  </template>
                  <template slot="edit" slot-scope="data">
                    <p>
                      <v-btn icon @click="editItem(data.item)">
                        <v-icon color="teal">edit</v-icon>
                      </v-btn>
                      <v-btn icon @click="data.item.faculty_id == null ? deleteItem(data.item) : deleteFaculty(data.item, data.item.faculty_id)">
                        <v-icon color="pink">delete</v-icon>
                      </v-btn>
                    </p>
                  </template>
                </b-table>
                  <template v-if="facultyList.length == 0">
                    <v-alert :value="true" color="warning" icon="warning">
                      There's no facult list to show :(
                    </v-alert>
                  </template>

                <!-- <v-data-table
                  :headers="header1"
                  :items="facultyList"
                  item-key="faculty_id"
                  hide-actions
                >
                  <template slot="items" slot-scope="props">
                    <td class="text-xs-center">
                      {{ ++props.index }}
                      <v-icon  v-if="props.item.faculty_id == null && status.mode == 'update'" color="red" large>fiber_new</v-icon>
                    </td>
                    <td class="text-xs-center"><v-chip disabled=ture>{{ majorList[props.item.major_id-1].content }}</v-chip></td>
                    <td class="text-xs-center">{{ props.item.department_name }}</td>
                    <td class="text-xs-center">{{ props.item.major_name }}</td>
                    <td class="text-xs-center">{{ props.item.class_name }}</td>
                    <td class="text-xs-center">{{ props.item.college_category_sub }}</td>
                    <td>
                      <v-btn icon @click="editItem(props.item)">
                        <v-icon color="teal">edit</v-icon>
                      </v-btn>
                      <v-btn icon @click="props.item.faculty_id == null ? deleteItem(props.item) : deleteFaculty(props.item, props.item.faculty_id)">
                        <v-icon color="pink">delete</v-icon>
                      </v-btn>
                    </td>
                  </template>
                  <template slot="no-data">
                    <v-alert :value="true" color="warning" icon="warning">
                      There's no facult list to show :(
                    </v-alert>
                  </template>
                </v-data-table> -->
              </v-card>
            </v-flex>
          </v-layout>
      
        </v-card>
        
        <v-layout row >
         
       
        <v-flex xs12>
          <v-btn v-if="status.mode == 'create'" large color="primary" block @click="createOrgSchool">登録</v-btn>
          <v-btn v-else large color="primary" block @click="updateOrgSchool">修正</v-btn>
          <v-btn large color="error" block @click="go2Back">キャンセル</v-btn>
        </v-flex>
        </v-layout>

        <v-dialog v-model="dialog" max-width="500px" scrollable>
          <v-card>
            <v-card-title class="grey lighten-4 py-4 title">
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap  justify-center>
                  <v-flex xs10>
                    <v-select 
                    label="専攻分野"
                    :items ="majorList"
                    item-text="content"
                    item-value="id"
                    v-model="editedItem.major_id"
                    auto
                    >
                    </v-select>
                </v-flex>
                  <v-flex xs10>
                    <v-text-field label="学部" placeholder="コンプーター情報系列" v-model="editedItem.department_name"></v-text-field>
                  </v-flex>
                  <v-flex xs10>
                    <v-text-field label="学科／専攻" placeholder="ウェブデータベース専攻" v-model="editedItem.major_name"></v-text-field>
                  </v-flex>
                  <v-flex xs10>
                    <v-text-field label="専攻／クラス" placeholder="3WDJ" v-model="editedItem.class_name"></v-text-field>
                  </v-flex>
                  <v-flex xs10>
                    <v-select 
                    :label="gradetotal.label"
                    :items ="gradetotal.options"
                    v-model="editedItem.college_category_sub"
                    auto
                    >
                    </v-select>

                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="close">Cancel</v-btn>
              <v-btn color="blue darken-1" flat @click.native="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-container>
    </v-card>
  <!-- {{facultyList}} -->
  </v-container>
</template>

<script>
import ContinentList from "../../static/dataProvide/ContinentList.js"
import ProfileImageUpload from "../../shared/ProfileImageUpload.vue" 
export default {
  components : {
    'profile-image-uploader' : ProfileImageUpload
  },

  created : function(){

    //권한 확인
    this.orgAgent = this.$route.params.orgAgent;
    if(this.orgAgent == null){
      this.$router.go(-1);
    }


    //mode 확인
    if(this.$route.params.mode == 'create'){
      this.status.label = "登録";
      this.status.mode = "create";
    }else if(this.$route.params.mode == 'update'){
      this.status.label = "修正";
      this.status.mode = "update";

      let org_college_id = this.$route.query.college_id;
      if(org_college_id == null){
        this.$router.go(-1);
      }else{
        this.getCollegeInfo(org_college_id);
      }
    }

    //전공 카테고리
    let tableList = ['major_infos'];
    this.axios.post('/item/listUp', {tableList})
              .then(rep=>{
                  this.majorList = rep.data.major_infos;
              })
              .catch(ex=>{
                console.log(ex);
              });

    //   //mode 확인   
    //  let org_college_id = this.$route.query.college_id;
    //  if(org_college_id == null){

    //     //작성 orgAgent 정보가 없으면 이전페이지로 돌아가기
    //     let orgAgent = this.$route.params.orgAgent;
    //     if(orgAgent == null)
    //       this.$router.go(-1);

    //     this.orgAgent = orgAgent;

    //     this.status.label = "등록";
    //     this.status.mode = "create";
    //  }else{
    //     this.status.label = "수정";
    //     this.status.mode = "update";

    //     this.getCollegeInfo(org_college_id);
    //  }

      //국가 코드
      this.axios.post('/continent/getCountries')
                .then(rep=>{
                    this.countryList = rep.data;
                    this.continents = ContinentList;
                })
                .catch(ex=>{
                    console.log(ex);
                });

  },

  data : ()=>({

    // 지도에 필요한 변수
    markers : [],
    place   : null,
    center  : {lat : 35, lng : 128},
    

    orgAgent : null,

    status : {
      label : "登録",
      mode : 'create'
    },
    
    profileImage : {
      type : null,
      data : null,
      photo_url : null,
    },

    profileImageWidth : 320,
    profileImageHeight : 300,
    cardheight : 500,
    dialog: false,

    modifiedSave : null,

    profileImage : {
      type: null,
      data : null,
    },

    curr_continent_code : 'AS',


    majorList : [],
    countryList : [],
    continents : [],


    info : {
      org_name : null,
      org_name_kana : null,
      college_category : null,
      credit_total : null,
      college_alias : null,
      homepage_url : null,
      address_city : null,
      address : null,
      country_code : null,
      latitude     : '',
      longitude     : '',
    },

    header1: [
      { text: 'no', value: 'no', },
      { text: '専攻分類', value: 'major', align:'center'},
      { text: '学部', value: 'department_name'},
      { text: '学科／専攻', value: 'major_name', align:'center'},
      { text: '専攻／クラス', value: 'class_name', align:'center'},
      { text: '卒業年数', value: 'college_category_sub',  align:'center' },
      { text: '修正／削除', value: 'edit', align:'center'}
    ],
    header: [
      { label: 'no', key: 'no', },
      { label: '専攻分類', key: 'major'},
      { label: '学部', key: 'department_name'},
      { label: '学科／専攻', key: 'major_name'},
      { label: '専攻／クラス', key: 'class_name'},
      { label: '卒業年数', key: 'college_category_sub'},
      { label: '修正／削除', key: 'edit'}
    ],

    facultyList: [],

    editedIndex: -1,

    editedItem: {
      department_name: '',
      major_name: '',
      class_name: '',
      college_category_sub: '',
    },

    defaultItem: {
      department_name: '',
      major_name: '',
      class_name: '',
      college_category_sub:'',
    },

    division : {
      label : '学校分類',
      options : [
        {value : 'u', label : '一般大学'},
        {value : 'c', label : '専門大学'}
      ]
    },

    scoretotal : {
      label : '成績満点',
      options : [4.0,  4.3, 4.5]
    },

    gradetotal : {
      label : '卒業年数',
      options : [2,3,4,5]
    }

  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? '学科登録' : '学科修正'
    }
  },


  watch: {
    dialog (val) {
      val || this.close()
    }
  },

  methods: {
    // 지도 함수
    // 어디 호출 되는지 잘 모르겠음 -> 추후 완성 후 안쓸 떄 삭제하겠음
    setDescription(description) {
      this.description = description;
    },

    // 주소창에서 자동완성 기능으로 주소를 넣을 때 호출
    setPlace(place) {
      this.place = place;
      let pos = new google.maps.LatLng(this.place.geometry.location.lat(), this.place.geometry.location.lng());
      this.center = pos;
      
      // 만약 마커가 있을 시 전에 선택한 주소 마커 삭제
      this.markers.splice(0, 1)
      
      this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
      })


      this.info.latitude = this.place.geometry.location.lat();
      this.info.longitude = this.place.geometry.location.lng();
        
    },

    // ADD 버튼을 눌렀을 시 호출
    usePlace(place) {
      
      // let pos = new google.maps.LatLng(this.place.geometry.location.lat(), this.place.geometry.location.lng());
      // this.aa = pos;
      // 전에 있떤 마커 삭제 후 원형 마커 추가
      this.markers.splice(0, 1)
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.geometry.location.lat(),
            lng: this.place.geometry.location.lng(),
          }
        })

        this.info.latitude = this.place.geometry.location.lat();
        this.info.longitude = this.place.geometry.location.lng();

        // ADD 눌러서 좌표 디비로 넣을 때 -> date에 정의되어있는 lat, lng변수에가 값을 넣어줘야함
        // -> 추후 학교 생성 버튼 누르면 좌표값도 넘어가고 학교 insert 해줄때 같이 들어감
        
      }
    },

    //모달 -> 테이블
    editItem (item) {
      alert('学科を修正します。');
      this.editedIndex = this.facultyList.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      alert('学科を削除します。');
      const index = this.facultyList.indexOf(item);
      this.facultyList.splice(index, 1)
    },

    deleteFaculty(item, faculty_id){
      let yesNo = confirm('学科を削除しますか？');
      if(!yesNo){
        return;
      }

      this.axios.post('/school/deleteFaculty',{faculty_id})
          .then(rep=>{
            if(rep.data.returnBool){
              this.deleteItem(item);
            }else{
              let code = rep.data.returnCode;
              if(code == 1)
                alert('学科に教授が登録されていて削除できません');
              else if(code == 2)
                alert('学科にグループが登録されていて削除できません');
              else if(code == 3)
                alert('学科に学生が登録されていて削除できません');
              else
                alert('学科を削除できません');
            }
          })
          .catch(ex=>{
            console.log(ex);
          })
    },

    close () {
      this.dialog = false
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      }, 300)
    },

    save () {
      var department = this.editedItem.department_name;
      var major = this.editedItem.major_name;
      var Class = this.editedItem.class_name;
      var college_category = this.editedItem.college_category_sub;
      if(department !== "" && major !== "" && Class !== "" && college_category !== ""){
        if (this.editedIndex > -1) {
          Object.assign(this.facultyList[this.editedIndex], this.editedItem)
        } else {
          this.facultyList.push(this.editedItem)
        }
        this.close()
      }else {
        alert("欄を占めてください。");
      }
    },
    
    //사진 함수
    createProfileImage(type , data){
      console.log('プロフィール写真登録');
      this.profileImage.type = type;
      this.profileImage.data = data;
    },

    removeProfileImage(){
      console.log('プロフィール写真削除');
      this.profileImage.type = null;
      this.profileImage.data = null;
    },
    
    //유효성 검사
    validationCheck(){
      var org_name = this.info.org_name;
      var org_name_kana = this.info.org_name_kana;
      var category = this.info.college_category;
      var alias  = this.info.college_alias;
      var country_code = this.info.country_code;

      if( org_name == null || org_name_kana == null || 
          category == null || alias == null || country_code == null){
        alert("'☆'が付いた欄を占めてください。");
        return false;
      }

      if(alias.length > 3){
        alert("あだ名は最大3字です。");
        return false;
      }
      else if(!this.aliasAlpha(alias)){
        alert("アルファベットを使用してください。");
        return false;
      }
      else if(this.facultyList.length == 0){
        alert("学部や学科を一つ以上登録してください。");
        return false;
      }
        return true;
    },

    aliasAlpha(alias){ 
        var regex=/^[A-Za-z]*$/;
        let returnBool =  regex.test(alias); 
        return returnBool;
    },


    //학교 정보 가져오기
    getCollegeInfo(org_college_id){
      this.axios.post('/school/getCollegeInfo',{org_college_id})
                .then(rep=>{
                  this.info = rep.data.collegeInfo;
                  this.profileImage.photo_url = this.info.photo_url;
                  this.facultyList = rep.data.facultyInfo;
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    //학교 생성
    createOrgSchool(){
      
      if(!this.validationCheck()){
        return;
      }
  
      let org_colleges = this.info;
      let profileImage = this.profileImage;
      let host_org_agent_id = this.orgAgent.org_agent_id;
      let faculties = this.facultyList;
      console.log(org_colleges)
      // this.lat, this.lng 추가 해주세요 
      this.axios.post('/school/create',{org_colleges, profileImage, host_org_agent_id, faculties})
                .then(rep=>{
                  if(rep.data.returnBool){
                    alert('学校が登録されました。');
                    let org_college_id = rep.data.org_college_id;
                    
                    this.$router.push({
                      path:'/agent/collegeInfo', 
                      query:{college_id : org_college_id, agent_id : this.orgAgent.org_agent_id}
                    });
                  }else{
                    let returnCode = rep.data.returnCode;
                    if(returnCode == 1){
                      alert('同じ学科が登録されています。');
                    }
                  }
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    //학과 정보 가져오기
    getfacultyList(org_college_id){
      this.axios.post('/school/getfacultyList', {org_college_id})
              .then(rep=>{
                this.facultyList = rep.data;
              })
              .catch(ex=>{
                  console.log(ex);
              });
    },



    //학교 수정
    updateOrgSchool(){
      
      let org_college_id = this.info.org_college_id;
      let org_colleges = this.info;
      let profileImage = this.profileImage;
      let faculties = this.facultyList;

      this.axios.post('/school/update',{org_college_id, org_colleges, profileImage, faculties})
                .then(rep=>{
                  if(rep.data.returnBool){
                      this.$router.push({
                        path:'/agent/collegeInfo', 
                        query:{college_id : org_college_id, agent_id : this.orgAgent.org_agent_id}
                      });
                  }else{
                    let returnCode = rep.data.returnCode;
                    if(returnCode == 1){
                      alert('同じ学科が登録されています。');
                      this.getMajorInfo();
                      return ;
                    }
                  }
                })
                .catch(ex=>{
                  console.log(ex);
                })
    },

    go2Back(){
      this.$router.go(-1);
    },

  }

  

}
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
<style>
  #img {
    /* 150x180 */
    width: 150px;
    height: 180px;
  }
  
  #mapstyle {
    border-bottom: 1px solid gray; 
    width:100%; 
    height: 30px;
    font-size:16px;
  }

  #mapstyle:hover {
    transition: 0.5s;
    border-bottom: 1px solid black; 
  }
  #mapstyle:visited {
    border-bottom: 2px solid blue; 
  }

</style>
