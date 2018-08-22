<template>
  <v-layout row>
    
    <!-- 외국어 정보 -->
    <v-flex xs12>
        <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">
          語学能力試験の成績
        </p>
        <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
        </div>
        <v-container fluid>
          <v-layout row>
            <!-- 일본어 -->
            <v-flex xs12 sm12 md6 lg6>
              <v-card>
                <v-layout row wrap>
                  <v-flex xs10>
                    <p class="Titlefont" style="margin: 10px 15px 0px 15px; text-align: left">日本語</p>
                  </v-flex>
                  <v-flex xs2>
                    <button right type="button" @click="openLangDialog('JP')" style="float:right; margin:14px 15px 0px 0px">
                      <img src="images/common/edit.png" width="80%" height="80%"> 
                    </button>
                  </v-flex>
                </v-layout>
                
                <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;"></div>
                <v-container>
                  <v-layout row>
                    <v-flex xs3><b>JLPT</b></v-flex>
                    <v-flex xs9 v-if="student_lang.JLPT != null">{{student_lang.JLPT}}2級 &nbsp;&nbsp;(取得年月 : {{student_lang.JLPT_acquisition_date == null ? '内容をいれて下さい。': student_lang.JLPT_acquisition_date }})</v-flex>
                    <v-flex xs9 v-else>受験しない</v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs3><b>JPT</b></v-flex>
                    <v-flex xs9 v-if="student_lang.JPT != null">{{student_lang.JPT}}点 &nbsp;&nbsp;(取得年月 : {{student_lang.JPT_acquisition_date == null ? '内容をいれて下さい。': student_lang.JPT_acquisition_date }})</v-flex>
                    <v-flex xs9 v-else>受験しない</v-flex>
                  </v-layout> 
                  <v-layout row>
                    <v-flex xs12><b>&nbsp;</b></v-flex>
                  </v-layout>         
                </v-container>
              </v-card>
            </v-flex>
            <!-- 영어 -->
            <v-flex xs12 sm12 md6 lg6>
              <v-card>
                <v-layout row wrap>
                  <v-flex xs10>
                    <p class="Titlefont" style="margin: 10px 15px 0px 15px; text-align: left">英語</p>
                  </v-flex>
                  <v-flex xs2>
                    <button right type="button" @click="openLangDialog('EN')" style="float:right; margin:14px 15px 0px 0px">
                      <img src="images/common/edit.png" width="80%" height="80%"> 
                    </button>
                  </v-flex>
                </v-layout>
                <!-- <p class="Titlefont" style="margin: 10px 15px 0px 15px; text-align: left">
                  英語
                  <button right type="button" @click="openLangDialog('EN')">
                    <img src="images/common/edit.png" width="80%" height="80%"> 
                  </button>
                </p> -->
                <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;"></div>
                <v-container>
                  <v-layout row>
                    <v-flex xs3><b>TOEIC</b></v-flex>
                    <v-flex xs9 v-if="student_lang.TOEIC != null">{{student_lang.TOEIC}}점 &nbsp;&nbsp;(取得年月 : {{student_lang.TOEIC_acquisition_date == null ? '内容をいれて下さい。': student_lang.TOEIC_acquisition_date }})</v-flex>
                    <v-flex xs9 v-else>受験しない</v-flex>
                  </v-layout>

                  <v-layout row>
                    <v-flex xs3><b>TOEFL</b></v-flex>
                    <v-flex xs9 v-if="student_lang.TOEFL != null">{{student_lang.TOEFL}}점 &nbsp;&nbsp;(取得年月 : {{student_lang.TOEFL_acquisition_date == null ? '内容をいれて下さい。': student_lang.TOEFL_acquisition_date }})</v-flex>
                    <v-flex xs9 v-else>受験しない</v-flex>
                  </v-layout>

                  <v-layout row>
                    <v-flex xs3><b>Mock TOEIC</b></v-flex>
                    <v-flex xs9 v-if="student_lang.mock_TOEIC != null">{{student_lang.mock_TOEIC}}점 &nbsp;&nbsp;(取得年月 : {{student_lang.mock_TOEIC_acquisition_date == null ? '内容をいれて下さい。': student_lang.mock_TOEIC_acquisition_date }})</v-flex>
                    <v-flex xs9 v-else>受験しない</v-flex>
                  </v-layout>


                </v-container>
              </v-card>
            </v-flex>

            <v-flex xs12>
              <hoka-lang  :user="user"></hoka-lang>
            </v-flex>
          </v-layout>
        </v-container>
    </v-flex>

