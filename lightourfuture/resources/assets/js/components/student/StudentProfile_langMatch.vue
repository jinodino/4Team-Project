<template>
  <v-card>
    <v-layout row wrap>
      <v-flex xs10>
        <p class="Titlefont" style="margin: 10px 15px 0px 13px; text-align: left">ほかの語学能力</p>
      </v-flex>
      <v-flex xs2>
        <button right type="button" @click="openDialog" style="float:right; margin:14px 15px 0px 0px">
          <img src="images/common/edit.png" width="80%" height="80%"> 
        </button>
      </v-flex>
    </v-layout>
    
    <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;"></div>
      
    <v-container>
      <v-layout v-if="student_lang_match.length == 0">
        <v-flex xs12>
          外国語能力を入れてください。
        </v-flex>
      </v-layout>
      <v-layout row justify-center v-else v-for="(lang, key) in student_lang_match" :key = key>
        <v-flex xs6 lg2><b>{{lang.language}} ( {{lang.language_kana}} )</b></v-flex>
        <v-flex xs6 lg2>{{lang.language_level}}</v-flex>
      </v-layout>
    
    </v-container>

    <!-- 다른 언어 모달 -->
    <v-dialog v-model="otherLangModify" width="600px" persistent>
      <v-card>
        
        <v-card-title class="grey lighten-4 py-4 Titlefont">
            ほかの語学能力
            <v-spacer></v-spacer>
            <v-btn large color="error" @click="otherLangModify = false">X</v-btn>
        </v-card-title>
                
        <v-container grid-list-sm class="pa-4">
          <match-junior 
          @delete-lang="deleteLang"
          :langMatchEdit="lang_match_edit_arr" 
          :langList="lang_list" 
          :langLevelList="lang_level_list"
          ></match-junior>
          <v-container>
            <v-layout row wrap>
              <v-flex xs5>
                <v-select
                :items="lang_list_edit_arr"
                item-text = "language"
                item-value="no"
                label ="外国語"
                v-model="new_lang"
                required
                :disabled="lang_list_edit_arr.length == 0"
                >
                </v-select>
              </v-flex>
              <v-flex xs5> 
                <v-select
                :items="lang_level_list"
                item-text = "language_level"
                item-value = "no"
                label ="レベル"
                v-model="new_lang_level"
                required
                :disabled="new_lang == null"
                >
                </v-select>
              </v-flex>   
              <v-flex xs2>
                <v-btn icon color="success" @click="addNewLang" :disabled="new_lang == null || new_lang_level == null">
                  <v-icon>add</v-icon>
                </v-btn>
              </v-flex> 
            </v-layout>
          </v-container>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click="updateLangMatchInfo">セーブ</v-btn>
          <v-btn color="error" @click="otherLangModify = false">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>


<script>
import JR from './StudentProfile_langMatch_jr';
export default {
  props : ['user'],
  components : {
    'match-junior' : JR,
  },

  created : function(){

    this.getLangMatchInfo();
    //항목 뽑기
    //1.외국어 항목,   2.외국어 레벨
    let tableList = ['language_infos', 'language_levels'];
    this.axios.post('/item/listUp', {tableList} )
    .then(rep => {
        let lang_list = rep.data.language_infos;
        for(let i in lang_list)
          this.lang_list.push({'no' : lang_list[i].no, 'language' : lang_list[i].language, 'checked' : false});
        
        let lang_level_list = rep.data.language_levels;
        for(let i in lang_level_list)
          this.lang_level_list.push({'no':lang_level_list[i].no, 'language_level':lang_level_list[i].language_level});
    })
    .catch(ex => {
      console.log(ex);
    });

  },

  data : ()=>({

    //학생 기타 외국어
    student_lang_match : [],
    lang_match_edit_arr : null,

    //외국어 리스트
    lang_list : [],
    lang_level_list :[],
    
    lang_list_edit_arr: [],

    otherLangModify : false,
    langModifyKey : 0,

    //lang 아이템 추가
    new_lang : null,
    new_lang_level : null,

    otherLangModify : false,

  }),

  methods : {


    openDialog(){
      this.lang_match_edit_arr = this.student_lang_match.slice();
      //this.lang_list_check_arr = this.lang_list.slice();
      this.initialize();
      this.otherLangModify = true;
      return ;
    },

    initialize(){  
      this.new_lang = null,
      this.new_lang_level = null,

      //언어 선택 옵션을 초기화 한다.
      this.lang_list_edit_arr = [];

      //보유 언어를 체크하기 위해 초기화 한다.
      for(let i in this.lang_list)
        this.lang_list[i].checked = false;
      
      //지금까지 보유한 언어를 체크한다.
      for(let i in this.lang_match_edit_arr){
        let code = (this.lang_match_edit_arr[i].language_code - 1);
        this.lang_list[code].checked = true;
      }
        
      //보유한 언어라면 언어 선택 옵션에 넣어주지 않는다.
      for(let i in this.lang_list){
        if(!this.lang_list[i].checked)
          this.lang_list_edit_arr.push( this.lang_list[i]);
      }
       
      return ;
    },

    getLangMatchInfo(){
      let login_id = this.user.login_id;

      this.axios.post('/student/getLangMatchInfo', {login_id} )
                .then(rep => {
                  this.student_lang_match = null;
                  this.student_lang_match = rep.data.slice();
                })
                .catch(ex => {
                  console.log(ex);
                });
    },

    addNewLang(){
      let lang = this.lang_list[this.new_lang-1].language;
      alert( "外国語 +[ "+ lang+" ]+ 能力を追加します。");

      this.lang_match_edit_arr.push({ 'language_code' : this.new_lang, 'language_level_code' : this.new_lang_level});
      this.initialize();
      return;
    },

    deleteLang(index, lang_code){
      let lang = this.lang_list[lang_code-1].language;
      alert( "外国語 -[ "+ lang+" ]- 能力を削除します。.");

      this.lang_match_edit_arr.splice(index,1);
      this.initialize();
      return;
    },

    updateLangMatchInfo(){
      let login_id = this.user.login_id;
      let lang_match = this.lang_match_edit_arr;

      this.axios.post('/student/updateLangMatchInfo',{login_id, lang_match})
      .then(rep=>{
        if(rep.data.returnBool){
          alert('修正されました。');
          
        }else{
          alert('変更事項がありません。');    
        }
          this.getLangMatchInfo();
          this.otherLangModify = false;
      })
      .catch(ex=>{
        console.log(ex);
      });

    },
  }


}
</script>
<style>
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>