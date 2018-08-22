<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <v-layout row wrap>
            <!-- <breeding-rhombus-spinner
                :animation-duration="2000"
                :size="80"
                color="#ffa32a"
            /> -->
            <!-- company profile image & recruit status & change profile image-->
            <v-flex d-flex xs12 sm4 md4 lg4>
                <v-card>
                    <v-card-media  :src="companyProfile.photo_url ?
                                         companyProfile.photo_url : '/images/common/profileDefault.jpg'" height="13vw" />
                    <!-- Status Profile -->
                    <v-card-title primary-title class="justify-center headline">                                                                          
                        <div>
                            <h4 class="headline mb-0" style="text-align:center;">
                                {{companyProfile.org_name}}({{companyProfile.org_name_kana}})
                            </h4><hr>     

                            <v-btn round :color="resignPossibility ? 'green' : 'red'" style="color:white; font-weight:bold" >
                                {{resignStatusMessage}}
                            </v-btn>

                        </div>
                    </v-card-title>
                </v-card>
            </v-flex>
            
            <!-- Manager Infomations -->
            <v-flex d-flex xs6 sm3 md3 lg3>
                <v-card>
                    <v-card-title class="justify-center headline" style="font-weight:bold;" >
                        <v-icon color="green" medium>account_circle</v-icon> &nbsp; Manager
                    </v-card-title>
                    <!-- Manager Data-->
                    <v-card-text>
                        <v-layout>
                            <v-flex d-flex xs12 sm12 md12 lg12>
                                <b-form-group>

                                    <b-form-group label-class="font-weight-bold pt-0" label="이름(영문)">
                                        <b-form-input 
                                            type="text"
                                            v-validate="'required'"
                                            placeholder="English Name"
                                            v-model.trim="managerNameEn">
                                        </b-form-input>
                                    </b-form-group>

                                    <b-form-group  label-class="font-weight-bold pt-0" label="이름(카타카나)">
                                        <b-form-input 
                                            type="text"
                                            placeholder="Katakana Name"
                                            v-validate="'required'"
                                            v-model.trim="managerNameKana">
                                        </b-form-input>
                                    </b-form-group>

                                    <b-form-group  label-class="font-weight-bold pt-0" label="Public Email">
                                        <b-form-input 
                                            type="text"
                                            placeholder="Public Email"
                                            v-validate="'required'"
                                            v-model.trim="managerMailAddr">
                                        </b-form-input>
                                    </b-form-group>

                                </b-form-group>
                            </v-flex>
                        </v-layout>
                    </v-card-text>

                    <!-- Change Manager Information Request Btn -->
                    <v-card-actions class="justify-center">
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat="flat" @click="changeManagerInfo()">
                            <v-icon>save</v-icon> &nbsp; 현재 설정 저장
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <!-- Matching Infomations -->
            <v-flex d-flex xs6 sm5 md5 lg5>
                <v-card>
                    <v-card-title class="justify-center headline" style="font-weight:bold;" >
                        <v-icon color="green" medium>playlist_add_check</v-icon> &nbsp; 매칭 정보
                    </v-card-title>

                    <v-card-title>
                        <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="matchSchoolSearch"
                        ></v-text-field>
                    </v-card-title>       

                    <v-data-table
                    :headers="matchListHeaders"
                    :items="matchedSchoolList"
                    :search="matchSchoolSearch"
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props" >
                            <td>{{ props.item.org_agent_name }}</td>
                            <td>{{ props.item.college_name}}</td>
                            <td>
                                <v-btn color="blue" flat="flat" v-if="props.item.employment_decision_ox === 'o'" @click.native.stop="changeEmploymentStatusDialog = true , dialog_type = 'match'"
                                    @click="setChangeStatusData('match',props.item.org_college_id,true,props.item.org_agent_id)">
                                    <v-icon>autorenew</v-icon> 진행중
                                </v-btn>
                                
                                <v-btn color="red" flat="flat" v-else @click.native.stop="changeEmploymentStatusDialog = true , dialog_type = 'match'"
                                    @click="setChangeStatusData('match',props.item.org_college_id,false,props.item.org_agent_id)">
                                    <v-icon>autorenew</v-icon> 미확정
                                </v-btn>
                            </td>
                        </template>
                    </v-data-table>  
                </v-card>
            </v-flex>

            <!-- company Info -->
             <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-expansion-panel>
                        <v-expansion-panel-content>
                            <div slot="header">
                                <v-card-title primary-title class="justify-center headline" style="font-weight:bold;" >
                                    <v-icon color="green" medium>business</v-icon> &nbsp; 회사 정보
                                </v-card-title>
                            </div>

                            <v-card-text>
                                <v-layout>
                                    <v-flex xs12 sm12 md6 lg6>
                                        <v-carousel>
                                            <v-carousel-item v-for="(item,i) in publishImages" :src="item.src" :key="i"></v-carousel-item>
                                        </v-carousel>
                                    </v-flex>
                                    
                                    <v-flex xs12 sm12 md6 lg6>
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>회사명</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.org_name}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>대표</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.ceo_name}}({{companyProfile.ceo_name_kana}})</v-flex>
                                        </v-layout>
                                        
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>지역</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.address_to_dou_hu_ken}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>주소</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.addess}}</v-flex>
                                        </v-layout>
                                        
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>설립일</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.establish_date}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>국가</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.country_code}}</v-flex>
                                        </v-layout>

                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>종업원수</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.worker_count}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>자본금</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.capital}}</v-flex>
                                        </v-layout>

                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>사업분야</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.business_content}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>상장 여부</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{companyProfile.listed_company_ox}}</v-flex>
                                       </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>                
            </v-flex>         

            <!-- Employment Notice List -->
             <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-expansion-panel>
                        <v-expansion-panel-content popout>
                            <div slot="header">
                                <v-card-title primary-title class="justify-center headline" style="font-weight:bold;" >
                                    <v-icon color="green" medium>accessibility</v-icon> &nbsp; 채용 공고
                                </v-card-title>
                            </div>

                            <v-card-title>
                                <v-text-field
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                                v-model="employmentNoticeSearch"
                                ></v-text-field>
                            </v-card-title>    
    
                            <v-data-table
                            :headers="employmentNoticeHeader"
                            :items="employmentNoticeList"
                            :search="employmentNoticeSearch"
                            class="elevation-1"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr @click="getEmployNoticeDetails(props.item.employment_id)" @click.native.stop="employmentNoticeDetailsShow = true">
                                        <td>{{ props.item.college_name }}</td>
                                        <td>{{ props.item.org_agent_name}}</td>
                                        <td>{{ props.item.desired_employee_content}}</td>
                                        <td>{{ props.item.working_area}}</td>
                                        <td>{{ props.item.apply_open_date}}</td>
                                        <td>{{ props.item.apply_deadline_date}}</td>
                                        <!-- employ status-->
                                        <td>
                                            <v-chip outline color="blue" v-if="props.item.employment_decision_ox === 'o'" >진행중</v-chip>
                                            <v-chip outline color="red" v-else>미확정</v-chip>
                                        </td>
                                        <!-- employ notice status chagne -->
                                        <td style="text-align:center;">
                                            <v-chip outline color="green" v-if="props.item.date_result === 'OPEN'">Open</v-chip>
                                            
                                            <v-chip outline color="green" v-else-if="props.item.date_result==='YET'">YET</v-chip>

                                            <v-btn outline color="red" flat="flat" v-else-if="props.item.date_result === 'CLOSE'" 
                                                   @click="setChangeStatusData('notice',props.item.employment_id,true,props.item.org_agent_id)"
                                                   @click.native.stop="changeEmploymentStatusDialog = true , dialog_type = 'notice'">
                                                <v-icon>block</v-icon> End
                                            </v-btn>
                                        </td>
                                    </tr>
                                </template>

                                 <template slot="footer">
                                    <td colspan="100%">
                                        <strong>채용 공고를 선택해주세요.</strong>
                                    </td>
                                </template>

                            </v-data-table>          
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>                
            </v-flex>   

            <!-- Employment Notice Details -->
             <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-expansion-panel>
                        <v-expansion-panel-content popout>
                            <div slot="header">
                                <v-card-title primary-title class="justify-center headline" style="font-weight:bold;" >
                                    <v-icon color="green" medium>accessibility</v-icon> &nbsp; 채용 공고 상세 정보 
                                </v-card-title>
                            </div>
                            <!-- notice Details Information-->
                            <v-card-text v-if="noticeDetails">
                                <v-layout>
                                    <v-flex xs12 sm12 md6 lg6>
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>Theme</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.employment_name}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>DeadLine</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.apply_deadline_date}}</v-flex>
                                        </v-layout>
                                        
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>Detail</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.desired_employee_content}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>Place</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.working_area}}</v-flex>
                                        </v-layout>
                                        
                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>Pay</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.pay_min}} ~ {{noticeDetails.pay_max}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>WorkTime</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.business_hour}}</v-flex>
                                        </v-layout>

                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>Holiday</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.holiday}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>Interview</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.whole_interview_count}}</v-flex>
                                        </v-layout>

                                        <v-layout border>
                                            <v-flex xs2 sm2 md2 lg2 border>College</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.college_name_kana}}</v-flex>

                                            <v-flex xs2 sm2 md2 lg2 border>Agent</v-flex>
                                            <v-flex xs4 sm4 md4 lg4 border>{{noticeDetails.agent_name}} ({{noticeDetails.agent_name_kana}})</v-flex>
                                       </v-layout>                    
                                    </v-flex>
                                    
                                    <v-flex xs12 sm12 md6 lg6>
                                        <v-card-text>
                                            <p class="subheading"> <v-icon color="green">language</v-icon> Welfares </p>
                                            <p class="body">
                                                <v-chip outline color="primary" v-for="item in welfares" :key=item>
                                                    {{item.content}} 
                                                </v-chip>
                                            </p><hr>

                                            <p class="subheading"> <v-icon color="green">mdi-quadcopter</v-icon> Department </p>
                                            <p class="body">
                                                <v-chip outline color="blue" v-for="item in working" :key=item>
                                                    {{item.content}}
                                                </v-chip>
                                            </p>
                                        </v-card-text>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            
                            <v-alert :value="true" color="error" icon="new_releases" class="center" v-else>
                                채용 공고를 선택해주세요.
                            </v-alert>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>                
            </v-flex>           
        </v-layout>

        <v-layout>
            <v-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-tabs
                    v-model="active"
                    color="cyan"
                    dark
                    slider-color="yellow">
                    </v-tabs>
                </v-card>
            </v-flex>
        </v-layout>
    
        <!-- Change Employment Status , Employment Notice Status Dialog -->
        <v-dialog v-model="changeEmploymentStatusDialog" max-width="600">
            <v-card>
                <!-- Dialog Titles -->
                <v-card-title class="justify-center">
                    <p class="headline" v-if="dialog_type === 'match'">  <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; Change Employment Status </p>
                    <p class="headline" v-else-if="dialog_type === 'notice'"> <v-icon color="green darken-1"> autorenew </v-icon>&nbsp; Change Employment Notice Status </p>
                </v-card-title>

                <!-- Message -->
                <v-card-text class="center" v-if="dialog_type === 'match'">
                    <p class="subheading" >  <v-icon color="error">question_answer</v-icon> Really Send Chagne Employment Status Request ? </p>
                </v-card-text>

                <v-card-text v-else-if="dialog_type === 'notice'">
                    <p class="subheading"> <v-icon color="error">question_answer</v-icon> Really Send Chagne Employment Notice Status Request ? </p>
                    
                    <v-card-text v-if="requestTargetCurrentStatus === true">
                        <p class="subheading"> <v-icon color="blue" medium>done</v-icon> Please select new deadline date </p>
                        
                        <v-date-picker
                        full-width
                        landscape
                        class="mt-3"
                        v-model="requestNewDeadline"
                        ></v-date-picker>
                    </v-card-text>
                    <!-- FIXME: get new deadline date -->
                </v-card-text>

                <v-card-actions class="justify-center">
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-3" flat="flat" @click.native="changeEmploymentStatusDialog = false">
                        <v-icon dark right>block</v-icon>&nbsp; Disagree
                    </v-btn>

                    <!-- request button -->
                    <v-btn v-if="dialog_type === 'match'" color="green darken-1" flat="flat" @click="sendChangeStatusRequest('match')" 
                           @click.native=" changeEmploymentStatusDialog = false ">
                        <v-icon>autorenew</v-icon> &nbsp; Send Request
                    </v-btn>

                    <v-btn v-else-if="dialog_type === 'notice'" color="green darken-1" flat="flat" @click="sendChangeStatusRequest('notice')" 
                           @click.native=" changeEmploymentStatusDialog = false ">
                        <v-icon>autorenew</v-icon> &nbsp; Send Request
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { BreedingRhombusSpinner } from 'epic-spinners';

