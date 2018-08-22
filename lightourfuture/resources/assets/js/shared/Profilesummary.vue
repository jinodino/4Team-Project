<template>
<!-- made by hyojin -->
  <div class="container-fluid">

   <!-- 면접 status 정보 -->
    <div class="row text-center section">
      <div class="col-xs-6  col-md-6"><h1>{{profile.status.state_content}}</h1></div>
      <div class="col-xs-6  col-md-6 "><h1>{{profile.status.student_code}}</h1></div>
    </div>

    <div class="row section">
      <div class="col-xs-4 col-md-4 text-center" v-for="(value, key) in notices" :key="key">
        <v-avatar style="background-color:lavenderblush;">{{value.value}}</v-avatar>
        <div>{{value.label}}</div>
      </div>
    </div>

<!-- 기본 정보 -->
    <div class="row section">
      
      <div class="col-xs-4 col-md-4 text-center">
        <div class="row">
          <div class="col-xs-4 col-md-4">
              <img src="http://placehold.it/300x400" alt="" class="img-rounded img-responsive" />  
          </div>
        </div>

        <div class="row">
          <div class="col-xs-4 col-md-4">
            <input name="myFile" type="file" value="사진 변경">
          </div>
        </div>
      </div>


      <div class="col-xs-4 col-md-8">
          <div class="row" v-for="value in profile.gibon" :key = "value">
            <div class="col-xs-4 col-md-4"><b>{{value.label}}</b></div>
            <div class="col-xs-4 col-md-4" v-if="value.type == 'url'"><a :href="value.value">{{value.value}}</a></div> 
            <div class="col-xs-4 col-md-4" v-else-if="value.value">{{value.value}}</div>
            
            <div class="col-xs-4 col-md-4" v-if="value.date">{{value.date}}</div> 
          </div>

          <div class="row">
            <div class="col-xs-4 col-md-8">
              <button type="button" class="btn btn-primary pull-right" @click.stop="profileModify = !profileModify;">수정</button>
            </div>
          </div>

      </div>
    </div>

<!-- 소속 정보 -->
    <div class="row section">
      <div class="col-xs-4 col-md-8">
          <div class="row" v-for="(value, key) in profile.sosock" :key = "value">
            <div class="col-xs-2 col-md-4">{{key}}</div>
            <div class="col-xs-2 col-md-4">{{value}}</div> 
          </div>
      </div>
    </div>

<!-- 외국어 정보 -->
    <div class="row section" v-for="(value, key, index) in lang_skill" :key="index">
      <div class="col-xs-4 col-md-12">
         <div class="row">
            <div class="col-xs-4 col-md-6" >
              <h3>{{value.title}}</h3>
            </div>
         </div>

        <div class="row">
          <div class="col-xs-4 col-md-6" v-for="(value, key, index) in value.item" :key="index">
            <div class="row">
              <div class="col-xs-4 col-md-6">
                <h5>{{value.label}}</h5>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-4 col-md-6">
                {{value.value == null || value.enable ? '미응시' : value.value+value.unit}}
              </div>
            </div> 
          </div>
        </div>
        <div class="row">
          <div class="col-xs-4 col-md-12">
            <button type="button" class="btn btn-danger pull-right">삭제</button>
            <button type="button" class="btn btn-primary pull-right" @click.stop="langModify = !langModify; langModifyKey = key">수정</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row section">
      <h3>기타 외국어</h3>
      <button class="btn btn-primary" @click.stop="otherLangModify = !otherLangModify;"> 추가 </button>
    </div>

<!-- 스킬 시트 정보 -->
    <div class="row section">
      <div class="col-xs-4 col-md-12">
        <div class="row">
          <div class="col-xs-4 col-md-12">
              <h3>프로그래밍 스킬 시트</h3>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-4 col-md-3" v-for="item_1 in skillList" :key="item_1.key">
              <h4>{{item_1.text}}</h4>
              <div class="row" v-for="item_2 in item_1.children" :key="item_2.key">
                <ul>
                  <li>
                    <div class="col-xs-3 col-md-6">{{item_2.key}}</div>
                    <div class="col-xs-1 col-md-6">{{item_2.value}}</div>
                  </li>
                </ul>
              </div>
              
          </div>
        </div>
        <div class="row">
          <div class="col-xs-4 col-md-12">
            <button class="btn btn-primary pull-right" @click.stop="skillListModify = !skillListModify;"> 추가 </button>              
          </div>
        </div>
      </div>
    </div>


 
 
 <!-- 어필 정보 -->
    <div class="row section" v-for="(obj, key) in PRText" :key="key">
        <div class="form-group">
            <label for="formControl"><h3>{{obj.title}}</h3></label>
            <p id="formControl">{{obj.content}}</p>
            <div class="text-left">
              <button type="button" class="btn btn-danger pull-right">삭제</button>
              <button type="button" class="btn btn-primary pull-right" @click.stop="dialog = !dialog; dialogKey = key">수정</button>
            </div>
        </div>
    </div>
  
  
