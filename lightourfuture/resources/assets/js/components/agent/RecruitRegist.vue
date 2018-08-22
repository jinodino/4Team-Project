<template> 
  <v-container fluid grid-list-lg text-xs-center v-if="orgAgent != null && matchingItem != null">
    <v-card style="background:#E3F2FD">
      <v-container fluid>
        <v-layout row text-center>
          <v-flex xs12 md12 lg12>
            <div class="display-1">
              採用情報 {{status.label}}
            </div>
            <div xs12 md12 lg12>
              {{"college : " + matchingItem.college_name_kana + ' / ' + "company : " + matchingItem.company_name_kana}}<br>
              {{'Hosting Agent : ' + orgAgent.org_name}}
            </div>
            <div style="color : red;  float:right">'☆'は必ず作成してください。</div>
          </v-flex>
        </v-layout>
      
      <v-flex xs12 md12 lg12>
        <!-- 지원 정보 -->
        <v-card style="margin-top : 8px">
          <v-container fluid>
            
            <v-layout wrap>
              <v-flex xs12 md12 lg12>
                <v-text-field
                  v-model="DB.employment_name"
                  label="募集ポジション☆"
                  
                ></v-text-field>
              </v-flex>
              
              <!-- <v-flex xs6 md6 lg6>
                <v-text-field
                  v-model="DB.recruit_number"
                  label="모집 인원"
                ></v-text-field>
              </v-flex>
              <v-flex xs6 md6 lg6>
                <v-text-field
                  v-model="DB.working_sort"
                  label="모집 직종"
                ></v-text-field>
              </v-flex> -->
              
              <v-flex xs12 md6 lg6>
                <v-text-field
                  v-model="DB.recruit_number"
                  label=" 募集人数"
                ></v-text-field>

                <v-menu
                  ref="apply_open_date"
                  lazy
                  :close-on-content-click="false"
                  v-model="datePicker.apply_open_date"
                  transition="scale-transition"
                  offset-y
                  full-width
                  :nudge-right="40"
                  min-width="290px"
                  :return-value.sync="DB.apply_open_date"
                >
                  <v-text-field
                    slot="activator"
                    label="募集開始日☆"
                    v-model="DB.apply_open_date"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="DB.apply_open_date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="datePicker.apply_open_date = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.apply_open_date.save(DB.apply_open_date)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
                <v-menu
                  ref="apply_deadline_time"
                  :close-on-content-click="false"
                  v-model="datePicker.apply_deadline_time"
                  :nudge-right="40"
                  :return-value.sync="apply_deadline_time"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="apply_deadline_time"
                    label="志願締め切り時間"
                    prepend-icon="access_time"
                    readonly
                  ></v-text-field>

                  <v-time-picker 
                  v-model="apply_deadline_time" 
                  @change="$refs.apply_deadline_time.save(apply_deadline_time)"
                  >
                  </v-time-picker>
                </v-menu>

              </v-flex>
              
              <v-flex xs12 md6 lg6>
                <v-text-field
                  v-model="DB.working_sort"
                  label="募集職種"
                ></v-text-field>
                <v-menu
                  ref="apply_deadline_date"
                  lazy
                  :close-on-content-click="false"
                  v-model="datePicker.apply_deadline_date"
                  transition="scale-transition"
                  offset-y
                  full-width
                  :nudge-right="40"
                  min-width="290px" 
                  :return-value.sync="DB.apply_deadline_date"
                  :disabled = "DB.apply_open_date == ''"
                >
                  <v-text-field
                    slot="activator"
                    :disabled = "DB.apply_open_date == ''"
                    label="募集締切日☆"
                    v-model="DB.apply_deadline_date"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="DB.apply_deadline_date" :min="DB.apply_open_date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="datePicker.apply_deadline_date = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.apply_deadline_date.save(DB.apply_deadline_date)">OK</v-btn>
                  </v-date-picker>
                </v-menu>

                <v-menu
                  ref="expected_acceptance_date"
                  lazy
                  :close-on-content-click="false"
                  v-model="datePicker.expected_acceptance_date"
                  transition="scale-transition"
                  offset-y
                  full-width
                  :nudge-right="40"
                  min-width="290px"
                  :disabled = "DB.apply_deadline_date == ''"
                  :return-value.sync="DB.expected_acceptance_date"
                >
                  <v-text-field
                    :disabled = "DB.apply_deadline_date == ''"
                    slot="activator"
                    label="内定提示時期"
                    v-model="DB.expected_acceptance_date"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="DB.expected_acceptance_date" no-title scrollable :min="DB.apply_deadline_date">
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="datePicker.expected_acceptance_date = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.expected_acceptance_date.save(DB.expected_acceptance_date)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex>
            </v-layout>
            <v-layout>
              <!-- <v-flex xs12 md6 lg6>
                <v-menu
                  ref="apply_deadline_time"
                  :close-on-content-click="false"
                  v-model="datePicker.apply_deadline_time"
                  :nudge-right="40"
                  :return-value.sync="apply_deadline_time"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    slot="activator"
                    v-model="apply_deadline_time"
                    label="지원마감 시간"
                    prepend-icon="access_time"
                    readonly
                  ></v-text-field>

                  <v-time-picker 
                  v-model="apply_deadline_time" 
                  @change="$refs.apply_deadline_time.save(apply_deadline_time)"
                  >
                  </v-time-picker>
                </v-menu>
              </v-flex> -->

              <!-- <v-flex xs12 md6 lg6>
                <v-menu
                  ref="expected_acceptance_date"
                  lazy
                  :close-on-content-click="false"
                  v-model="datePicker.expected_acceptance_date"
                  transition="scale-transition"
                  offset-y
                  full-width
                  :nudge-right="40"
                  min-width="290px"
                  :disabled = "DB.apply_deadline_date == ''"
                  :return-value.sync="DB.expected_acceptance_date"
                >
                  <v-text-field
                    :disabled = "DB.apply_deadline_date == ''"
                    slot="activator"
                    label="내정 제시 시기"
                    v-model="DB.expected_acceptance_date"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker v-model="DB.expected_acceptance_date" no-title scrollable :min="DB.apply_deadline_date">
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="datePicker.expected_acceptance_date = false">Cancel</v-btn>
                    <v-btn flat color="primary" @click="$refs.expected_acceptance_date.save(DB.expected_acceptance_date)">OK</v-btn>
                  </v-date-picker>
                </v-menu>
              </v-flex> -->
            </v-layout>

          </v-container>
            
        </v-card>
        
        <v-card style="margin-top : 8px">
          
          <v-container fluid
          >
            <!-- {{interview_schedule}} -->
          <v-layout>
              <v-flex xs12 md6 lg6>
                <!-- interviewCount -->
                <v-select
                  :label="interviewCount.label"
                  :items="interviewCount.number"
                  v-model="DB.whole_interview_count"
                  auto
                ></v-select>
                <!-- <v-text-field
                  v-model="DB.whole_interview_count"
                  :label="interviewCount.label"
                  :min="interviewCount.min"
                  :max="interviewCount.max" 
                  type = "number"
                ></v-text-field> -->
              </v-flex>
                <v-btn color="success" @click="InterviewType">選考種類入力☆</v-btn>
              <v-btn color="error" @click="AllClear">All clear</v-btn>
            </v-layout>

            <b-table :fields="header" responsive :items="interview_schedule" item-key="faculty_id"  striped>
              <template slot="interview_count" slot-scope="data">
                <p>{{++data.index}}</p>
              </template> 
              <template slot="schedule_title" slot-scope="data">
                <p>{{data.item.schedule_title == null? ' - ' : data.item.schedule_title}}</p>
              </template>
              <template slot="interview_content_choice" slot-scope="data">
                <p><v-chip disabled>{{data.item.interview_content_choice == null ? ' - ': interview_infos[data.item.interview_content_choice-1].content }}</v-chip></p>
              </template><template slot="interview_date" slot-scope="data">
                <p>{{data.item.interview_date}}</p>
              </template> 
              <template slot="interview_start_time" slot-scope="data">
                <p>{{data.item.interview_start_time}}</p>
              </template>
              <template slot="interview_end_time" slot-scope="data">
                <p>{{data.item.interview_end_time}}</p>
              </template>
              <template slot="edit" slot-scope="data">
                <p>
                  <v-btn icon @click="editItem(data.item.interview_count)">
                    <v-icon color="teal" >edit</v-icon>
                  </v-btn>
                  <v-btn icon @click="deleteItem(data.item.interview_count)">
                    <v-icon color="pink">delete</v-icon>
                  </v-btn>
                </p>
              </template>
            </b-table>
            <template>
              <v-alert v-if="interview_schedule.length == 0" :value="true" color="error" icon="warning">
                 No Data
              </v-alert>
            </template>

              <!-- <v-data-table
                :headers="header1"
                :items="interview_schedule" 
                hide-actions
              >
                <template slot="items" slot-scope="props">
                  <td class="text-xs-center">{{ ++props.index }}</td>
                  <td class="text-xs-center">{{props.item.schedule_title == null? ' - ' : props.item.schedule_title}}</td>
                  <td class="text-xs-center"><v-chip disabled>{{props.item.interview_content_choice == null ? ' - ': interview_infos[props.item.interview_content_choice-1].content }}</v-chip></td>
                  <td class="text-xs-center">{{props.item.interview_date}}</td>
                  <td class="text-xs-center">{{props.item.interview_start_time}}</td>
                  <td class="text-xs-center">{{props.item.interview_end_time}}</td>
                  <td>
                    <v-btn icon @click="editItem(props.item.interview_count)">
                      <v-icon color="teal" >edit</v-icon>
                    </v-btn>
                    <v-btn icon @click="deleteItem(props.item.interview_count)">
                      <v-icon color="pink">delete</v-icon>
                    </v-btn>
                  </td>
                </template>
                <template slot="no-data">
                  <v-alert :value="true" color="error" icon="warning">
                    No Data
                  </v-alert>                
                </template>
              </v-data-table> -->
          </v-container>
        </v-card>
        <!-- 사퇴 카드 라디오 -->
        <v-card style="margin-top : 10px">
          <v-layout>
            <v-flex xs12 md4 lg4 v-for="(item, key) in term.Resigned" :key="key">
              <v-icon x-large v-bind:color="item.icon_color">{{item.icon}}</v-icon><br>
              <input type="radio" name="resignation" 
                v-bind:value="item.item_value" v-bind:checked="item.default" 
                
                v-model="DB.acceptance_fixed_ox">{{item.item_text}}
            </v-flex>
          </v-layout>
        </v-card>

        <!--  상세 정보 -->
        
        <v-card style="margin-top : 10px">
          
          <v-container>
            <v-text-field
              v-model="DB.working_area"
              label="勤務地☆"
            ></v-text-field>
            <v-text-field
              v-model="DB.motomeru_major_grade"
              label="専攻能力"
            ></v-text-field>
            <v-text-field
              v-model="DB.motomeru_language_grade"
              label="外国語能力"
            ></v-text-field>
            <v-text-field
              v-model="DB.motomeru_sonohoka"
              label="その他"
            ></v-text-field>
            <v-layout row v-for="item in checkBoxMenu" :key="item.table">
              <v-flex xs12>
                <v-expansion-panel>
                  <v-expansion-panel-content  style="background:#EEEEEE">
                    <div slot="header">
                      <v-layout row>
                        <div>
                          {{item.label}}
                        </div>
                        <v-flex xs12>
                          <v-chip color='light gray' v-if="checkBoxValue[item.table].value.length == 0" disabled>
                            There's no tag yet :)
                          </v-chip>
                          <v-chip
                            v-else-if="checkBoxMenu[item.table].menus.length != 0"
                            color="primary"
                            text-color="white"
                            v-for="pill in checkBoxValue[item.table].value" 
                            :key="pill"
                            disabled
                            close
                            @input="removeTag(pill, item.table)"
                          >
                          {{pill}}&nbsp;&nbsp;{{checkBoxMenu[item.table].menus[pill-1].content}} 
                          </v-chip>
                        </v-flex>
                      </v-layout>
                    </div>
                    <v-card>
                      <v-card-text>
                        <v-layout row>
                          <v-flex  xs12 sm6 lg4 v-for="(Check, key) in item.menus" :key="key"> 
                            <v-checkbox 
                            :label="Check.content"
                            :value="Check.id"
                            v-model="checkBoxValue[item.table].value"
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
                  :label="item.textLabel" 
                  textarea
                  v-model="DB[item.textField]"
                  :rules="[(v) => v.length <= item.max || 'Max '+ item.max + ' characters']"
                  >
                </v-text-field>
              </v-flex>
            </v-layout>
            
          </v-container>
        </v-card>
        
        <v-card style="margin-top : 10px">
          <v-container>
            <div style="font-size: 18px; text-align:left">労働条件</div>
            <v-layout wrap>
              <v-flex xs6>
                <v-text-field
                  v-model="DB.work_term"
                  label="契約期間☆"
                ></v-text-field>
                <v-text-field
                  v-model="DB.business_hour"
                  label="勤務時間（週）☆"
                ></v-text-field>
                <v-text-field
                  v-model="DB.pay"
                  label="給料☆"
                ></v-text-field>
                <v-text-field 
                  v-model="DB.bonus_pay"
                  label="ボーナス"
                >
                </v-text-field>
                <v-text-field 
                  v-model="DB.transport_pay"
                  label="交通費"
                >
                </v-text-field>
              </v-flex>

              <v-flex xs6>
                <v-layout wrap style="margin-bottom:25px; margin-top:12px">
                  <v-flex xs6>
                    <input type="radio" name="insurance" value="o"
                    v-model="DB.insurance_content">保険加入
                    </v-flex>
                    <v-flex xs6>
                    <input type="radio" name="insurance" value="x" checked
                    v-model="DB.insurance_content">保険非加入
                  </v-flex>
                </v-layout>
                <v-text-field
                  v-model="DB.holiday"
                  label="休日"
                ></v-text-field>
                <v-text-field 
                  v-model="DB.overtime_pay"
                  label="残業代"
                >
                </v-text-field>
                <v-text-field 
                  v-model="DB.house_pay"
                  label="住宅補助金"
                >
                </v-text-field>
                <v-text-field 
                  v-model="DB.dormitory_airport_support"
                  label="寮/航空運賃をサポート"
                >
                </v-text-field>
              </v-flex>
            </v-layout>
            <v-text-field 
              label="その他" 
              textarea
              v-model="DB.other_work_condition"
              :rules="[(v) => v.length <= item.max || 'Max '+ item.max + ' characters']"
              >
            </v-text-field>
            
            

          </v-container>
        </v-card>
        
        <v-layout row >
          <v-flex xs12 md12 lg12>
            <v-btn v-if="status.mode == 'create'" large color="success" block @click="createRecruit">登録</v-btn>
            <v-btn v-else large color="dahong" block @click="updateRecruit">修正</v-btn>
            <v-btn large color="error" block @click="go2Back">取り消す</v-btn>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-container>
    </v-card>



    <v-dialog v-model="dialog" max-width="70%" persistent>
      <v-card>
        <v-card-title>
          <span class="headline">選考種類</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap  justify-center>
              <!-- <v-flex xs12 md12 lg12 v-for="(index, key) in interviewTypecountArr" :key="key">
                {{index}} -->
                  <v-flex xs12 md12>
                    面接回数 : {{editedItem.interview_count}}回目
                  </v-flex>
                  <v-flex xs12 md12>
                    <v-text-field
                      v-model="editedItem.schedule_title"
                      label="面接タイトル"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md12>
                    <v-text-field
                      v-model="editedItem.interview_content"
                      label="細かい内容"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md12>
                    <v-select
                      label="面接分類"
                      :items="interview_infos"
                      item-text="content"
                      item-value="id"
                      v-model="editedItem.interview_content_choice"
                      auto
                    ></v-select>
                  </v-flex>
                  
                  <v-flex xs12 md12>
                    <v-layout >　
                      <v-flex xs4 md4 >
                        <v-checkbox v-model="date" label="未定" hide-details class="shrink mr-2"></v-checkbox>                 
                      </v-flex>
                      <v-flex xs8 md8 >
                        <v-menu
                          ref="interview_date"
                          lazy
                          :close-on-content-click="false"
                          v-model="datePicker.interview_date"
                          transition="scale-transition"
                          offset-y
                          full-width
                          :nudge-right="40"
                          min-width="290px"
                          :return-value.sync="editedItem.interview_date"
                          :disabled="date"
                        >
                          <v-text-field
                            slot="activator"
                            label="面接予定日"
                            v-model="editedItem.interview_date"
                            prepend-icon="event"
                            readonly
                            :disabled="date"
                          ></v-text-field>
                          <v-date-picker v-model="editedItem.interview_date" no-title scrollable :disabled="date">
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="datePicker.interview_date = false">Cancel</v-btn>
                            <v-btn flat color="primary" @click="$refs.interview_date.save(editedItem.interview_date)">OK</v-btn>
                          </v-date-picker>
                        </v-menu>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex xs12 md12>
                    <v-layout >
                      <v-flex xs4 md4 >
                        <!-- <v-checkbox v-model="start_time" :disabled="date" label="決めてない" hide-details class="shrink mr-2"></v-checkbox>                  -->
                      </v-flex>
                      <v-flex xs8 md8 >
                        <v-menu
                          ref="interview_start_time"
                          :close-on-content-click="false"
                          v-model="datePicker.interview_Stime"
                          :nudge-right="40"
                          :return-value.sync="interview_Stime"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                          :disabled="date"
                        >
                          <v-text-field
                            slot="activator"
                            v-model="editedItem.interview_start_time"
                            label="面接開始時間"
                            prepend-icon="access_time"
                            readonly
                          :disabled = "date"
                          ></v-text-field>

                          <v-time-picker 
                          :disabled = "date"
                          v-model="editedItem.interview_start_time" 
                          @change="$refs.interview_start_time.save(editedItem.interview_start_time)"
                          >
                          </v-time-picker>
                        </v-menu>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  
                  <v-flex xs12 md12>
                  <v-layout row>
                    <v-flex xs4 md4>
                      <!-- <v-checkbox v-model="end_time" :disabled="date" label=" 決めてない" hide-details class="shrink mr-2"></v-checkbox>    -->
                    </v-flex>
                    <v-flex xs8 md8 >
                      <v-menu
                        ref="interview_end_time"
                        :close-on-content-click="false"
                        v-model="datePicker.interview_Etime"
                        :nudge-right="40"
                        :return-value.sync="interview_Etime"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px"
                        :disabled = "date"
                      >
                        <v-text-field
                          :disabled = "date"
                          slot="activator"
                          v-model="editedItem.interview_end_time"
                          label="面接終了時間"
                          prepend-icon="access_time"
                          readonly
                        ></v-text-field>

                        <v-time-picker 
                          :disabled = "date"
                        v-model="editedItem.interview_end_time" 
                        @change="$refs.interview_end_time.save(editedItem.interview_end_time)"
                        >
                        </v-time-picker>
                      </v-menu>
                    </v-flex>
                  </v-layout>
                  </v-flex>
                  <!--  -->
              <!-- </v-flex> -->
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="interviewCancel">Cancel</v-btn>
          <v-btn color="blue darken-1" flat @click="interviewSave">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog> 
  </v-container>