/**
 * @author JyunSoo Jang
 */
    export default {
        components : { BreedingRhombusSpinner },
        data(){
            return {
                /**
                 * @desc show controller => loading spinner , change employment dialog , employment notice details 
                 */
                spinnerVisible : false,                
                changeEmploymentStatusDialog : false,    
                employmentNoticeDetailsShow  : false, 

                /**
                 * @desc manager info
                 */
                managerNameEn   : null,
                managerNameKana : null,
                managerMailAddr : null,                        

                /**
                 * @desc resing possibility's current status & message list for create chips
                 */
                resignPossibility        : false,           
                resignStatusMessage      : "",              
                resignStatusMessageList  :
                { 
                    true  : "Resign Possible" ,
                    false : "Regisn Impossible" 
                },
                
                /**
                 * @desc get data from server using axios at created function
                 */
                companyProfile       : null,            
                employmentNoticeList : null,
                matchedSchoolList    : null,
                publishImages        : null,
                noticeDetails        : null,
                welfares             : null,
                working              : null,

                /**
                 * @desc matching data => table , search
                 */
                matchSchoolSearch    : "",
                matchListHeaders     :
                [ 
                    { text : "Agent"             , align : "left"   , value : "college_name" },
                    { text : "College"           , align : "left"   , value : "org_name" },
                    { text : "Employment Status" , align : "center" , value : "employment_decision_ox" },
                ],

                /**
                 * @desc employment notice data => table,
                 */
                employmentNoticeSearch    : "",
                employmentNoticeHeader    : 
                [   
                    { text : "College"           , align : "left"     , value : "college_name" },
                    { text : "Agent"             , align : "left"     , value : "org_agent_name" },
                    { text : "Department"        , align : "left"     , value : "desired_employee_content" },
                    { text : "Working Area"      , align : "left"     , value : "working_area" },
                    { text : "Apply Open"        , align : "left"     , value : "apply_open_date" },
                    { text : "Apply DeadLine"    , align : "left"     , value : "apply_deadline_date" },
                    { text : "Employment Status" , align : "left"     , value : "employment_decision_ox" },
                    { text : "Apply Progress"    , align : "center"   , value : "employment_owari_ox" },
                ],

                dialog_type   : null,
                requestType   : null,
                requester     : null,
                requestTarget : null, 
                requestNewDeadline : null,
                requestTargetAgent : null,
                requestTargetCurrentStatus : null,
            }
        },

        methods : {        
            /**
             * @desc set data for change status (employment , notice)
             * @param { String String Bool String String }
             */
            setChangeStatusData : function ( type, targetId , status , agent ){
                
                this.requestType   = type;
                this.requester     = this.$store.getters.identification;
                this.requestTarget = targetId;
                this.requestTargetAgent = agent;
                this.requestTargetCurrentStatus = status;
            },
            
            /**
             * @desc send request for change status / requestType Check -> data assignment -> send request  
             * @param { String } requestType
             * @return request result
             */
            sendChangeStatusRequest : function (requestType){
                
                if( !(this.requestType == requestType) ){
                    alert(";;");
                    return;
                }

                let reqHttpAddr = null;
                let sendData    = { requester  : this.requester, 
                                    agentId    : this.requestTargetAgent,
                                    status     : this.requestTargetCurrentStatus }

                if( requestType === 'match' ){
                    reqHttpAddr = "/companyMatchingSwitchNotice";
                    
                    sendData.schoolId = this.requestTarget 
                }
                else if( requestType === 'notice' ){
                    reqHttpAddr = "/employmentSwitchNotice";
 
                    sendData.newDeadline  = this.requestNewDeadline,    
                    sendData.noticeId     = this.requestTarget 
                }

                console.log(sendData);

                this.axios.post( reqHttpAddr, sendData ).then( res => {
                    
                    if( !res.data ) return;
                    console.log(res.data)
                }).catch( err => {
                    console.log(err.message)
                })
            },

            /**
             * @desc get employment notice details
             * @param  { String } selectedNoticeId
             * @return { Array[Object] } selected notice details
             */
            getEmployNoticeDetails : function (selectedNoticeId){

                let reqHttpAddr = "/employment_details";
                let sendData    = { selectedNotice : selectedNoticeId }

                this.axios.post( reqHttpAddr, sendData ).then( res => {
                    
                    if( !res.data ) return;
                    console.log(res.data)
                    this.noticeDetails = res.data.employment_detail_info[0];
                    this.welfares      = res.data.welfare_info;
                    this.working       = res.data.work_info;
                }).catch( err => {
                    console.log(err.message)
                })
            },
            
            /**
             * @desc change manager name(en , kana) , public email address
             * @argument  { String String String }
             * @return    result
             */
            changeManagerInfo : function () {
                
                if( !this.managerNameEn || !this.managerNameKana || !this.managerMailAddr ){
                    alert("please input all field");
                    return;
                }

                let reqHttpAddr = "/profileInfoChange";
                let sendData    = { requester : this.$store.getters.identification,
                                    nameEn    : this.managerNameEn   ,
                                    nameKana  : this.managerNameKana ,
                                    emailAddr : this.managerMailAddr }

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if( !res.data ) {
                        alert("fail Change");
                        return;
                    }else if( !res.data == 2){
                        alert("Can't change same Data");
                        return;
                    }

                    alert("Success Change");
                    
                }).catch( err => {  
                    console.log(err.message);
                })
            },         

            createCarouselImageObj : function (imgArray){
                let temp = { src : null };
                let imgObjArray = [];

                for(let i = 0 ; i < imgArray.length ; i++){
                    temp.src = imgArray[i];
                    imgObjArray.push(temp);
                }

                this.publishImages = imgObjArray;
                console.log(this.publishImages);
            }
        },
         /**
         * @desc      get company info that registrated by agent
         * @argument  { String } currentCompanyIdentification
         * @return    { Array [Object] } company info and profile image's path
         */
        created : function (){

            //FIXME: co01 -> this.$store.state.identification
            let reqHttpAddr = "/companyprofile";
            let sendData    = { requester : this.$store.getters.identification }
            
            console.log(sendData);

            this.axios.post(reqHttpAddr,sendData).then( res => {

                if( !res.data )  return false;
        
                this.companyProfile   = res.data.comInfo[0];
                this.managerNameEn    = res.data.comInfo[0].name;
                this.managerNameKana  = res.data.comInfo[0].name_kana;
                this.managerMailAddr  = res.data.comInfo[0].email;
                
                this.employmentNoticeList = res.data.employment_list_info;
                this.matchedSchoolList    = res.data.matching_school_info;

                if(res.data.comInfo[0].acceptance_fixed_ox === "x"){
                    this.resignPossibility   = false;
                    this.resignStatusMessage = this.resignStatusMessageList.false;
                }else{
                    this.resignPossibility   = true;
                    this.resignStatusMessage = this.resignStatusMessageList.true;
                }

                this.createCarouselImageObj(res.data.company_publish_images);

            }).catch( err => {
                console.log(err.message);
            });
        },
    }
</script>