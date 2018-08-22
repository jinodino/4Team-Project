<template>
<v-container grid-list-xs fluid>
  <v-layout row>
    <input id="orgFile" type="file" class="file-photo" @change="createFile" accept=".pdf"/>
    <v-flex xs12>
      <v-card flat>
        <v-card-title class="Titlefont">
          志願の時提出する書類
          <v-btn @click="openCreateDialog" large color="success">書類アップロード</v-btn>
        </v-card-title>
        <v-card-text>
          <b-table :fields="headers" responsive :items="repository_list" striped>
            <template slot="i" slot-scope="data">
              <p style="margin-top : 5px">
                <img 
                  v-if="data.detailsShowing"
                  src="/imageProvider/openFolderDesign.png" 
                  width="50px"
                  height="40px"
                >
                <img 
                  v-else
                  src="/imageProvider/closeFolderDesign.png" 
                  width="50px"
                >
              </p>
            </template> 
            <template slot="org_name" slot-scope="data">
              <p>
                {{data.item.folderName}}
                <v-chip v-if="repository_info[data.item.employment_id].apply_ox == 'x'"  label disabled outline small>志願前</v-chip>
                <v-chip v-else label disabled outline color="primary" small>志願完了</v-chip>
              </p>
            </template>
            <template slot="x" slot-scope="data">
              <p>
                  <v-btn  small 
                      color="success"
                      @click.stop="data.toggleDetails"
                      @change="open != open"
                  >
                      閲覧
                  </v-btn>
              </p>
            </template>
            <template slot="row-details" slot-scope="data" >
              <v-card-text>
                
                <v-layout row align-center wrap justify-space-between file-hover  @click="openMiriDialog('entrySheet', data.item.documents.employment_id)" color="success">
                  <v-flex xs3 md3 lg3>
                    <v-chip label color="orange" outline disabled>履歴書</v-chip>
                  </v-flex>
                  <v-flex xs6 md6 lg6>
                    {{data.item.documents.entrySheet_name == null?  'NO FILE' : data.item.documents.entrySheet_name}}
                  </v-flex>
                </v-layout>
                <v-layout row align-center wrap justify-space-between file-hover  @click="openMiriDialog('portfolio', data.item.documents.employment_id)" color="success">
                  <v-flex xs3 md3 lg3>
                    <v-chip label color="orange" outline disabled>ポートフォリオ</v-chip>
                  </v-flex>
                  <v-flex xs6 md6 lg6>
                    {{data.item.documents.portfolio_name == null?  'NO FILE' : data.item.documents.portfolio_name}}
                  </v-flex>
                </v-layout>
                <v-layout style="float:right;">
                  <v-btn flat color="teal" @click="openEditDialog(data.item)" :disabled="repository_info[data.item.employment_id].apply_ox == 'o'">EDIT</v-btn>
                  <v-btn flat color="pink" @click="deleteFolder(data.item.employment_id)" :disabled="repository_info[data.item.employment_id].apply_ox == 'o'">DELETE</v-btn>
                </v-layout>
              </v-card-text>
            </template>
          </b-table>

        </v-card-text>
      </v-card>

    </v-flex>
  </v-layout>



  <!-- 미리보기 다이얼로그 -->
  <v-dialog v-model="miriBool" width="1300px">
    <v-card>
      <v-card-title class="grey lighten-4 py-2 title">
        {{curr_file_name}}
        <v-spacer></v-spacer>
        <v-btn color="error" @click="miriBool = false">X</v-btn>
      </v-card-title>
      <v-card-media>
        <embed :src="curr_file_url" type="application/pdf" width="100%" height="600px">
      </v-card-media>
    </v-card>
  </v-dialog>