<!-- pr 텍스트 모달 -->
    <v-dialog v-model="dialog" width="800px">
      <v-card>
        <v-card-title
          class="grey lighten-4 py-4 title"
        >
        {{PRText[dialogKey].title}}
        </v-card-title>

        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>
            <v-flex xs12>
                <v-text-field
                  name="input-1"
                  :label = "PRText[dialogKey].max.toString() + '자 이내'"
                  :rules="[(v) => v.length <= PRText[dialogKey].max || 'Max '+ PRText[dialogKey].max + ' characters']"
                  :counter="PRText[dialogKey].max"
                  textarea
                  v-model="PRText[dialogKey].content"
                >
                </v-text-field>
            </v-flex>
          </v-layout>
        </v-container>

        <v-card-actions>
          <v-spacer></v-spacer>          
          <button type="button" class="btn btn-primary" @click="dialog = false">저장</button>
          <button type="button" class="btn btn-danger"  @click="dialog = false">취소</button>
        </v-card-actions>
      </v-card>
    </v-dialog>





<!-- 기본 정보 모달 -->
    <v-dialog v-model="profileModify" width="600px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 title">
            기본 정보
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap >
            <v-flex xs12 v-for="item in profile.gibon" :key="item.key"> 
                  <v-select
                    :label="item.label"
                    v-model="item.value"
                    :value="item.value"
                    required
                    v-if="item.select"
                    :items="item.select">
                  </v-select>

                  <v-select
                    :label="item.label"
                    v-model="item.value"
                    :value="item.value"
                    required
                    v-else-if="item.children"
                    :items="item.select"
                    item-value="label">
                  </v-select>

                  <v-text-field
                    :label="item.label"
                    v-model="item.value"
                    :value="item.value"
                    required
                    v-else-if="item.value">
                  </v-text-field>
            </v-flex>
            <v-flex xs12 >
             <v-menu
                    ref="menu"
                    lazy
                    :close-on-content-click="false"
                    v-model="profile.gibon[6].menu"
                    transition="scale-transition"
                    offset-y
                    full-width
                    :nudge-right="40"
                    min-width="290px"
                    :return-value.sync="profile.gibon[6].date"
                  >
                    <v-text-field
                      slot="activator"
                      :label="profile.gibon[6].label"
                      v-model="profile.gibon[6].date"
                      prepend-icon="event"
                      readonly
                    ></v-text-field>
                    <v-date-picker v-model="profile.gibon[6].date" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="profile.gibon[6].menu = false">Cancel</v-btn>
                      <v-btn flat color="primary" @click="$refs.menu.save(profile.gibon[6].date)">OK</v-btn>
                    </v-date-picker>
                  </v-menu>
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
            <v-spacer></v-spacer>  
            <button type="button" class="btn btn-primary" @click="profileModify = false">저장</button>
            <button type="button" class="btn btn-danger"  @click="profileModify = false">취소</button>
        </v-card-actions>
      </v-card>
    </v-dialog>

<!-- 랭귀지 모달 -->
    <v-dialog v-model="langModify" width="600px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 title">
            {{lang_skill[langModifyKey].title}}
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>
            <v-flex xs12 v-for="(value, key, index) in lang_skill[langModifyKey].item" :key="index">
                <v-layout align-center>
                  <v-flex xs4>
                    <v-checkbox v-model="value.enable" hide-details class="shrink mr-2" label="미응시"></v-checkbox>
                  </v-flex>
                  <v-flex xs4>
                    <v-select 
                    v-if="value.select"
                    :label="value.label"
                    :disabled="value.enable" 
                    v-model="value.value"
                    :value="value.value"
                    required
                    :items="value.select"
                    >
                    </v-select>
                    <v-text-field 
                    v-else
                    :label="value.label" 
                    :disabled="value.enable" 
                    v-model="value.value"
                    :value="value.value"
                    required
                    >
                    </v-text-field>
                  </v-flex>
                    <v-flex xs4>
                      {{value.unit}}
                    </v-flex>
                </v-layout>
            </v-flex>
              
            
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <button type="button" class="btn btn-primary" @click="langModify = false">저장</button>
          <button type="button" class="btn btn-danger"  @click="langModify = false">취소</button>
        </v-card-actions>
      </v-card>
    </v-dialog>

