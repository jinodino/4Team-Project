<template>
    <v-container grid-list-lg fluid class=" lighten-4">
        <v-layout row>
            <!-- status  -->
            <v-flex xs12>
                <v-card>
                    
                    <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">STATUS</p>
                    <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
                    </div>
                    
                    <v-card-text>
                        <v-container grid-list-xs fluid>
                                <v-alert v-if="finalCompanyInfo == null" :value="true" color="warning" icon="warning">入社を確定した会社がまだありません。</v-alert>
                                <v-alert v-else :value="true" color="primary" icon="check_circle" class="display-1">
                                    おめでとうございます!!
                                    {{finalCompanyInfo.org_name}} ({{finalCompanyInfo.org_name_kana}}) 就社確定!!!
                                </v-alert>

                            <v-layout row>
                                <v-flex  class="text-xs-center">
                                    <div>志願数</div>
                                    <v-avatar class="resultfont">{{applyStatus.list.length}}</v-avatar>
                                    
                                </v-flex> 
                                <div class="divheight"></div>
                                
                                <v-flex  class="text-xs-center">
                                    <div>面接結果</div>
                                    <v-avatar class="resultfont">{{interviewStatus.subListKeys.length}}</v-avatar>
                                </v-flex>
                                <div class="divheight"></div>

                                
                                <v-flex  class="text-xs-center">
                                    <div>内定結果</div>
                                    <v-avatar class="resultfont">{{norminateStatus.list.length}}</v-avatar>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                </v-card>
            </v-flex>

            <!-- 지원 Status -->
            <v-flex xs12>
                <v-card>
                    <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">{{applyStatus.tableTitle}} : {{applyStatus.list.length}}件</p>
                    <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
                    </div>
                    <!-- <v-card-title  class="grey lighten-4 py-4 title" style="color:#008080; font: bold;font-size : 25px">
                        {{applyStatus.tableTitle}} : {{applyStatus.list.length}}件
                    </v-card-title> -->
                    <v-card-text>
                        <b-table :fields="applyStatus.headers" responsive :items="applyStatus.list" striped>
                            <template slot="org_name" slot-scope="data">
                                <p>
                                    <v-chip v-if="data.item.acceptance_fixed_ox == 'o'" color="error" label outline disabled>辞退不可能</v-chip>
                                    <v-chip v-else-if="data.item.acceptance_fixed_ox == 'x'" color="success" label outline disabled>辞退可能</v-chip>
                                    <v-chip v-else label outline disabled>決めてない</v-chip>
                                    {{data.item.org_name}}({{data.item.org_name_kana}}) | {{data.item.employment_name}}
                                </p>
                            </template> 
                            <template slot="apply_permission_ox" slot-scope="data">
                                <p>
                                    <v-chip v-if="data.item.apply_permission_ox == 'o'" color="primary" label outline disabled>承諾</v-chip>
                                    <v-chip v-else-if="data.item.apply_permission_ox == 'x'" color="error" label outline disabled>断る</v-chip>
                                    <v-chip v-else label outline disabled>決めてない</v-chip>
                                </p>
                            </template>
                            <template slot="name" slot-scope="data">
                                <p>
                                    <v-btn v-if="data.item.apply_permission_ox == 'o'" color="error" normal disabled>取り消し不可能</v-btn>
                                    <v-btn v-else color="error" normal @click="deleteApply(data.item.apply_id)">取り消す</v-btn>
                                </p>
                            </template>
                        </b-table>
                    </v-card-text>

                <!-- <v-data-table
                    :headers="applyStatus.headers1"
                    :items="applyStatus.list"
                    hide-actions
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props">
                            <tr>
                                <td class="text-xs-center">{{props.item.employment_id}}</td>
                                <td>
                                    <v-chip v-if="props.item.acceptance_fixed_ox == 'o'" color="error" label outline disabled>사퇴불가</v-chip>
                                    <v-chip v-else-if="props.item.acceptance_fixed_ox == 'x'" color="success" label outline disabled>사퇴가능</v-chip>
                                    <v-chip v-else label outline disabled>미확정</v-chip>
                                    {{props.item.org_name}}({{props.item.org_name_kana}}) | {{props.item.employment_name}}
                                </td>

                                <td class="text-xs-center">
                                    <v-chip v-if="props.item.apply_permission_ox == 'o'" color="primary" label outline disabled>승낙</v-chip>
                                    <v-chip v-else-if="props.item.apply_permission_ox == 'x'" color="error" label outline disabled>거절</v-chip>
                                    <v-chip v-else label outline disabled>미승낙</v-chip>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn v-if="props.item.apply_permission_ox == 'o'" color="error" normal disabled>취소불가</v-btn>
                                    <v-btn v-else color="error" normal @click="deleteApply(props.item.apply_id)">지원취소</v-btn>
                                </td>
                            </tr>
                        </template>
              
                        <template slot="no-data">
                            <v-alert :value="true" color="error" icon="warning">
                                Nothing to display :(
                            </v-alert>
                        </template>
                    </v-data-table> -->
                </v-card>
            </v-flex>

            <!-- 면접 결과 Status -->
            <v-flex xs12>
                <v-card>
                    
                    <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">面接結果 : {{interviewStatus.subListKeys.length}}件</p>
                    <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
                    </div>
                    
                    <v-card-text> 
                        <b-table :fields="interviewStatus.headers" responsive :items="interviewStatus.list" striped>
                            <template slot="org_name" slot-scope="data">
                                <p>
                                    {{data.item.org_name}}({{data.item.org_name_kana}}) | {{data.item.employment_name}}
                                </p>
                            </template> 
                            <template slot="name" slot-scope="data">
                                <p>
                                    {{interviewStatus.subList[data.item.employment_id].length}} / {{data.item.whole_interview_count}}
                                </p>
                            </template>
                            <template slot="kekka" slot-scope="data">
                                <p>
                                    <v-chip v-if="interviewStatus.subList[data.item.employment_id][interviewStatus.subList[data.item.employment_id].length -1].interview_result == 'o'" color="primary" label outline disabled>
                                        <v-icon left color="primary">card_giftcard</v-icon>合格
                                    </v-chip>
                                    <v-chip v-else-if="interviewStatus.subList[data.item.employment_id][interviewStatus.subList[data.item.employment_id].length -1].interview_result == 'x'" color="error" label outline disabled>
                                        <v-icon left color="error">block</v-icon>不合格
                                    </v-chip>
                                    <v-chip v-else label outline disabled>
                                        <v-icon left>update</v-icon>検討中
                                    </v-chip>
                                </p>
                            </template>
                            <template slot="x" slot-scope="data">
                                <p>
                                    <v-btn  small 
                                        color="success"
                                        @click.stop="data.toggleDetails"
                                    >
                                        現状況閲覧
                                    </v-btn>
                                    <!-- <v-icon v-if="data.expanded" large>expand_less</v-icon>
                                    <v-icon v-else large>expand_more</v-icon> -->
                                </p>
                            </template>
                            <template slot="row-details" slot-scope="data">
                                <b-table :fields="interviewStatus.subHeaders" responsive :items="interviewStatus.subList[data.item.employment_id]" item-key="faculty_id"  striped>
                                    <template slot="num" slot-scope="data">
                                        <p>{{data.item.interview_count}}차</p>
                                    </template> 
                                    <template slot="item" slot-scope="data">
                                        <p><v-chip disabled>{{data.item.content}}</v-chip>{{data.item.schedule_title}} - {{data.item.interview_content}}</p>
                                    </template>
                                    <template slot="place" slot-scope="data">
                                        <p>{{data.item.interview_place}}</p>
                                    </template>
                                    <template slot="day" slot-scope="data">
                                        <p>{{data.item.interview_date}}</p>
                                    </template>
                                    <template slot="result" slot-scope="data">
                                        <p>
                                            <v-chip v-if="data.item.interview_result == 'o'" color="primary" label outline disabled>
                                                合格
                                            </v-chip>
                                            <v-chip v-else-if="data.item.interview_result == 'x'" color="error" label outline disabled>
                                                不合格
                                            </v-chip>
                                            <v-chip v-else disabled> 
                                                検討中
                                            </v-chip>
                                        </p>
                                    </template>
                                </b-table>
                            </template>
                        </b-table>
                    </v-card-text>

                    <!-- <v-data-table
                    :headers="interviewStatus.headers1"
                    :items="interviewStatus.list"
                    item-key="employment_id"
                    hide-actions
                    >
                        <template slot="items" slot-scope="props">
                            <tr @click="props.expanded = !props.expanded">
                                <td>{{props.item.org_name}}({{props.item.org_name_kana}}) | {{props.item.employment_name}}</td>
                                <td class="text-center" >{{interviewStatus.subList[props.item.employment_id].length}} / {{props.item.whole_interview_count}}</td>
                                <td class="text-xs-center">
                                    <v-chip v-if="interviewStatus.subList[props.item.employment_id][interviewStatus.subList[props.item.employment_id].length -1].interview_result == 'o'" color="primary" label outline disabled>
                                        <v-icon left color="primary">card_giftcard</v-icon> 합격
                                    </v-chip>
                                    <v-chip v-else-if="interviewStatus.subList[props.item.employment_id][interviewStatus.subList[props.item.employment_id].length -1].interview_result == 'x'" color="error" label outline disabled>
                                        <v-icon left color="error">block</v-icon> 불합격
                                    </v-chip>
                                    <v-chip v-else label outline disabled>
                                        <v-icon left>update</v-icon>심사중
                                    </v-chip>
                                </td>
                                <td>
                                    <v-icon v-if="props.expanded" large>expand_less</v-icon>
                                    <v-icon v-else large>expand_more</v-icon>
                                </td>
                            </tr>
                        </template>
                        
                        <template slot="expand" slot-scope="props">
                            <v-card style="background:#C0C2C3; margin:8px">
                                <div style="margin:8px">
                                    <v-data-table
                                    :headers="interviewStatus.subHeaders"
                                    :items="interviewStatus.subList[props.item.employment_id]"
                                    hide-actions
                                    >
                                        <template slot="items" slot-scope="props">
                                            <td class="text-xs-center">{{props.item.interview_count}}차</td>
                                            <td><v-chip disabled>{{props.item.content}}</v-chip>{{props.item.schedule_title}} - {{props.item.interview_content}}</td>
                                            <td>{{props.item.interview_place}}</td>
                                            <td>{{props.item.interview_date}}</td>
                                            <td class="text-xs-center">
                                                <v-chip v-if="props.item.interview_result == 'o'" color="primary" label outline disabled>
                                                    합격
                                                </v-chip>
                                                <v-chip v-else-if="props.item.interview_result == 'x'" color="error" label outline disabled>
                                                    불합격
                                                </v-chip>
                                                <v-chip v-else disabled> 
                                                    심사중
                                                </v-chip>
                                            </td>
                                            <td></td>
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-card>
                        </template>
                        <template slot="no-data">
                            <v-alert :value="true" color="error" icon="warning">
                                Nothing to display :(
                            </v-alert>
                        </template>
                    </v-data-table> -->
                </v-card>
            </v-flex>

            <!-- 내정 결과 Status -->
            <v-flex xs12>
                <v-card>
                    
                    <p class="resultfont" style="margin: 10px 15px 0px 15px; text-align: left">内定結果  : {{norminateStatus.list.length}}件</p>
                    <div style="border-bottom: 3px solid #008080; margin: 0px 15px 0px 15px;">
                    </div>
                    <!-- <v-card-title  class="grey lighten-4 py-4 title" style="color:#008080; font: bold;font-size : 25px">
                        내정 결과  : {{norminateStatus.list.length}}건
                    </v-card-title> -->
                    <v-card-text> 
                        <b-table :fields="norminateStatus.headers" responsive :items="norminateStatus.list" striped>
                            <template slot="name" slot-scope="data">
                                <p>
                                    {{data.item.org_name}} ({{data.item.org_name_kana}}) | {{data.item.employment_name}}
                                </p>
                            </template> 
                            <template slot="syoutaku" slot-scope="data">
                                <p>
                                    <v-chip v-if="data.item.student_acceptance_ox =='o'" label outline disabled color="primary">承諾</v-chip>
                                    <v-chip v-else-if="data.item.student_acceptance_ox =='x'" label outline disabled color="error">辞退</v-chip>
                                    <v-chip v-else label outline disabled>決めてない</v-chip>
                                </p>
                            </template>
                            <template slot="kyouzyuu" slot-scope="data">
                                <p>
                                    <v-chip v-if="data.item.professor_acceptance_ox =='o'" color="primary" label outline disabled>承諾</v-chip>
                                    <v-chip v-else-if="data.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>断る</v-chip>
                                    <v-chip v-else label outline disabled>承諾してない</v-chip>
                                </p>
                            </template>
                            <template slot="status" slot-scope="data">
                                <p>
                                    <v-chip v-if="data.item.student_acceptance_ox == 'o' && data.item.professor_acceptance_ox == 'o'" color="primary" label outline disabled>
                                    <v-icon left>gavel</v-icon>内定完了
                                    </v-chip>
                                    <v-chip v-else-if="data.item.student_acceptance_ox == 'x' && data.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>
                                        <v-icon left>pan_tool</v-icon>辞退完了
                                    </v-chip>
                                    <v-chip v-else label outline disabled>
                                        教授承諾待機
                                    </v-chip>
                                </p>
                            </template>
                            <template slot="actions" slot-scope="data">
                                <p v-if="data.item.student_acceptance_ox == null" >
                                    <v-btn color="success" @click="openResignDialog('accept', data.item.apply_id)">承諾する</v-btn>
                                    <v-btn color="error" @click="openResignDialog('resign', data.item.apply_id)">辞退する</v-btn>
                                </p>    
                                <p v-else >
                                    <v-btn  v-if="data.item.professor_acceptance_ox == null" color="error" @click="removeDecision(props.item.apply_id, props.item.student_acceptance_ox)">
                                        決定を取り消す
                                    </v-btn>
                                    <v-btn v-else disabled color="error">取り消し不可能</v-btn>
                                </p>
                            </template>
                        </b-table>
                    </v-card-text>
                    <!-- <v-data-table
                    :headers="norminateStatus.headers"
                    :items="norminateStatus.list"
                    hide-actions
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{props.item.org_name}} ({{props.item.org_name_kana}}) | {{props.item.employment_name}}</td>
                            <td class="text-xs-center">
                                <v-chip v-if="props.item.student_acceptance_ox =='o'" label outline disabled color="primary">수락</v-chip>
                                <v-chip v-else-if="props.item.student_acceptance_ox =='x'" label outline disabled color="error">사퇴</v-chip>
                                <v-chip v-else label outline disabled>미결정</v-chip>
                            </td>
                            <td class="text-xs-center">
                                <v-chip v-if="props.item.professor_acceptance_ox =='o'" color="primary" label outline disabled>승낙</v-chip>
                                <v-chip v-else-if="props.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>거절</v-chip>
                                <v-chip v-else label outline disabled>미승낙</v-chip>
                            </td>
                            
                            <td class="text-xs-center">
                                <v-chip v-if="props.item.student_acceptance_ox == 'o' && props.item.professor_acceptance_ox == 'o'" color="primary" label outline disabled>
                                    <v-icon left>gavel</v-icon>내정 완료
                                </v-chip>
                                <v-chip v-else-if="props.item.student_acceptance_ox == 'x' && props.item.professor_acceptance_ox == 'x'" color="error" label outline disabled>
                                    <v-icon left>pan_tool</v-icon>사퇴 완료
                                </v-chip>
                                <v-chip v-else label outline disabled>
                                    교수 승낙 대기
                                </v-chip>
                            </td>
                            <td v-if="props.item.student_acceptance_ox == null"  class="text-xs-center">
                                <v-btn color="primary" @click="openResignDialog('accept', props.item.apply_id)">수락하기</v-btn>
                                <v-btn color="error" @click="openResignDialog('resign', props.item.apply_id)">사퇴하기</v-btn>
                                
                            </td>
                            <td v-else class="text-xs-center">
                                <v-btn  v-if="props.item.professor_acceptance_ox == null" color="error" @click="removeDecision(props.item.apply_id, props.item.student_acceptance_ox)">
                                    결정 취소
                                </v-btn>
                                <v-btn v-else disabled color="error">취소 불가</v-btn>
                            </td>
                        </template>
                        <template slot="no-data">
                            <v-alert :value="true" color="error" icon="warning">
                                Nothing to display :(
                            </v-alert>
                        </template>
                    </v-data-table> -->
                </v-card>
            </v-flex>
       
        </v-layout>

        <!-- 사퇴 다이얼로그 -->
        <v-dialog scrollable v-model="resignDialog" width="800">
            <v-card>
                <v-card-title class="grey lighten-4 py-4 title">
                     内定辞退理由
                     <v-spacer></v-spacer>
                     <v-btn color="error" largeb @click="resignDialog = false">x</v-btn>
                </v-card-title>
                <v-card-text>
                    <v-card v-for="apply in resign_apply_list" :key="apply.apply_id">
                        <v-container>
                            <v-layout row justify-center align-center>
                                <v-flex xs12 md10 lg10>
                                    <v-chip color="black" label outline disabled class="subheading">
                                        <v-icon left>business</v-icon>{{norminateStatus.subList[apply.apply_id].org_name}} ({{norminateStatus.subList[apply.apply_id].org_name_kana}}) | {{norminateStatus.subList[apply.apply_id].employment_name}}
                                    </v-chip>
                                </v-flex>
                                <v-flex xs10>
                                    <v-select
                                    label="内定辞退理由を選択してください。"
                                    :items="resign_infos"
                                    item-text="content"
                                    item-value="id"
                                    v-model="apply.resign_id"
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-card-text>
                <v-card-actions>
                    <v-btn block color="error" @click="setResignation">
                        辞退する
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
  created: function() {
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

    this.getStatus();

    //내정 사퇴이유 가져오기
    let tableList = ["resign_infos"];
    this.axios
      .post("/item/listUp", { tableList })
      .then(rep => {
        this.resign_infos = rep.data.resign_infos;
      })
      .catch(ex => {
        console.log(ex);
      });
  },

  data: () => ({
    user: {
      login_id: null,
      classification: null
    },

    applyStatus: {
      tableTitle: "志願数",
      list: [],
      headers1: [
        //{text : '채용 id', value : 'employment_id', width : '88px', sortable: false, align:'center'},
        { text: "채용건", value: "org_name", sortable: false },
        //{text : '사퇴 가능 여부', value :'acceptance_fixed_ox', width : '90px', sortable: false, align:'center'},
        {
          text: "교수 승낙",
          value: "apply_permission_ox",
          sortable: false,
          align: "center"
        },
        { text: "Action", value: "name"}
      ],
      headers: [
        { label: "採用件", key: "org_name"},
        {
          label: "教授承諾",
          key: "apply_permission_ox",
        },
        { label: "Action", key: "name"}
      ]
    },
    interviewStatus: {
      tableTitle: "面接結果",
      list: [],
      headers1: [
        //{text : '채용 id', value : 'employment_id', width : '88px', sortable: false, align:'center'},
        { text: "채용건", value: "org_name", sortable: false },
        {
          text: "진행 면접횟수 / 전체 면접횟수",
          value: "name",
          sortable: false,
          align: "center"
        },
        { text: "결과", sortable: false, align: "center" },
        { text: "", sortable: false, width: "10px" }
      ],
      headers: [
        //{text : '채용 id', value : 'employment_id', width : '88px', sortable: false, align:'center'},
        { label: "採用件", key: "org_name"},
        { label: "進行中の面接 / 全体面接数", key: "name"},
        { label: "結果", key:"kekka"},
        { label: "", key:"x"}
      ],
      subListKeys: [],
      subList: {},
      subHeaders1: [
        { sortable: false, text: "回数", align: "center", width: "30px" },
        { sortable: false, text: "面接種類" },
        { sortable: false, text: "場所" },
        { sortable: false, text: "日" },
        { sortable: false, text: "結果", align: "center" },
      ],
      subHeaders: [
        { label: "回数", key: "num" },
        { label: "面接種類", key: "item" },
        { label: "場所", key: "place" },
        { label: "日", key: "day" },
        { label: "結果", key: "result" },
      ] 
    },

    norminateStatus: {
      tableTitle: "内定数",
      list: [],
      subList: {},
      headers1: [
        //{text : '채용 id', sortable : false, align : 'center'},
        { text: "채용건", sortable: false },
        { text: "수락 여부", sortable: false, align: "center" },
        { text: "교수 승낙", sortable: false, align: "center" },
        { text: "Status", sortable: false, align: "center" },
        { text: "Actions", sortable: false, align: "center" }
      ],
      headers: [
        //{text : '채용 id', sortable : false, align : 'center'},
        { label: "採用件", key:"name"},
        { label: "承諾可否", key:"syoutaku"},
        { label: "教授承諾", key:"kyouzyuu"},
        { label: "Status", key:"Status"},
        { label: "Actions", key:"actions"}
      ]
    },

    //사퇴이유 항목 리스트
    resign_infos: [],

    //사퇴 이유 리스트
    resign_apply_list: [],

    finalCompanyInfo: null,

    resignDialog: false
  }),

  methods: {
    getStatus() {
      let login_id = this.user.login_id;
      let classification = this.user.classification;

      this.axios
        .post("/student/getStatus", { login_id, classification })
        .then(rep => {
          this.applyStatus.list = rep.data.applyStatus;

          //지원한 채용건 리스트
          this.interviewStatus.subListKeys = rep.data.interviewEmploymentList;

          //면접 진행 채용건 리스트 뽑기
          this.interviewStatus.list = [];
          for (let key in this.interviewStatus.subListKeys) {
            for (let item in this.applyStatus.list) {
              if (
                this.interviewStatus.subListKeys[key] ==
                this.applyStatus.list[item].employment_id
              )
                this.interviewStatus.list.push(this.applyStatus.list[item]);
            }
          }

          //채용건 리스트 별로 인터뷰 리스트 array 생성
          for (let index in this.interviewStatus.subListKeys) {
            let employment_id = this.interviewStatus.subListKeys[index];
            this.interviewStatus.subList[employment_id] = [];
          }

          //각 채용건 리스트에 대한 인터뷰 결과 리스트 뽑기
          let subList = rep.data.interviewStatus;
          for (let index in subList) {
            let employment_id = subList[index].employment_id;
            this.interviewStatus.subList[employment_id].push(subList[index]);
          }

          this.norminateStatus.list = rep.data.norminateStatus;
          for (let index in this.norminateStatus.list) {
            let apply_id = this.norminateStatus.list[index].apply_id;
            this.norminateStatus.subList[apply_id] = this.norminateStatus.list[
              index
            ];
          }
          this.finalCompanyInfo = rep.data.finalCompanyInfo;
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    deleteApply(apply_id) {
      let yesNo = confirm("志願を取り消ししますが？");
      if (!yesNo) {
        alert("取り消ししました。");
        return;
      }

      let login_id = this.user.login_id;

      this.axios
        .post("/student/deleteApply", { login_id, apply_id })
        .then(rep => {
          if (rep.data.returnBool) {
            alert("採用件の志願が取り消しされました。");
            this.getStatus();
          } else {
            alert("問題が発生されました。");
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //사퇴 창 오픈
    openResignDialog(mode, apply_id) {
      //초기화
      this.resign_apply_list = [];

      if (mode == "resign") {
        this.resign_apply_list.push({ apply_id: apply_id });
        this.resignDialog = true;
        return;
      } else if (mode == "accept") {
        for (let index in this.norminateStatus.list) {
          if (
            apply_id != this.norminateStatus.list[index].apply_id &&
            this.norminateStatus.list[index].student_acceptance_ox == null
          ) {
            let obj = this.norminateStatus.list[index];
            obj.list_index = index;
            this.resign_apply_list.push(obj);
          }
        }

        //사퇴할 다른 채용건이 없을 때
        if (this.resign_apply_list.length == 0) {
          this.setAcceptance(apply_id);
          return;
        } else {
          alert("ほかの内定を辞退してください。");
          this.resignDialog = true;
          return;
        }
      }
    },

    //내정 수락
    //*** 학생이 내정 수락을 한다. --> 교수에게 알리기
    setAcceptance(apply_id) {
      let yesNo = confirm("内定を承諾しますか？");
      if (!yesNo) {
        alert("取り消ししまいた。");
        return;
      }
      let login_id = this.user.login_id;
      let apiKey = this.$store.getters.push_config.apiKey;
      this.axios
        .post("/student/setAcceptance", { login_id, apply_id, apiKey })
        .then(rep => {
          let returnBool = rep.data.returnBool;
          if (returnBool) {
            alert("内定が承諾されました。");
            this.getStatus();
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //내정 사퇴 예외 처리
    resignValidationCheck() {
      for (let index in this.resign_apply_list) {
        if (this.resign_apply_list[index].resign_id == null) return false;
      }

      return true;
    },

    //내정 사퇴
    //*** 학생이 내정 사퇴의사를 밝힌다. --> 교수에게 알리기
    setResignation() {
      let yesNo1 = this.resignValidationCheck();
      if (!yesNo1) {
        alert("辞退理由を選択してください.");
        return;
      }

      let yesNo2 = confirm("内定を辞退しますか？");
      if (!yesNo2) {
        alert("取り消ししました。");
        return;
      }
      let login_id = this.user.login_id;
      let resign_apply_list = this.resign_apply_list;
      let apiKey = this.$store.getters.push_config.apiKey;

      this.axios
        .post("/student/setResignation", {
          login_id,
          resign_apply_list,
          apiKey
        })
        .then(rep => {
          let returnBool = rep.data.returnBool;
          if (returnBool) {
            alert("内定辞退ができました。");
            this.getStatus();
            this.resignDialog = false;
          } else {
            alert("問題が発生しました。");
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    },

    //결정 취소
    removeDecision(apply_id, student_acceptance_ox) {
      let yesNo = confirm("決定を取り消ししますか？");
      if (!yesNo) {
        alert("取り消ししました。");
        return;
      }
      let login_id = this.user.login_id;
      let api_key = this.$store.getters.push_config.apiKey;

      this.axios
        .post("/student/removeDecision", {
          login_id,
          apply_id,
          api_key,
          student_acceptance_ox
        })
        .then(rep => {
          let returnBool = rep.data.returnBool;
          if (returnBool) {
            let str =
              student_acceptance_ox == "o" ? "内定承諾が" : "内定辞退が";
            alert(str + " 取り消ししました。");
            this.getStatus();
          } else {
            alert("問題が発生しました。");
          }
        })
        .catch(ex => {
          console.log(ex);
        });
    }
  }
};
</script>

<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>