<!-- 폴더 추가 다이얼로그 -->
  <v-dialog v-model="createModalBool" width="900px">
    <v-card>
      <v-card-title class="grey lighten-4 py-4 title">
        レポジトリ追加
      </v-card-title>
      <v-container grid-list-sm text-xs-center class="pa-4">
        <!-- 폴더선택 -->
        <v-layout row justify-center align-center>
          <v-flex xs11>
            <v-select
            :items="repository_employment_list"
            label="資料をアップロードするフォルダ（採用件）を選択してください"
            item-text="selectText"
            item-value="employment_id"
            prepend-icon="folder"
            :class="{'input-group--focused' : (createdFolder.employment_id != null)}"
            :error="createdFolder.employment_id == null"
            :rules="[(v)=>createdFolder.employment_id != null || '必須選択項目です。']"
            v-model="createdFolder.employment_id"    
            required
            >
            </v-select>
          </v-flex>

          <v-flex xs9>
            <v-text-field
            label="履歴書"
            :error="createdFolder.entrySheet.data == null"
            :rules="[(v)=>createdFolder.entrySheet.data != null || '履歴書は必ず入れてください。']"
            hint="pdf形式のみ入れることができます。"
            class="input-group--focused"
            required
            readonly
            :prepend-icon=" createdFolder.entrySheet.data != null? 'insert_drive_file' : 'no_sim'" 
            :value="createdFolder.entrySheet.name == null ? 'ファイルを選択してください。' : createdFolder.entrySheet.name"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs2>
            <v-btn v-if="createdFolder.entrySheet.data == null" color="primary" @click="check('entrySheet')">
              ファイル選択
            </v-btn>
            <v-btn v-else color="error"  @click="removeFile('entrySheet')">
              取り消す
            </v-btn>
          </v-flex> 
        </v-layout>

        <!-- 포트폴리오 업로드 -->
        <v-layout row  align-center justify-center>
          <v-flex xs9>
            <v-text-field
            label="ポートフォリオ"
            hint="pdf形式のみ入れることができます。"
            :class="{'input-group--focused' : (createdFolder.portfolio.data != null)}"
            readonly
            :prepend-icon=" createdFolder.portfolio.data != null? 'insert_drive_file' : 'no_sim'" 
            :value="createdFolder.portfolio.name == null ? 'ファイルを選択してください。' : createdFolder.portfolio.name"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs2>
            <v-btn v-if="createdFolder.portfolio.data == null" color="primary" @click="check('portfolio')" dark>
              ファイル選択
            </v-btn>
            <v-btn v-else color="error"  @click="removeFile('portfolio')">
              取り消す
            </v-btn>
          </v-flex> 
        </v-layout>
      </v-container>
      <v-card-actions class="grey lighten-4 py-3">
        <v-spacer></v-spacer>  
        <v-btn color="primary" @click="createFolder">セーブ</v-btn>
        <v-btn color="error"   @click="createModalBool = false">取り消す</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>



<!-- 폴더 수정 다이얼로그 -->
   <v-dialog v-model="updateModalBool"  width="900px">
    <v-card>
      <v-card-title class="grey lighten-4 py-4 title">
        レポジトリ修正
      </v-card-title>
      <v-container grid-list-sm class="pa-4">

            <!-- 폴더-->
          <v-layout row justify-center align-center>
            <v-flex xs11>
              <v-chip label color="primary" outline class="subheading" disabled> 
                <v-icon large>folder</v-icon>
                &nbsp;<b>{{editFolderName}}</b>
              </v-chip>
            </v-flex>

            <!-- 이력서 -->
            <v-flex xs9>
              <v-text-field
              label="履歴書"
              :error="createdFolder.entrySheet.name == null"
              :rules="[(v)=>createdFolder.entrySheet.name != null || '履歴書は必ず入れてください。']"
              hint="pdf形式のみ入れることができます。"
              class="input-group--focused"
              required
              readonly
              :prepend-icon=" createdFolder.entrySheet.name != null? 'insert_drive_file' : 'no_sim'" 
              :value="createdFolder.entrySheet.name == null ? 'ファイルを選択してください。' : createdFolder.entrySheet.name"
              >
              </v-text-field>
            </v-flex>
            <v-flex xs2>
              <v-btn v-if="createdFolder.entrySheet.name == null" color="primary" @click="check('entrySheet')">
                ファイル選択
              </v-btn>
              <v-btn v-else color="error"  @click="removeFile('entrySheet')"> 
                取り消す
              </v-btn>
            </v-flex> 
            
            <!-- 포트폴리오 업로드 -->
            <v-flex xs9>
              <v-text-field
              label="ポートフォリ"
              hint="pdf形式のみ入れることができます。"
              :class="{'input-group--focused' : (createdFolder.portfolio.name != null)}"
              readonly
              :prepend-icon=" createdFolder.portfolio.name != null? 'insert_drive_file' : 'no_sim'" 
              :value="createdFolder.portfolio.name == null ? 'ファイルを選択してください。' : createdFolder.portfolio.name"
              >
              </v-text-field>
            </v-flex>
            <v-flex xs2>
              <v-btn v-if="createdFolder.portfolio.name == null" color="primary" @click="check('portfolio')">
                ファイル選択
              </v-btn>
              <v-btn v-else color="error"  @click="removeFile('portfolio')">
                取り消す
              </v-btn>
            </v-flex> 
          </v-layout>
      </v-container>
      <v-card-actions  class="grey lighten-4 py-3">
        <v-spacer></v-spacer>  
        <v-btn color="primary" @click="editFolder">セーブ</v-btn>
        <v-btn color="error"   @click="updateModalBool = false">取り消す</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