<!-- 다른 언어 모달 -->
    <v-dialog v-model="otherLangModify" width="600px">
      <v-card>
        <v-card-title class="grey lighten-4 py-4 title">
            외국어 능력
        </v-card-title>
        <v-container grid-list-sm class="pa-4">
          <v-layout row wrap>

            <v-flex xs6>
              <v-select
              :items="other_lang_items"
              item-text = "label"
              label ="외국어"
              ref="lang"
              >
              </v-select>
            </v-flex>

            <v-flex xs6>  
              <v-select
              :items="other_lang_level_items"
              item-text = "label"
              item-value = "value"
              label ="레벨"
              ref="level"
              slot="item"
            >
            </v-select>
            </v-flex>    
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <button type="button" class="btn btn-primary" @click="otherLangModify = false; other_lang.push($data);">저장</button>
          <button type="button" class="btn btn-danger"  @click="otherLangModify = false">취소</button>
        </v-card-actions>
      </v-card>
    </v-dialog>
<!-- 스킬시트 모달 -->
<v-dialog v-model="skillListModify" width="800px">
  <v-card>

    <v-card-title  class="grey lighten-4 py-4 title">
        프로그래밍 스킬 시트
    </v-card-title>

    <v-container  grid-list-sm class="pa-4">
      <v-layout row>
        <v-flex xs3 v-for="item_1 in skillList" :key="item_1.key">
         <div>{{item_1.text}}</div>
         <v-layout row v-for="item_2 in item_1.children" :key="item_2.key">
           <v-flex xs6>{{item_2.key}}</v-flex>
           <v-flex xs6>
             <v-select
             :items="skillLevel"
             item-text="text"
             item-value="value"
             v-model="item_2.value"
             >
             </v-select>
           </v-flex>
         </v-layout>
        </v-flex>
      </v-layout>
    </v-container>

    <v-card-actions>

    </v-card-actions>
  </v-card>
</v-dialog>
</div>
 






</template>





<script>

import skill from '../skill';