<!-- 랭귀지 모달 -->

    <!-- 일본어 -->
    <v-dialog v-model="langModifyJP" persistent width="600px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 Titlefont">
            日本語能力試験
          <v-spacer></v-spacer>
          <v-btn large color="error" @click="langModifyJP = false">X</v-btn>
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>
            <v-flex xs12>
                <v-layout align-center justify-space-between>
                  <v-flex xs3>
                    <v-checkbox v-model="JLPT_untake" hide-details class="shrink mr-2" label="受験しない"></v-checkbox>
                  </v-flex>
                  <v-flex xs2>
                    <v-select 
                      label="JLPT"
                      :disabled="JLPT_untake" 
                      v-model="JLPT_edit"
                      required
                      :items="JLPT_options"
                    >
                    </v-select>
                    
                  </v-flex>
                  <v-flex xs1>
                    級
                  </v-flex>

                  <v-flex xs4>
                    <v-menu
                      ref="JLPT"
                      lazy
                      :close-on-content-click="false"
                      v-model="JLPT_date_menu"
                      transition="scale-transition"
                      offset-y
                      full-width
                      :nudge-right="40"
                      min-width="290px"
                      :return-value.sync="JLPT_date"
                      :disabled="JLPT_edit == null || JLPT_untake" 
                    >
                      <v-text-field
                        slot="activator"
                        label="取得年月"
                        v-model="JLPT_date"
                        prepend-icon="event"
                        :disabled="JLPT_edit == null || JLPT_untake" 
                        readonly
                      ></v-text-field>
                      <v-date-picker 
                        v-model="JLPT_date" 
                        no-title 
                        scrollable 
                        type="month" 
                        min="2000-01-01"
                        :max="new Date().toISOString().substr(0, 10)"
                        @input="$refs.JLPT.save(JLPT_date)"
                      ></v-date-picker>
                    </v-menu>
                  </v-flex>                                                                                                              
                </v-layout>
            </v-flex>

            <v-flex xs12>
                <v-layout align-center justify-space-between>

                  <v-flex xs3>
                    <v-checkbox v-model="JPT_untake" hide-details class="shrink mr-2" label="受験しない"></v-checkbox>
                  </v-flex>

                  <v-flex xs2>
                    <v-text-field 
                      label="JPT" 
                      :disabled="JPT_untake" 
                      v-model="JPT_edit"
                      type = "number"
                      required
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs1>
                    点
                  </v-flex>

                  <v-flex xs4>

                    <v-menu
                      ref="JPT"
                      lazy
                      :close-on-content-click="false"
                      v-model="JPT_date_menu"
                      transition="scale-transition"
                      offset-y
                      full-width
                      :nudge-right="40"
                      min-width="290px"
                      :return-value.sync="JPT_date"
                      :disabled="JPT_edit == null || JPT_untake" 
                    >
                      <v-text-field
                        slot="activator"
                        label="取得年月"
                        v-model="JPT_date"
                        prepend-icon="event"
                        :disabled="JPT_edit == null || JPT_untake" 
                        readonly
                        
                      ></v-text-field>
                      <v-date-picker 
                        v-model="JPT_date" 
                        no-title 
                        scrollable 
                        type="month"  
                        min="2000-01-01"
                        :max="new Date().toISOString().substr(0, 10)"
                        @input="$refs.JPT.save(JPT_date)"
                        >
                      </v-date-picker>
                    </v-menu>
                  </v-flex>

                </v-layout>

            </v-flex>  
            
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="updateLangInfo('JP')">セーブ</v-btn>
          <v-btn color="error"  @click="langModifyJP = false">取り消し</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- 영어 -->
    <v-dialog v-model="langModifyEN" persistent width="600px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 Titlefont">
            英語能力試験
            <v-spacer></v-spacer>
            <v-btn large color="error" @click="langModifyEN = false">X</v-btn>
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>

            <!-- 토익 -->
            <v-flex xs12>
              <v-layout align-center justify-space-between>
                 <v-flex xs3>
                  <v-checkbox v-model="TOEIC_untake" hide-details class="shrink mr-2" label="受験しない"></v-checkbox>
                </v-flex>
                <v-flex xs3>
                  <v-text-field 
                    label="TOEIC" 
                    :disabled="TOEIC_untake" 
                    v-model="TOEIC_edit"
                    type = "number"
                    required
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs1>
                  点
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    ref="TOEIC"
                    lazy
                    :close-on-content-click="false"
                    v-model="TOEIC_date_menu"
                    transition="scale-transition"
                    offset-y
                    full-width
                    :nudge-right="40"
                    min-width="290px"
                    :return-value.sync="TOEIC_date"
                    :disabled="TOEIC_edit == '' || TOEIC_untake" 
                  >
                    <v-text-field
                      slot="activator"
                      label="取得年月"
                      v-model="TOEIC_date"
                      prepend-icon="event"
                      :disabled="TOEIC_edit == '' || TOEIC_untake" 
                      readonly
                    ></v-text-field>
                    <v-date-picker 
                    v-model="TOEIC_date" 
                    no-title 
                    scrollable 
                    type="month"
                    min="2000-01-01"
                    :max="new Date().toISOString().substr(0, 10)"  
                    @input="$refs.TOEIC.save(TOEIC_date)"
                    ></v-date-picker>
                  </v-menu>
                </v-flex>
              </v-layout>
            </v-flex>  

            <!-- 토플 -->
            <v-flex xs12>
              <v-layout align-center justify-space-between>
                <v-flex xs3>
                  <v-checkbox v-model="TOEFL_untake" hide-details class="shrink mr-2" label="受験しない"></v-checkbox>
                </v-flex>
                <v-flex xs3>
                  <v-text-field 
                    label="TOEFL" 
                    :disabled="TOEFL_untake" 
                    v-model="TOEFL_edit"
                    type = "number"
                    required
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs1>
                  点
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    ref="TOEFL"
                    lazy
                    :close-on-content-click="false"
                    v-model="TOEFL_date_menu"
                    transition="scale-transition"
                    offset-y
                    full-width
                    :nudge-right="40"
                    min-width="290px"
                    :return-value.sync="TOEFL_date"
                    :disabled="TOEFL_edit == '' || TOEFL_untake" 
                  >
                    <v-text-field
                      slot="activator"
                      label="取得年月"
                      v-model="TOEFL_date"
                      prepend-icon="event"
                      :disabled="TOEFL_edit == '' || TOEFL_untake" 
                      readonly
                    ></v-text-field>
                    <v-date-picker 
                      v-model="TOEFL_date" 
                      no-title 
                      scrollable 
                      type="month"
                      min="2000-01-01"
                      :max="new Date().toISOString().substr(0, 10)"
                      @input="$refs.TOEFL.save(TOEFL_date)"
                    ></v-date-picker>
                  </v-menu>
                </v-flex>
              </v-layout>

            </v-flex>  

            <!-- 모의 토익 -->
            <v-flex xs12>
              <v-layout align-center justify-space-between>
                <v-flex xs3>
                  <v-checkbox v-model="mock_TOEIC_untake" hide-details class="shrink mr-2" label="受験しない"></v-checkbox>
                </v-flex>
                <v-flex xs3>
                  <v-text-field 
                    label="Mock TOEIC" 
                    :disabled="mock_TOEIC_untake" 
                    v-model="mock_TOEIC_edit"
                    type = "number"
                    required
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs1>
                  点
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    ref="mock_TOEIC"
                    lazy
                    :close-on-content-click="false"
                    v-model="mock_TOEIC_date_menu"
                    transition="scale-transition"
                    offset-y
                    full-width
                    :nudge-right="40"
                    min-width="290px"
                    :return-value.sync="mock_TOEIC_date"
                    :disabled="mock_TOEIC_edit == '' || mock_TOEIC_untake" 
                  >
                    <v-text-field
                      slot="activator"
                      label="取得年月"
                      v-model="mock_TOEIC_date"
                      prepend-icon="event"
                      :disabled="mock_TOEIC_edit == '' || mock_TOEIC_untake" 
                      readonly
                    ></v-text-field>
                    <v-date-picker 
                      v-model="mock_TOEIC_date" 
                      no-title 
                      scrollable 
                      type="month" 
                      min="2000-01-01"
                      :max="new Date().toISOString().substr(0, 10)" 
                      @input="$refs.mock_TOEIC.save(mock_TOEIC_date)"
                    ></v-date-picker>
                  </v-menu>
                </v-flex>
              </v-layout>

            </v-flex>  
            
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="updateLangInfo('EN')">セーブ</v-btn>
          <v-btn color="error"  @click="langModifyEN = false">取り消し</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-layout>