</v-container>

</template>

<script>
import EntrySheet from '../student/EntrySheet'

export default {
  created : function(){
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

    this.getRepositoryInfo();
  },

  data : ()=>({
    open: false,
    user : {
      login_id : null,
      classification : null
    },
    repository_list : [],
    repository_info : [],
    repository_employment_list : [],


    headers1 : [
      //{text : '채용 id', value : 'employment_id', width : '80px', sortable : false},
      //{text : '', value : 'photo', sortable : false},
      {text : '', sortable : false},
      {text : '폴더명', value : 'org_name', sortable : false, align:'center', width : '850px'},
      //{text : 'Status', sortable : false, align:'center'},
      //{text : 'Actions', value : 'name', sortable : false, align:'center'},
      {text : '', sortable : false},
    ],
    headers : [
      {label : '', key : "i"},
      {label : 'フォルダ名', key : 'org_name'},
      {label : '', key : "x"},
    ],

    curr_folder_id : null,
    curr_file_url : null,
    curr_file_name : null,
    curr_file_type : null,


    miriBool : false,
    createModalBool : false,
    updateModalBool : false,


    createdFileType : null,
    createdFolder : {
      employment_id : null,

      entrySheet : {
        url : null,
        data : null,
        type : null,
        name : null,
      },
      portfolio : {
        url : null,
        data : null,
        type : null,
        name : null,
      },
    },


    editFolderName : null,
    // editedFileType : null,
    // editedFolder : {
    //   employment_id : null,
    //   entrySheet : {
    //     url : null,
    //     data : null,
    //     type : null,
    //     name : null,
    //   },
    //   portfolio : {
    //     url : null,
    //     data : null,
    //     type : null,
    //     name : null,
    //   }
    // }

  }),

  methods : {

    getRepositoryInfo(){
      let login_id = this.user.login_id;
      this.axios.post('/student/getRepositoryInfo', {login_id})
                .then(rep=>{
                  this.repository_info = null;
                  this.repository_info = rep.data.repository_info;

                  let repository_list = rep.data.repository_list;
                  for(let i in repository_list){
                    let employment_id = repository_list[i].employment_id;
                    repository_list[i].documents = this.repository_info[employment_id];
                    repository_list[i].folderName =  '채용건 id : ' + repository_list[i].employment_id + ' | ' + repository_list[i].org_name + '(' + repository_list[i].org_name_kana +') | ' + repository_list[i].employment_name + ' | ' + repository_list[i].apply_deadline_date + '까지 지원 마감'; 
                  }
                  this.repository_list = null;
                  this.repository_list = repository_list;
                  

                  this.repository_employment_list = null;
                  this.repository_employment_list = rep.data.repository_employment_list;

                  for(let i in this.repository_employment_list){
                    this.repository_employment_list[i].selectText =  '채용건 id : ' + this.repository_employment_list[i].employment_id + ' | ' + this.repository_employment_list[i].org_name + '(' + this.repository_employment_list[i].org_name_kana +') | ' + this.repository_employment_list[i].employment_name + ' | ' + this.repository_employment_list[i].apply_deadline_date + '까지 지원 마감'; 
                  }

                })
                .catch(ex=>{
                  console.log(ex);
                });
    },

    changeCurrFolderId(id){
      if(this.curr_folder_id == id)
        this.curr_folder_id = null;   
      else
        this.curr_folder_id = id;
      
      this.curr_file_url = null;
    },

    openMiriDialog(type, id){

      if(type == 'entrySheet'){
        this.curr_file_url = this.repository_info[id].entrySheet_url;
        this.curr_file_name = this.repository_info[id].entrySheet_name;
      }else{
        this.curr_file_url = this.repository_info[id].portfolio_url;
        this.curr_file_name = this.repository_info[id].portfolio_name;
      }


      if(this.curr_file_url == null){
        alert('ファイルがありません。');
        return ;
      }else {
        this.miriBool = true;
        return;
      }
    },

    changeCurrFile(url, type){
      this.curr_file_url = url;
      this.curr_file_type = type;
    },

    createFolderValidationCheck(mode){
      let createdFolder = this.createdFolder;

      if(mode == 'create'){
        if(createdFolder.employment_id == null){
          alert('フォルダーを選択してください。');
          return false;
        }else if(createdFolder.entrySheet.data == null){
          
        }
      }else if(mode == 'update'){
        if(createdFolder.entrySheet.name == null){
          alert('履歴書を選択してください。');
          return false;
        }
      }
    


      if(mode == 'create')
        alert('新しいフォルダーを作ります。');

      return true;
    },

    createFolder(){
      if(!this.createFolderValidationCheck('create'))
        return;

      let login_id = this.user.login_id;
      let createdFolder = this.createdFolder;

      this.axios.post('/student/createFolder', {login_id, createdFolder})
                .then(rep=>{
                    let returnBool = rep.data.returnBool;
                    let returnCode = rep.data.returnCode;
                    if(!returnBool){

                      if(returnCode == 1)
                        alert('プロフィールに英語の名前を入れてください。');
                      else if(returnCode == 2)
                        alert('もう作られているフォルダーです。');
                        
                    }else{
                      alert('新しいフォルダが作られました。');
                      this.getRepositoryInfo();
                      this.createModalBool = false;
                    }
                })
                .catch(ex=>{
                  console.log(ex);
                });
    },

    editFolder(){
      let yesNo = confirm('フォルダ修正しますか？');
      if(!yesNo){
        alert('取り消ししまいた。');
        this.updateModalBool = false;
        return;
      }

      if(!this.createFolderValidationCheck('update'))
        return;


      let login_id = this.user.login_id;
      let updatedFolder = this.createdFolder;

      this.axios.post('/student/updateFolder', {login_id, updatedFolder})
                    .then(rep=>{
                      if(rep.data.returnBool){
                        alert('修正されました。');
                        this.getRepositoryInfo();
                      }
                      else
                        alert('修正事項がありません。');

                      this.updateModalBool = false;
                    })
                    .catch(ex=>{
                      console.log(ex);
                    });
    },

    deleteFolder(employment_id){
      
      let yesNo = confirm('フォルダを削除しますか？');
      if(!yesNo){
        alert('取り消ししまいた。');
        return;
      }
        
      let login_id = this.user.login_id;
      let entrySheet_url = this.repository_info[employment_id].entrySheet_url;
      let portfolio_url = this.repository_info[employment_id].portfolio_url;
    
      this.axios.post('/student/deleteFolder', {login_id, entrySheet_url, portfolio_url})
                .then(rep=>{
                  let returnBool = rep.data.returnBool;
                    if(returnBool){
                      alert('フォルダが削除されました。');
                      this.getRepositoryInfo();
                    }
                })
                .catch(ex=>{
                  console.log(ex);
                });
    },

    openCreateDialog(){
      if(this.repository_employment_list.length == 0){
        alert('アップロードする採用件がありません。');
        return ; 
      }else{
        this.initCreatedFolder();
        this.createModalBool = true;
      }
    
    },

    //파일 정보 초기화
    initCreatedFolder(){
     
      this.createdFolder.employment_id  = null;

      this.createdFolder.entrySheet.url = null;
      this.createdFolder.entrySheet.data = null;
      this.createdFolder.entrySheet.type = null;
      this.createdFolder.entrySheet.name = null;

      this.createdFolder.portfolio.url = null;
      this.createdFolder.portfolio.data = null;
      this.createdFolder.portfolio.type = null;
      this.createdFolder.portfolio.name = null;
    },

    openEditDialog(obj){
      //this.editFolderName = obj.employment_id + ' | '+ obj.org_name + '(' + obj.org_name_kana + ') | '+ obj.employment_name;
      this.editFolderName = obj.folderName;
      this.initCreatedFolder();

      this.createdFolder.employment_id = obj.employment_id;
      this.createdFolder.entrySheet.url = obj.documents.entrySheet_url;
      this.createdFolder.entrySheet.name = obj.documents.entrySheet_name;

      this.createdFolder.portfolio.url = obj.documents.portfolio_url;
      this.createdFolder.portfolio.name = obj.documents.portfolio_name;
      
      this.updateModalBool = true;
    },

    // initEditDialog(){
    //   this.editedFolder.employment_id = null;

    //   this.editedFolder.entrySheet.url = null;
    //   this.editedFolder.entrySheet.data = null;
    //   this.editedFolder.entrySheet.type = null;
    //   this.editedFolder.entrySheet.name = null;

    //   this.editedFolder.portfolio.url = null;
    //   this.editedFolder.portfolio.data = null;
    //   this.editedFolder.portfolio.type = null;
    //   this.editedFolder.portfolio.name = null;
    // },



    eventOccur(evEle, evType){
            //MouseEvents가 포인트 그냥 Events는 안됨~ ??
            var mouseEvent = document.createEvent('MouseEvents');

            /* API문서 initEvent(type,bubbles,cancelable) */
            mouseEvent.initEvent(evType, true, false);
            var transCheck = evEle.dispatchEvent(mouseEvent);

            //만약 이벤트에 실패했다면
            if (!transCheck) 
                console.log("イベント失敗!");
    },

    check(fileType){
        this.createdFileType = fileType;
        this.eventOccur(document.getElementById('orgFile'),'click');
          //alert(orgFile.value); //이벤트 처리가 끝나지 않은 타이밍이라 값 확인 안됨! 시간차 문제 
    },



    //파일 업로드
    createFile(event) {
        if (this.disabled) {
          return;
        }
        let files = event.target.files;
        if (files.length === 0){
          return;
        }

        let reader = new FileReader();
        reader.onload = (event) => {

          let receivedFileType = files[0].type;
          if(receivedFileType != 'application/pdf'){
            alert('pdf形式のみ入れることができます。');
            return;
          }

          if(this.createdFileType == 'entrySheet'){
            this.createdFolder.entrySheet.data = event.target.result;
            this.createdFolder.entrySheet.type = receivedFileType;
            this.createdFolder.entrySheet.name = files[0].name;
          }else if(this.createdFileType == 'portfolio'){
            this.createdFolder.portfolio.data = event.target.result;
            this.createdFolder.portfolio.type = receivedFileType;
            this.createdFolder.portfolio.name = files[0].name;
          }
      

          this.$emit('createProfileImage', files[0].type, this.preview);
        };

        reader.readAsDataURL(files[0]);
    },

    removeFile(type){
      if(type == 'entrySheet'){
        this.createdFolder.entrySheet.data = null;
        this.createdFolder.entrySheet.name = null;
        this.createdFolder.entrySheet.type = null;
      }else if(type == 'portfolio'){
        this.createdFolder.portfolio.data = null;
        this.createdFolder.portfolio.name = null;
        this.createdFolder.portfolio.type = null;
      }
    }


  },

  // components : {
  //   'entrysheet' : EntrySheet
  // },



}
</script>

<style>
    embed:focus { 
        outline: none;
    }
   .file-photo {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .file-hover:hover{
      background-color: #EEEEEE;
    }
</style>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>