export default {
  data : ()=>({

    skillList : skill.skillList,
    skillLevel : skill.skillLevel,

    skillListModify : false,
    
    valid:false,

    dialog: false,
    dialogKey : 0,

    profileModify: false,

    langModify: false,
    langModifyKey : 0,

    otherLangModify : false,
    otherLangKey : null,

    profile : {

        'status' : {
          "state_content":"구직중",
          'student_code': 'KRYJC180001',
        },
        
        'gibon' : [
            {
              label : '한글 이름',
              key : 'name_kr',
              value : '이영진'
            },
            {
              label : '영문 이름',
              key : 'name_en',
              value : 'Youngjin Lee',
            },
            {
              label : '요미가나 이름',
              key : 'name_kana',
              value : 'イ・ヨンジン',
            },
            {
              label : '한자 이름',
              key : 'name_kanji',
              value : '李永進',
            },
            {
              label : '이메일',
              key : 'email',
              value : 'youngjin@gmail.com',
            },
            {
              label : '성별',
              key : 'gender',
              select : ['여','남'],
              value : '여',
            },
            {
              label : '생년월일',
              datePicker : true,
              key : 'birth',
              date : '1995-11-05',
              menu: false,
            },
            {
              label : '만 나이',
              key : 'age',
              value : 22
            },
            {
              label : '국적',
              key : 'country_code',
              value : 'KR'
            },

            {
              label : '소속 조',
              select : [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
              key : 'group_num',
              value : 4
            },
            {
              label : '조에서 맡은 파트',
              key : 'group_part',
              value : 'asdfasdfadsfasdfasdfadsfasdfasdf'
            },
            {
              label : '깃허브 주소',
              key : 'github_url',
              value : 'http://github.com',
              type : 'url'
            },
            
        ],

        'sosock' :{
            'nation' : 'KOR',
            'college': '영진전문대학',
            'department' : '컴퓨터 정보계열',
            'major': '웹 데이터베이스',
            'gradutatedata' :"2019.02",
            'grade' : 3,
            'professor' : '정영철',
        }
    },

    lang_skill : [
        {
            'title' : '일본어 능력',
            'item' : [
              {
                label : 'JLPT',
                value : 1,
                unit : '급',
                max : 5,
                select : [1,2,3,4,5],
                enable : false
              },
              {
                label : 'JPT',
                value : 960,
                unit : '점',
                max : 990,
                enable : false
              },
            ]
          
        },

        {
            'title' : '영어 능력',
            'item' : [
              {
                label : 'TOEIC',
                value : null,
                unit : '점',
                max : 990,
                enable : false
              },
              {
                label : 'TOEFL',
                value : null,
                unit : '점',
                max : 120,
                enable : false
              },
            ]
          
        },
    ],

    other_lang : [],

    other_lang_items : [
      {
        label : '스페인어',
        key : 'esp',
        picked : false
      },
      {
        label : '중국어',
        key : 'cha',
        picked : false
      },
      {
        label : '프랑스어',
        key : 'fra',
        picked : false
      }
    ],

    other_lang_level_items : [
      {
        label : '모국어 수준',
        value : 1
      },
      {
        label : '비지니스 회화',
        value : 2
      },
      {
        label : '기초 회화',
        value : 3
      },
    ],


    interested_field_items : [
      {
        key : 'OS',
        label : '운영체제',
        children : [
          {
            key:'Linux',
            value :0
          },
          {
            key:'Windows',
            value :0
          },
          {
            key:'Android',
            value :0
          }
        ]
      },
      {
        label : "컴퓨터 언어",
        key : 'conputer_lang',
        children : [
          {
            key:'c',
            value :0
          },
          {
            key:'c++',
            value :0
          },
          {
            key:'html',
            value :0
          },
          {
            key:'java',
            value :0
          },
          {
            key:'javascript',
            value :0
          },
          {
            key:'php',
            value :0
          },
          {
            key:'css',
            value :0
          }
        ]
      },
      {
        label : '데이터 베이스',
        key : 'db',
        children : [
          {
            key:'oracle',
            value :0
          },
          {
            key:'mysql',
            value :0
          }
        ]
      },
      {
        label : '서버',
        key : 'server',
        children : [
          {
            key:'apachi',
            value :0
          },
          {
            key:'tomcat',
            value :0
          },
          {
            key:'nodejs',
            value :0
          }
        ]
      },
    ],
    interested_field : [],

    
      


    credit :{
      'credit_all' : 4.5,
      'credit' : 4.33
    },

    notices : [
      {'label' :'지원' ,
       'key': "apply",
       'value' :6
       },
       {'label' :'면접중' ,
       'key': "inteview",
       'value' :5
       },
        {'label' :'내정수' ,
       'key': "acceptance",
       'value' :0
       }
    ],

    PRText:[
      {
        key: 'qualify_cotent',
        title : '기타 자격',
        content:'이상의 쓸쓸한 없는 행복스럽고 것이다. 그와 이상은 밝은 있다. 미인을 사라지지 가치를 보배를 약동하다. 청춘 싹이 청춘을 이것을 안고, 보내는 것은 영원히 때문이다. 사라지지 무엇을 없는 이상의 얼마나 피고 보라.',
        max : 120
      },
      {
        key: 'intrested_field_content',
        title : '관심 있는 분야',
        content :  "스며들어 할지니, 무엇이 오직 것이다. 영원히 이상은 인간은 있는 이상, 그들은 돋고, 이상의 전인 끓는다. 모래뿐일 사라지지 영원히 소리다.이것은 힘있다. 인류의 과실이 이것이야말로 우리 노래하며 오직 방황하였으며, 피에 커다란 봄바람이다. 듣기만 것이다.보라, 열매를 칼이다. 황금시대를 역사를 귀는 반짝이는 있으랴? 얼마나 그들은 봄바람을 그리하였는가?",
        children:[],
        max : 200
      },
      {
        key:'motivation_content',
        title : '일본 취업을 결심하게 된 동기',
        content :'황금시대다. 웅대한 원대하고 이성은 우리는 지혜는 얼마나 이상, 능히 교향악이다. 이상의 이상의 없으면, 대한 이 교향악이다. 가슴에 천고에 피는 황금시대다. 오아이스도 피부가 무한한 자신과 사막이다. 현저하게 풍부하게 사는가 공자는 속에 인도하겠다는 귀는 위하여서, 것이다. 천고에 대고, 끓는 풀밭에 그들에게 황금시대를 피어나기 노년에게서 영원히 것이다.황금시대다. 웅대한 원대하고 이성은 우리는 지혜는 얼마나 이상, 능히 교향악이다. 이상의 이상의 없으면, 대한 이 교향악이다. 가슴에 천고에 피는 황금시대다. 오아이스도 피부가 무한한 자신과 사막이다. 현저하게 풍부하게 사는가 공자는 속에 인도하겠다는 귀는 위하여서, 것이다. 천고에 대고, 끓는 풀밭에 그들에게 황금시대를 피어나기 노년에게서 영원히 것이다.',
        max : 500
      },
      {
        key:'pr_content',
        title:'자기 PR 문장',
        content:'이상의 쓸쓸한 없는 행복스럽고 것이다. 그와 이상은 밝은 있다. 미인을 사라지지 가치를 보배를 약동하다. 청춘 싹이 청춘을 이것을 안고, 보내는 것은 영원히 때문이다. 사라지지 무엇을 없는 이상의 얼마나 피고 보라.',
        max :120
      },
    
    ],
   
  }),
  
}
</script>
<style>
  .section{
    margin-bottom: 1rem;
    background-color:lavender;
  }
</style>
