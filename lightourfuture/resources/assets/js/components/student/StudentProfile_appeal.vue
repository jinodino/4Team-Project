<template>
  <v-layout row>
    <v-flex xs12 v-for="(item, key) in student_contents_label" :key="key">

      <v-layout row wrap>
        <v-flex xs10 >
          <p class="resultfont" style="margin: 10px 15px 0px 13px; text-align: left">{{item.title}}</p>
        </v-flex>
        <v-flex xs2 >
          <button right type="button" @click="openDialog(key)" style="float:right; margin:16px 15px 0px 0px">
            <img src="images/common/edit.png" > 
          </button>
        </v-flex>
      </v-layout>
      <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
      </div>
      
      <v-card-text>
        <v-flex xs12 v-if="key == 'interested_field_content'">
          <v-chip color='light gray' v-if="student_interested_match.length == 0" disabled>
            There's no tag yet :)
          </v-chip>
          <v-chip 
            v-else-if="interested_field_list.length != 0"
            color="success"
            text-color="white"
            v-for="pill in student_interested_match" 
            :key="pill" 
            disabled
          >
            {{interested_field_list[pill-1].id}}&nbsp;&nbsp;&nbsp;&nbsp;{{interested_field_list[pill-1].content}}
          </v-chip>
        </v-flex>

        <v-flex class="grey" style="min-height:150px">
          <!-- <v-subheader>max : {{item.max}}字</v-subheader> -->
          <p class="text-left">{{item.value == null ? '内容を入れでください。': item.value}}</p>
        </v-flex>
      </v-card-text>
    </v-flex>

<!-- pr 텍스트 모달 -->
    <v-dialog v-model="dialog" scrollable width="800px" persistent>
      <v-card>
        <v-card-title
          class="grey lighten-4 py-4 Titlefont"
        >
        {{student_contents_label[dialogKey].title}}
        <v-spacer></v-spacer>
        <v-btn large color="error" @click="dialog = false">X</v-btn>

        </v-card-title>
        <v-card-text>
          <v-container grid-list-sm class="pa-4" fluid>
            <v-layout row wrap>
              <v-flex xs12 v-if="dialogKey == 'interested_field_content'">
                <v-expansion-panel>
                  <v-expansion-panel-content class="grey lighten-4 py-4 title">
                    <div slot="header">
                      <v-layout row>
                        <v-flex xs12>
                          興味ある分社（複数選択可）
                        </v-flex>
                        <v-flex xs12>
                          <v-chip color='light gray' v-if="student_interested_match_edit.length == 0" disabled>
                            There's no tag yet :)
                          </v-chip>
                          <v-chip 
                          v-else
                          color="primary"
                          text-color="white"
                          v-for="pill in student_interested_match_edit" 
                          :key="pill" 
                          close
                          @input="removeTag(pill)"
                          >
                          {{interested_field_list[pill-1].id}}&nbsp;&nbsp;&nbsp;&nbsp;{{interested_field_list[pill-1].content}}
                          </v-chip>
                        </v-flex>
                      </v-layout>
                    </div>
                    <v-card>
                      <v-card-text>
                          <v-layout row>
                            <v-flex xs12 sm6 lg3 v-for="menu in interested_field_list" :key="menu.id"> 
                              <v-checkbox 
                              :label="menu.content"
                              :value="menu.id"
                              v-model="student_interested_match_edit"
                              >
                              </v-checkbox>
                            </v-flex>
                          </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-flex>
              <v-flex xs12>
                  <v-text-field
                    name="input-1"
                    :label = "student_contents_label[dialogKey].max.toString() + '字以内'"
                    :rules="[(v) => v.length <= student_contents_label[dialogKey].max || 'Max '+ student_contents_label[dialogKey].max + ' characters']"
                    :counter="student_contents_label[dialogKey].max"
                    textarea
                  v-model="student_contents_edit[dialogKey]"
                  >
                  </v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
    

        <v-card-actions>
          <v-spacer></v-spacer>          
          <v-btn color="success" @click="updateAppealInfo(dialogKey)">セーブ</v-btn>
          <v-btn color="error" @click="dialog = false">取り消す</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-layout>

</template>





<script>

export default {
  props : ['user'],

  created : function(){
    this.getAppealInfo();
    //항목 뽑기
    //1.비즈니스 소분류, 
    let tableList = ['business_small_infos'];
    this.axios.post('/item/listUp', {tableList} )
    .then(rep => {
      this.interested_field_list = null;
      this.interested_field_list= rep.data.business_small_infos;
    })
    .catch(ex => {
      console.log(ex);
    });

  },

  data : ()=>({

    //학생이 흥미있는 분야
    interested_field_list : [],

    valid:false,

    dialog: false,
    dialogKey : 'qualification_content',

    student_contents_edit : {
      qualification_content : null,
      interested_field_content : null,
      motivation_content : null,
      pr_content : null,
    },

    student_contents_label : {
      qualification_content : {title : '持ってある資格', value : null, max : 120 },
      interested_field_content : {title : '興味ある業界・分野', value: null, max : 200 },
      motivation_content : {title : '日本就職動機', value : null, max : 500 },
      pr_content : {title : '自己PR文章', value:null, max : 120 },
    },

    student_interested_match :[],
    student_interested_match_edit : [],

  
  }),

  methods : {
    getAppealInfo(){
      let login_id = this.user.login_id;
      this.axios.post('/student/getAppealInfo', {login_id})
      .then(rep=>{
        let temp  = rep.data.student_contents;
        
        this.student_contents_label.qualification_content.value = temp.qualification_content;
        this.student_contents_label.interested_field_content.value = temp.interested_field_content;;
        this.student_contents_label.motivation_content.value = temp.motivation_content;
        this.student_contents_label.pr_content.value = temp.pr_content;

        temp = rep.data.student_interested_match;
        this.student_interested_match = [];
        for(let i in temp)
          this.student_interested_match.push(temp[i].business_small_id);

        //this.student_interested_match_edit = this.student_interested_match.slice()
        
      })
      .catch(ex=>{
        console.log(ex);
      })
    },

    openDialog(key){
      this.dialogKey = key;

      this.student_contents_edit[key] = this.student_contents_label[key].value;
      if(key == 'interested_field_content')
        this.student_interested_match_edit = this.student_interested_match.slice();

      
      this.dialog = true;
      return;
    },

    updateAppealInfo(key){
      let login_id = this.user.login_id;
      let student_content = this.student_contents_edit[key];
      let student_interested_match = null;

      if(key == 'interested_field_content')
        student_interested_match = this.student_interested_match_edit;

      this.axios.post('/student/updateAppealInfo', {login_id, key, student_content, student_interested_match})
      .then(rep=>{
        //if(rep.data.returnBool){
          alert('修正されました。');
          this.getAppealInfo();
        // }else{
        //   alert('변경된 문장이 없습니다.');
        // }
        this.dialog = false;
      })  
      .catch(ex=>{
        console.log(ex);
      })
    },

    removeTag(pill){
      let index = this.student_interested_match_edit.indexOf(pill);
      this.student_interested_match_edit.splice(index, 1);
      return;
    }

  },


  watch : { 
    profileDialog(val){
      val || this.close();
    }
  }

}
</script>
<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