</template>


<script>
import HOKA from './StudentProfile_langMatch';
export default {
  props : ['user'],
  components : {
    'hoka-lang' : HOKA,
  },


  created : function(){

    this.getLangInfo();
  },

  data : ()=>({

    student_lang : {},

    JLPT_untake  : false,
    JPT_untake   : false,
    TOEIC_untake : false,
    TOEFL_untake : false,
    mock_TOEIC_untake : false,

    JLPT_edit   : null,
    JPT_edit    : null,

    TOEIC_edit  : null,
    TOEFL_edit  : null,
    mock_TOEIC_edit  : null,

    JLPT_date   : null,
    JPT_date   : null,

    TOEIC_date  : null,
    TOEFL_date  : null,
    mock_TOEIC_date  : null,

    JLPT_date_menu   : false,
    JPT_date_menu   : false,

    TOEIC_date_menu  : false,
    TOEFL_date_menu  : false,
    mock_TOEIC_date_menu  : false,

    JLPT_options : [1,2,3,4,5],
    

    langModifyJP : false,
    langModifyEN : false,
  
  }),

  methods : {

    getLangInfo(){
      let login_id = this.user.login_id;
  
      this.axios.post('/student/getLangInfo', {login_id})
                .then(rep => {
                    this.student_lang = rep.data.student_lang_info;
                })
                .catch(ex => {
                  console.log(ex);
                });
    },

    openLangDialog(params){

      this.initializeLang(params);
      if(params == 'JP')
        this.langModifyJP = true;
      else if(params == 'EN')
        this.langModifyEN = true;
    },

    initializeLang(params){
      if(params == 'JP'){

        this.JLPT_edit = this.student_lang.JLPT;
        this.JPT_edit = this.student_lang.JPT;

        this.JLPT_date = this.student_lang.JLPT_acquisition_date;
        this.JPT_date = this.student_lang.JPT_acquisition_date;

      }else if(params == 'EN'){

        this.TOEIC_edit = this.student_lang.TOEIC;
        this.TOEFL_edit = this.student_lang.TOEFL;
        this.mock_TOEIC_edit = this.student_lang.mock_TOEIC;

        this.TOEIC_date = this.student_lang.TOEIC_acquisition_date;
        this.TOEFL_date = this.student_lang.TOEFL_acquisition_date;
        this.mock_TOEIC_date = this.student_lang.mock_TOEIC_acquisition_date;

      }
    },

    updateLangInfo(params){
      let login_id = this.user.login_id;
      let langInfo = null;

      if(params == 'JP')
        langInfo = this.getEditJP();
      else if(params == 'EN')
        langInfo = this.getEditEN();

      this.axios.post('/student/updateLangInfo', {login_id, langInfo})
                .then(rep=>{
                    let returnBool = rep.data.returnBool;

                    if(returnBool)
                      alert('修正されました。');
                    else
                      alert('変更事項がありません。');
                    
                    this.getLangInfo();
                    this.langModifyJP = false;
                    this.langModifyEN = false;
                })
                .catch(ex=>{

                })
    },

    getEditJP(){
      let langInfo = {};
      langInfo['JLPT'] = this.JLPT_edit;
      langInfo['JPT']  = this.JPT_edit;
      langInfo['JLPT_acquisition_date'] = new Date(this.JLPT_date); //this.JLPT_date == null ? this.JLPT_date  : (this.JLPT_date + '-00');
      langInfo['JPT_acquisition_date']  = new Date(this.JPT_date); //this.JPT_date == null ? this.JPT_date : (this.JPT_date + '-00');

      if(this.JLPT_untake){
        langInfo['JLPT'] = null;
        langInfo['JLPT_acquisition_date'] = null;
      }

      if(this.JPT_untake){
        langInfo['JPT'] = null;
        langInfo['JPT_acquisition_date'] = null;
      }

      return langInfo;
    },

    getEditEN(){
      let langInfo = {};

      langInfo['TOEIC'] = this.TOEIC_edit;
      langInfo['TOEFL']  = this.TOEFL_edit;
      langInfo['mock_TOEIC'] = this.mock_TOEIC_edit;

      langInfo['TOEIC_acquisition_date'] = new Date(this.TOEIC_date); 
      langInfo['TOEFL_acquisition_date'] = new Date(this.TOEFL_date);
      langInfo['mock_TOEIC_acquisition_date'] = new Date(this.mock_TOEIC_date);


      if(this.TOEIC_untake){
        langInfo['TOEIC'] = null;
        langInfo['TOEIC_acquisition_date'] = null;
      }

      if(this.TOEFL_untake){
        langInfo['TOEFL'] = null;
        langInfo['TOEFL_acquisition_date'] = null;
      }

      if(this.mock_TOEIC_untake){
        langInfo['mock_TOEIC'] = null;
        langInfo['mock_TOEIC_acquisition_date'] = null;
      }



      return langInfo;
    },

  },

}
</script>
<style>
  .section{
    background-color:lavender;
  }
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>