</template>
<script>
export default {

  created : function(){
    //mode 확인   
    let employment_id = this.$route.query.employment_id;
    let matchingId = this.$route.query.matching_id;
    let orgAgent = this.$route.params.orgAgent;
   
    //작성 orgAgent 정보가 없으면 이전페이지로 돌아가기
    if(orgAgent == null || matchingId == null)
      this.$router.go(-1);

    this.orgAgent = orgAgent;
    this.DB.org_matching_foreign = matchingId;

    if(employment_id == null){
 
      this.status.label = "登録";
      this.status.mode = "create";
      
    }else{
      this.status.label = "取り消す";
      this.status.mode = "update";

      this.getRecruit(employment_id);
    }

    
    this.axios.post('/agent/getMatchingById', {matchingId})
              .then(rep=>{
                this.matchingItem = rep.data[0];
              })
              .catch(ex=>{
                console.log(ex);
              });

    let tableList = ['work_infos','desired_employment_infos', 'welfare_infos', 'interview_infos'];
    this.axios.post('/item/listUp', {tableList} )
    .then(rep => {
        this.checkBoxMenu.work_matchings.menus = rep.data.work_infos;
        this.checkBoxMenu.desired_employments.menus = rep.data.desired_employment_infos;
        this.checkBoxMenu.welfares.menus = rep.data.welfare_infos;
        this.interview_infos = rep.data.interview_infos;
    })
    .catch(ex => {
      console.log(ex);
    });
    
    //일본 행정구역 가져오기
    // this.axios.post('/japan/getTodouhuken', )
    // .then(rep =>{
    //   this.japan_regions_menu = rep.data.regions;
    //   this.japan_prefectures_menu = rep.data.prefectures;
    // })
    // .catch(ex=>{
    //   console.log(ex);
    // });

    //사용자 정보 가져오기
    this.user.login_id = this.$store.getters.identification;
    this.user.classification = this.$store.getters.classification;

  },

  data : ()=>({
    status : {
      label : null,
      mode : null
    },

    user : {
      login_id : null,
      classification : null
    },

    matchingItem : null,


    //면접 횟수
    interviewCount : {
      label : '前代面接回数',
      number : [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
    },
 
    orgAgent : null,


    datePicker : {
      apply_open_date : false,
      apply_deadline_date  : false,
      expected_acceptance_date : false,

      apply_deadline_time: false,
      
      interview_date : false,
      interview_Stime : false,
      interview_Etime : false,
    },

    DB : { 

      whole_interview_count : 1,//면접 횟수

      pay_content : null, //급여 조건
      bonus_pay : null, //상여금
      transport_pay : null,//교통수단
      overtime_pay  : null,//잔업 수당
      house_pay : null,//주택 보조 수당
      dormitory_airport_support  : null,//기숙사, 항공쵸 지원
      recruit_number : null, //모집 인원
      working_sort : null, // 직종
      insurance_content : null,//보험
      other_work_condition : null,//기타 근로 조건
      
      pay : "", // 급료
      business_hour : "", // 근무 시간
      working_area : "", // 근무지
      address_to_dou_hu_ken : "", //토도부현
      holiday : "", // 휴일
      org_matching_foreign : "", //매칭 아이디


      // file_url : "", // 첨부파일
      business_type_content : "", // 근무내용
      desired_employee_content : "", // 원하는 인재
      welfare_content : "",  // 대우/복리후생
      employment_name : "",//채용 이름

      // 지원 마감일 apply_deadine
      apply_open_date : "",
      apply_deadline_date  : "",
 
      // 내정 제시 시기 empected
      expected_acceptance_date : "",
      //내정 사퇴 가능 여부
      acceptance_fixed_ox : "",
    },

//지원 마감 시간 
    apply_deadline_time : null,
    dialog : false,
    day : "",
    time : null,
    interview_Stime : null,
    interview_Etime : null,

    //인터뷰 시간  
    
    date : true,
    
    checkBoxValue : {
      work_matchings : {value : [], table:'work_matchings'}, 
      desired_employments : {value : [], table:'desired_employments'},
      welfares : {value : [], table:'welfares'},
    },
    interview_infos : [],

    checkBoxMenu:{

      work_matchings :{
        label : '仕事内容分類(複数選択可)', 
        textLabel : "仕事内容(500字以内)☆",
        textField : "business_type_content",
        menus : [], 
        table : 'work_matchings', 
        max : 500
      },

      desired_employments:{
        label : '求めている人材分類(複数選択可、最大3個まで)', 
        textLabel : "求めている人材(500字以内)",
        textField : "desired_employee_content",
        menus : [], 
        table : 'desired_employments', 
        max : 500
      },

      welfares :{
        label : '待遇・福利厚生(複数選択可)',
        textLabel : "待遇・福利厚生(500字以内)", 
        textField : "welfare_content",
        menus : [], 
        table : 'welfares', 
        max : 500
      }
    },

    term : {
      Resigned :[
        { item_text : '辞退可能', 
          icon : 'delete', 
          icon_color : 'green darken-2',
          item_value : 'o',
          default : false
        },
        { item_text : '辞退不可能', 
          icon : 'delete_forever', 
          icon_color : 'red darken-2',
          item_value : 'x',
          default : false
        },
        { item_text : '未定', 
          icon : 'error_outline', 
          icon_color : 'purple accent-2',
          item_value : '',
          default : true
        },

      ]
    },
    // japan_regions_menu : [],
    // japan_prefectures_menu : [],
    // curr_region_code : '',

    interviewTypecountArr : [1],
    interviewcountArr : 1,

    header1: [
      { text: '回数', value: 'interview_count',   align:'center', sortable : false},
      { text: '面接名', value: 'schedule_title',   align:'center'},
      { text: '面接分類', value: 'interview_content_choice',   align:'center'},
      { text: '面接日', value: 'interview_date',   align:'center'},
      { text: '面接始まり時間', value: 'interview_start_time',   align:'center'},
      { text: '終わり時間', value: 'interview_end_time',   align:'center'},
      { text: 'Action', value: 'edit',   align:'center'}
    ],
    header: [
      { label: '回数', key: 'interview_count'},
      { label: '面接名', key: 'schedule_title'},
      { label: '面接分類', key: 'interview_content_choice'},
      { label: '面接日', key: 'interview_date'},
      { label: '面接始まり時間', key: 'interview_start_time'},
      { label: '終わり時間', key: 'interview_end_time'},
      { label: 'Action', key: 'edit'}
    ],
    //차수 (interview_count), 타이틀 (schedule_title), 면접 종류(interview_content_choice)
    //ex) [ { "interview_count": 1, "schedule_title": "-", "interview_content_choice": "1" }, 
    //      { "interview_count": 2, "schedule_title": "ㄴㅇㅁㄴㅇ", "interview_content_choice": "1" } ]
    interview_schedule : [],//회차별 면접 

    editedItem :{
      interview_count : 1,
      schedule_title: null,

      interview_content : null,

      interview_content_choice : null,

      interview_date : null,
      interview_start_time : null,
      interview_end_time : null,
    },
    //수정인지 새 일정 저장인지 구분
    divcode : null
  }),

  methods: {

    //디비에 등록
    //*** 새로운 채용건 등록하기 --> 교수, 학생에게 알리기
    createRecruit(){

      //유효성 체크
      let returnArr = this.validationCheck();
      let returnBool = returnArr[0];
      let returnMsg = returnArr[1];
      if(!returnBool){
        alert(returnMsg);
        return;
      }

      
      let interview_schedule = this.interview_schedule;
      for(let index in interview_schedule)
        interview_schedule[index].interview_count = index*1 + 1;

      let DB = this.DB;
      if(this.apply_deadline_time == null)
        DB.apply_deadline_date = new Date(DB.apply_deadline_date + 'T' + this.apply_deadline_time);

      let checkBoxValue = this.checkBoxValue;
      let login_id = this.user.login_id;
      let apiKey = this.$store.getters.push_config.apiKey;

      this.axios.post('/company/createRecruit', { DB, checkBoxValue, login_id, interview_schedule, apiKey})
                .then(rep=>{
                    this.$router.push(
                    { 
                      path : '/agent/recruit',
                      query:{ employment_id : rep.data, matching_id : this.DB.org_matching_foreign, agent_id:this.orgAgent.org_agent_id}
                    });
                })
                .catch(ex=>{
                  console.log(ex);
                });

    },

    updateRecruit(){
      //유효성 체크
      let returnArr = this.validationCheck();
      let returnBool = returnArr[0];
      let returnMsg = returnArr[1];
      if(!returnBool){
        alert(returnMsg);
        return;
      }

      let interview_schedule = this.interview_schedule;
      for(let index in interview_schedule)
        interview_schedule[index].interview_count = index*1 + 1;
      
      let DB = this.DB;
      DB.apply_deadline_date = new Date(DB.apply_deadline_date + 'T' + this.apply_deadline_time);
      let checkBoxValue = this.checkBoxValue;

      this.axios.post('/company/updateRecruit', { DB, checkBoxValue, interview_schedule })
      .then(rep=>{
          this.$router.push(
          { 
            path : '/agent/recruit',
            query:{ employment_id : rep.data, matching_id : this.DB.org_matching_foreign, agent_id:this.orgAgent.org_agent_id}
          });
      })
      .catch(ex=>{
        console.log(ex);
      });
    },

    go2Back(){
      this.$router.go(-1);
    },

    removeTag(pill, title){

      if(title == 'work_matchings'){
        let index = this.checkBoxValue.work_matchings.value.indexOf(pill);
        this.checkBoxValue.work_matchings.value.splice(index, 1);
      }else if(title == 'desired_employments'){
        let index = this.checkBoxValue.desired_employments.value.indexOf(pill);
        this.checkBoxValue.desired_employments.value.splice(index, 1);
      }else if(title == 'welfares'){
        let index = this.checkBoxValue.welfares.value.indexOf(pill);
        this.checkBoxValue.welfares.value.splice(index, 1);
      }
      return;
    },

    //유효성 검사
    validationCheck(){

      //빈칸 체크
      var employment_name = this.DB.employment_name;
      var apply_open_date = this.DB.apply_open_date;
      var apply_deadline_date = this.DB.apply_deadline_date;
      var business_type_content  = this.DB.business_type_content;
      var business_hour  = this.DB.business_hour;
      var pay  = this.DB.pay;
      var working_area  = this.DB.working_area;
      
      if( employment_name == "" || apply_open_date == "" || 
          apply_deadline_date == "" || business_type_content == "" || business_hour == "" || pay == "" || working_area == "" ){

        return [false, "'☆'が付いた欄を占めてください。"];
      }

      //인재상 체크
      if(this.checkBoxValue.desired_employments.value.length > 3){
        return [false, '求める人材は最大3つまで選択可能です。'];
      }
 

      //면접 스케쥴 차수 검사
      if(this.DB.whole_interview_count != this.interview_schedule.length){
        return [false, '面接スケジュールを確認してください。'];
      }

      return [true, ''];
    },

    getRecruit(employment_id){
      this.axios.post('/company/getRecruit',{employment_id})
          .then(rep=>{
      
            let info = rep.data.employment_infos;
             for(let i in info)
                    this.DB[i] = (info[i] == null? '' : info[i]);

            this.interview_schedule = null;
            this.interview_schedule = rep.data.interview_schedule;

            let work = rep.data.work_matchings;
            for(let i in work)
              this.checkBoxValue.work_matchings.value[i] = work[i].id;

            let desire = rep.data.desired_employments;
            for(let i in desire)
              this.checkBoxValue.desired_employments.value[i] = desire[i].id;

            let welfare = rep.data.welfares;
            for(let i in welfare)
              this.checkBoxValue.welfares.value[i] = welfare[i].id;

          })
          .catch(ex=>{
            console.log(ex);
          });
    },

    //원하는 인재 3개 제한
    // CountChecked(field) {
    //   var chkbox = document.getElementsByName('desired_employee_content');
    //   var chkCnt = 0;
    //   var max = 3;
    //   for(var i=0;i<chkbox.length; i++){
    //     if(chkbox[i].checked == true){
    //       chkCnt++;
    //     }
    //   }

    //   if(chkCnt > max){
    //     alert(document.getElementsByName('desired_employee_content'));
    //     // for (var j = 0; j < this.checkBoxMenu.humanResources.length; j++){
    //     //  for(var i = 0; i < max; i ++){
    //     //     if(this.checkBoxMenu.humanResources[j].springtide == this.checkBoxMenu.CheckedDataOnHumanResources[i]){
              
    //     //     }
    //     //   }
    //     // }
        
    //   }
    // },

    // dialog open 면접 타입 선택 갯수
    InterviewType(){

      //초기화 작업
      this.editedItem.interview_count = null;
      this.editedItem.schedule_title = null;
      this.editedItem.interview_content_choice = 1;
      this.editedItem.interview_start_time = null;
      this.editedItem.interview_end_time = null;
      this.editedItem.interview_content = null;
      this.editedItem.interview_date = null;

      this.start_time = true;
      this.end_time = true;
      this.date = true;

      if(this.DB.whole_interview_count == this.interview_schedule.length){
        alert('이미 모든 면접 일정을 등록하셨습니다.');
        return;
      }

      this.editedItem.interview_count = null;
      this.editedItem.interview_count = this.interview_schedule.length + 1;

      //새 일정 등록
      this.divcode = 0; 

      var dialog = this.dialog;
      this.dialog = !dialog;

      // var interviewcount = this.DB.whole_interview_count;
      
      // //alert("실행 " + interviewcount);

      // var former = this.interviewTypecountArr.length;
      // if(this.interviewTypecountArr != [1]){
      //   //alert("not null");
      //   this.interviewTypecountArr = [1]
      // }
      // //총면접 횟수 감소시 감소한 만큼 일정 데이터 삭제
      // alert(interviewcount + " : " + former);
      // if(former > interviewcount){
      //   for(var i = former ; i > interviewcount; i--){
      //     this.interview_schedule.pop();
      //   }
      //   //this.interview_schedule = [];

      // }

      //alert(former +" : " + interviewcount);
      

      // for (var count = 0; count < interviewcount; count++) {
      //   this.interviewTypecountArr[count] = count+1;
      // }
    },

    
    interviewSave(){

      var interviewnum = this.editedItem.interview_count;
      var interview_content_choice = this.editedItem.interview_content_choice;

      var schedule_title = this.editedItem.schedule_title;
      var interview_content = this.editedItem.interview_content;

      if(!this.date){
        var interview_date =  this.editedItem.interview_date;
        var interview_start_time = this.editedItem.interview_start_time == null? "00:00" : this.editedItem.interview_start_time;
        var interview_end_time = this.editedItem.interview_end_time == null? "00:00" : this.editedItem.interview_end_time;
      }else{
        var interview_date = null;
        var interview_start_time = null;
        var interview_end_time = null;
      }
      
      if(this.divcode == 0){
        this.interview_schedule.push({'interview_count' : interviewnum,'schedule_title' : schedule_title, 'interview_content' : interview_content , 'interview_content_choice' : interview_content_choice ,'interview_date' : interview_date, 'interview_start_time' : interview_start_time,'interview_end_time' : interview_end_time});
      }
      else {
        this.interview_schedule.splice(interviewnum-1,1,{'interview_count' : interviewnum,'schedule_title' : schedule_title, 'interview_content' : interview_content , 'interview_content_choice' : interview_content_choice ,'interview_date' : interview_date, 'interview_start_time' : interview_start_time,'interview_end_time' : interview_end_time});
      }
      
      
      var dialog = this.dialog;
      this.dialog = !dialog
    },

    interviewCancel(){
      var dialog = this.dialog
      this.dialog = !dialog
    },

    //면접 수정
    editItem (item) {
      //alert(item + " " + this.interview_schedule[item-1]);
      //this.interview_schedule[item].interview_count = {'interview_count' : interviewnum, 'interview_content_choice' : interview_content_choice}
      this.editedItem.interview_count = this.interview_schedule[item-1].interview_count;
      this.editedItem.interview_content_choice = this.interview_schedule[item-1].interview_content_choice;
      this.editedItem.schedule_title = this.interview_schedule[item-1].schedule_title;

      this.editedItem.interview_start_time = this.interview_schedule[item-1].interview_start_time;
      this.editedItem.interview_end_time = this.interview_schedule[item-1].interview_end_time;
      
      this.editedItem.interview_content = this.interview_schedule[item-1].interview_content;
      this.editedItem.interview_date = this.interview_schedule[item-1].interview_date;
      
      //일정 수정
      this.divcode = 1;

      var dialog = this.dialog;
      this.dialog = !dialog;
      
    },
    //면접 일정 삭제
    deleteItem (item) {
      for(var i = 0; i < this.interview_schedule.length; i++){
        if(item === this.interview_schedule[i].interview_count){
          this.interview_schedule.splice(i, 1)
        }                                                                
      }
    },
    AllClear(){
      this.interview_schedule = [];
    }
  }
} 

</script>
<style>
  .section{
    margin-bottom: 1rem;
    background-color:rgb(223, 255, 223); 
    
  }
  #img {
    /* 150x180 */
    width: 150px;
    height: 180px;
  }
</style>