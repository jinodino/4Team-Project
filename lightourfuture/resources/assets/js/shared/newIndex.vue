<template>
    <div class="container_wrapper">
        <div class="header-background">          
            <!-- grid 3 section -->
            <div class="message-box">
                <!-- Service Name -->
                <div id="service-title">
                    Light Our Future <hr>
                </div>
                
                <!-- Sub-Message -->
                <div id="sub-message">                 
                    世界中、どこでも                    
                </div>

                <!-- Language && Link Box grid 5-->
                <div class="languages-and-link-box">
                    <!-- change Language Setting -->  
                    <div v-for="(value ,key) in languagePacks" class="lang-setter-linker" :key="key">
                        <a @click.prevent="handleClick_changeLangSet(key)">              
                            <img :src="value[1]">  
                            <p>{{value[0]}}</p>     
                        </a>                        
                    </div>  

                    <!--FaceBook link-->
                    <div class="lang-setter-linker">
                        <a href="https://www.facebook.com/takashi.yano">
                            <img :src="faceBookIcon" >
                            <p> FaceBook </p>
                        </a> 
                    </div>  

                    <!--Halo link-->
                    <div class="lang-setter-linker">
                        <a href="http://halo.co.jp">
                            <img  :src="halo">
                            <p> Halo</p>
                        </a>
                    </div>

                </div>                
            </div> <!-- Message Box end -->          
        </div> <!-- Header Box End -->

        <!-- user Login Box -->
        <div class="user-info-provide">
            <div class="provides-box">
                <!-- login buttons grid 4 -->
               <div class="login-btns">

                    <!-- Set Current User Type Image button Div-->                    
                    <div class="login-btn" v-for="(imgSrc, userType) in allUserTypes" :key="userType"
                    @click="handleClick_setCurrentUserClassification(userType)" 
                    @click.stop="loginModal = ! loginModal"
                    :style="{backgroundImage : `url(${imgSrc})`}">
                        <!-- Show UserType & Welcome Message-->
                        <div class="login-text-box"> 
                            <h1> {{userType}} </h1>
                            <hr><br>
                            <p> {{$t("Index.welcomeMessage." + userType)}} </p>                            
                        </div> 
                    </div>
                </div>
            </div>      
        </div><!--user-info-provide Box End-->

        <!--Footer Developer Info-->
        <div class="footer">
            <div class="footer-project-info"> 
                <div style="text-align:center; margin : 5% 0;"> 
                    <p style="color:white; size:25vw;"> Title </p>
                    <p style="color:white; size:25vw;"> 장준수 </p>
                    <p style="color:white; size:25vw;"> 이효진 </p>
                    <p style="color:white; size:25vw;"> 장다연 </p>
                    <p style="color:white; size:25vw;"> 류호형 </p>
                    <p style="color:white; size:25vw;"> 조수진 </p>
                    <p style="color:white; size:25vw;"> 손진호 </p>
                </div>
                <div style="color:white; text-align:center; margin : 5% 0;">
                    <p>contributer</p>
                </div>
                <div> fasg</div>
                <div> fasg</div>
                <div> fasg</div>    
            </div>

            <div style="text-align:center; color:grey;">
                © 2018 YJC J-Class Team 4, all rights reserved. Made with  for a better future. 
            </div>        
        </div>
        
        <!-- Modal -->
        <v-app style="width:0; height:0;">
           
            <!-- Modal : Login Form -->
            <v-dialog v-model="loginModal" persistent  width="500px" class="dialogs">
                <v-card>
                    <!-- Input Fields-->
                    <v-container grid-list-sm class="pa-4">
                        <v-layout row wrap>
                            <v-form ref="loginForm"  lazy-validation>
                                <!-- ID -->
                                <v-flex xs12>
                                    <v-text-field 
                                        :rules="[v => !!v || $t('Index.validate_required_message')]"                                         
                                        v-model="loginInfo.id" 
                                        label="Account" 
                                        type="text" required />
                                </v-flex>
                                <!-- Pwd -->
                                <v-flex xs12>
                                    <v-text-field 
                                        :rules="[v => !!v || $t('Index.validate_required_message') ]"                                                 
                                        v-model="loginInfo.pwd" 
                                        label="Password" 
                                        type="password" required />
                                </v-flex>
                            </v-form>
                        </v-layout>
                    </v-container>

                    <!-- Option Buttons -->
                    <v-card-actions>
                        <v-spacer/>       
                        <!-- Sign In -->
                        <v-btn class="basicBtn" color="success" @click="login()">
                            {{ $t( "Index.loginMessage.signIn" )}}
                        </v-btn>
                        <!-- Sign In Modal Close -->
                        <v-btn class="basicBtn" @click="loginModal = false, clear('login')" >
                            Cancel
                        </v-btn>        

                        <!--  Find Id / Pwd Modal-->
                        <v-btn class="basicBtn" @click.stop="findAccountModal = !findAccountModal">
                            {{ $t("Index.loginMessage.findAccount" )}}
                        </v-btn>

                        <!-- show Join Us Modal when current User is Student -->
                        <v-btn class="basicBtn" v-if=" currentUserType == 'student' " @click.stop="signUserModal = !signUserModal">
                            {{ $t("Index.loginMessage.signUs")}}
                        </v-btn>
                        <v-spacer/>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Modal : sign Us Form -->
            <v-dialog v-model="signUserModal" persistent justify-center width="500px">
                <v-card>
                    <!--Title-->
                    <v-card-title>
                        {{ $t("Index.loginMessage.signUs" )}}
                    </v-card-title>
                    
                    <!-- Input Fields-->                    
                    <v-container grid-list-sm class="pa-4">
                        <v-layout row wrap>
                            <v-form ref="register" lazy-validation>
                                
                                <!-- validate_num -->
                                <v-text-field  
                                    :label="$t('Index.regist.code')"
                                    type="text" 
                                    v-model="validate_assignedStdInfo.validate_num"
                                    :rules="[v => !!v || $t('Index.validate_required_message')]" 
                                    required>
                                </v-text-field>

                                <!-- Name -->
                                <v-text-field  
                                    :label= "$t('Index.regist.name')"
                                    type="text" 
                                    v-model="validate_assignedStdInfo.name" 
                                    :rules="[v => !!v || $t('Index.validate_required_message')]" 
                                    required>
                                </v-text-field>
                            
                                <!-- date of birth -->
                                <v-layout row>
                                    
                                    <v-flex xs6>
                                        <v-text-field 
                                            :label="$t('Index.regist.birth')"
                                            name="birth" 
                                            persistent-hint hint="Ex) 971001" 
                                            type="text" 
                                            :rules="[v => !!v || 'compulosry field',
                                                    v => /^([0-9]+)$/.test(v) || $t('Index.validate_required_message')] " 
                                            v-model="validate_assignedStdInfo.birth">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 order-lg2>
                                        <v-btn flat small color="blue" @click="isAssigned_std()">
                                            {{$t("Index.regist.assigned_check_btn")}}
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                        
                            <v-form v-if="register_validateItems.assingedStd" ref="register" lazy-validation>
                                <!-- E-mail -->
                                <v-layout row>
                                    <v-flex xs6 >
                                        <v-text-field 
                                            v-model="register_info.email" 
                                            :label="$t('Index.regist.email')" 
                                            v-validate="'required|email'" 
                                            data-vv-name="email" 
                                            :rules="[v => !!v || $t('Index.validate_required_message')]"
                                            required>
                                        </v-text-field>
                                    </v-flex>    

                                    <span class="errorSpanSt" v-if="errors.has('email')"> {{ $t('Index.error_mailAddr') }} </span>

                                    <v-flex v-else xs6 order-lg2>
                                        <v-btn flat small color="blue" @click="sendAuthVerifyNum_toMail()">
                                            {{$t("Index.regist.sendMail_btn")}}
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <!-- authentication_number -->
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-text-field 
                                            name="number" 
                                            :rules="[v => !!v || $t('Index.validate_required_message')]" 
                                            persistent-hint hint="Ex) 971001" 
                                            :label="$t('Index.regist.authNum')"
                                            type="text" 
                                            v-model="register_info.auth_num">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 order-lg2>
                                        <v-btn flat small color="blue" @click="isValidate_authNum()">
                                            {{$t("Index.regist.confirmCode_btn")}}
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <!-- userID -->
                                <v-layout row>
                                    <v-flex xs6>
                                        <v-text-field 
                                        :label="$t('Index.regist.id')"
                                        type="text" 
                                        v-model="register_info.id" 
                                        :rules="[v => !!v || $t('Index.validate_required_message')]"/>
                                    </v-flex>
                                    <v-flex xs6 order-lg2>
                                        <v-btn flat small color="blue" @click="isDuplicated_id()">
                                            {{$t("Index.regist.duplicateCheck_btn")}}
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <!-- userPwd -->
                                <v-flex xs12>
                                    <v-text-field 
                                        v-validate="'required'" 
                                        name="password"
                                        :label="$t('Index.regist.pwd')" type="password" 
                                        v-model="register_info.pwd" 
                                        :rules="[v => !!v || $t('Index.validate_required_message') ]"/>
                                    <v-text-field  
                                        v-validate="'required|confirmed:password'" 
                                        name="password_confirmation" 
                                        :label="$t('Index.regist.repeat_pwd')" type="password" 
                                        v-model="register_info.pwd_Check" 
                                        :rules="[v => !!v || $t('Index.validate_required_message')]"/>
                                    <div v-show="errors.any()">
                                        <span  v-if="errors.has('password_confirmation')">
                                            {{ $t("Index.error_password") }}
                                        </span>
                                    </div>
                                </v-flex>
                            </v-form>
                        </v-layout>
                    </v-container>

                    <!-- Option Buttons -->
                    <v-card-actions>
                        <v-spacer/>                 
                        <v-btn class="basicBtn" @click="signUserModal = false, clear('register')">
                            Cancel
                        </v-btn>  
                        <v-btn class="basicBtn" @click="register_std()">
                            {{ $t("Index.regist.regist")}}
                        </v-btn>
                        <v-spacer/>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- Modal : Find Id / Pwd -->
            <v-dialog v-model="findAccountModal"  width="500px" persistent>
                <v-tabs color="white" light slider-color="red">
                    <!-- Find Id -->
                    <v-tab ripple :key="1">
                        findID
                    </v-tab>
                
                    <v-tab ripple :key="2">
                        findPwd
                    </v-tab>
                    <v-tab-item :key="1">
                        <v-card> 
                            <v-container grid-list-sm class="pa-4">
                                <v-layout row wrap>
                                    <v-form ref="findIdForm" lazy-validation> 
                                        <!-- user date of birth -->
                                        <v-flex xs12>
                                            <v-text-field 
                                                name="findbirth" 
                                                persistent-hint hint="Ex) 971001" 
                                                :label="$t('Index.findAccountMessage.birth')" 
                                                type="text" 
                                                v-model="findId_confirmData.birth" 
                                                :rules="[ v => !!v || 'compulsory field',
                                                          v => /^([0-9]+)$/.test(v)
                                                           || $t('Index.validate_required_message')]">
                                            </v-text-field>
                                        </v-flex>
                                        <!-- user Name -->
                                        <v-flex xs12>
                                            <v-text-field 
                                                :label="$t('Index.findAccountMessage.name')" 
                                                type="text" 
                                                v-model="findId_confirmData.name" 
                                                :rules="[v => !!v || $t('Index.validate_required_message')]">
                                            </v-text-field>
                                        </v-flex>
                                        <!-- user E-mail -->
                                        <v-flex xs12>
                                            <v-text-field 
                                                v-validate="'required|email'" 
                                                data-vv-name="findEmail" 
                                                :label="$t('Index.findAccountMessage.email')" type="email" 
                                                v-model="findId_confirmData.emailAddr" 
                                                :rules="[v => !!v || $t('Index.validate_required_message') ]">
                                            </v-text-field>
                                            <span class="errorSpanSt" v-if="errors.has('findEmail')"> {{ $t('Index.error_mailAddr') }}</span>                                    
                                        </v-flex>
                                        <v-flex xs12>
                                            <span>{{foundedId}}</span>
                                        </v-flex>
                                    </v-form>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-spacer/>
                                <v-btn class="basicBtn" flat color=this.theme.primary @click="findAccountModal = false, clear('findId')">Cancel</v-btn>
                                <!-- Remote by : Main Page of CurrentUser's -->
                                <v-btn class="basicBtn" flat @click="recover_id()">
                                    {{ $t("Index.findAccountMessage.find") }}
                                </v-btn><br>
                                <v-spacer/>
                            </v-card-actions>
                        </v-card> 
                    </v-tab-item>

                    <!-- Find Pwd -->
                    <v-tab-item :key="2">
                        <v-card> 
                            <v-container grid-list-sm class="pa-4">
                                <v-layout row wrap>
                                    <v-form ref="findPwdForm" lazy-validation>
                                        <!-- user date of birth -->
                                        <v-flex xs12>
                                            <v-text-field
                                                persistent-hint hint="Ex) 971001" 
                                                :label="$t('Index.findAccountMessage.birth')" 
                                                type="text"
                                                v-model="findPwd_confirmData.birth" 
                                                :rules="[v => !!v || 'compulsory field',
                                                         v => /^([0-9]+)$/.test(v) || $t('Index.validate_required_message')] " >
                                            </v-text-field>
                                        </v-flex>
                                        <!-- user Name -->
                                        <v-flex xs12>
                                            <v-text-field                                               
                                                :label="$t('Index.findAccountMessage.id')" 
                                                type="text"
                                                v-model="findPwd_confirmData.id" 
                                                :rules="[v => !!v || $t('Index.validate_required_message')]">
                                             </v-text-field>
                                        </v-flex>
                                        <!-- user E-mail -->
                                        <v-flex xs12>
                                            <v-text-field 
                                                v-validate="'required|email'"
                                                data-vv-name="findEmail"                                                         
                                                :label="$t('Index.findAccountMessage.email')" type="email" 
                                                v-model="findPwd_confirmData.emailAddr" 
                                                :rules="[v => !!v || $t('Index.validate_required_message')]">
                                            </v-text-field>
                                            <span class="errorSpanSt" v-if="errors.has('findEmail')"> {{ $t('Index.error_mailAddr') }}</span>
                                        </v-flex>
                                    </v-form>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-spacer/>
                                <v-btn class="basicBtn" flat color=this.theme.primary @click="findAccountModal = false, clear('findPwd')">Cancel</v-btn>
                                <!-- Remote by : Main Page of CurrentUser's -->
                                <v-btn class="basicBtn" flat @click="recover_pwd()">
                                    {{ $t("Index.findAccountMessage.find") }}
                                </v-btn><br>
                                <v-spacer/>
                            </v-card-actions>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-dialog>
        </v-app>
    </div>           
</template>


<style lang="css" src="../static/css/Index/Index.css"></style>
<script>

export default{ 

    data(){
        return {      
           /** 
            *  @author DaYoen Jang 
            *  @author JunSu  Jang
            */

            //SNS & Halo Icon Image Src
            faceBookIcon : "/images/Index/facebook.png",
            halo         : "/images/Index/halo.png",         

            // user Types 
            allUserTypes : {
                company     : "/images/Index/company.jpg",
                professor   : "/images/Index/professor.jpg",
                student     : "/images/Index/student.jpg",
                agent       : "/images/Index/agent.jpg"
            },

            // language Packs list
            languagePacks : {
                "en" : [ "English" , "/images/Index/usa.png"],  
                "ja" : [ "日本語"   , "/images/Index/japan.png"],
                "kr" : [ "한글"     , "/images/Index/korea.png" ]
            },

            //Open Modal Status
            loginModal          : false,
            signUserModal       : false,
            findAccountModal    : false,

            currentUserType     : "",   // professor , company , agent , student
            foundedId           : null,   // @function recover_id's result value     
            
            //credential for register
            register_validateItems : {
                duplicateId     : false,
                mailAddr        : false,                
                assingedStd     : false
            },            
      
            //for sign user info
            register_info : {
                email        : "",
                auth_num     : "",
                id           : "",
                pwd          : "",
                pwd_Check    : "" ,
                classification : "",          
            },

            //for MemberCheck info
            validate_assignedStdInfo : {
                validate_num    : "",
                birth           : "",
                name          : "",
                classification  : "",
            },

            //for findId info
            findId_confirmData : {
                name            : "",
                emailAddr       : "",
                birth           : "",
                classification  : ""
            },
            //for findPwd info
            findPwd_confirmData : {
                id              : "",
                emailAddr       : "",
                birth           : "",
                classification  : ""
            },                 
          
            //for loginfo
            loginInfo : {
                id             : "",   
                pwd            : "",
                classification : "",
            },
        }
    },
   
    methods:{

        /** @function
         *  @description  @fileOverview        set userType / login / set locale / regist new student account / fine ID || Password
         *                                     All of methods have userData validation 
         *  
         * 
         *  handleClick_setCurrentUserType     select your type with login button                   @param { String } userType 
         *  handleClick_changeLangSet          change current vue-18n locale set with image button  @param { String } lang 
         *                                              
         *  login                              create token              @argument { Object } loginInfo @return {String} token,id,classification   
         *  recover_id                         show id current dialog    @argument { Object } findID_ComfirmData   name birth mailValidate
         *  recover_pwd                        send password set-up url  @argument { Object } findPwd_ComfirmData  id   birth mvaliValidate
         * 
         *  register_std                       new student register   
         *      isAssigned_std                 assigned check from professor input data @argument { Object } validate_assignedStdInfo
         *      sendAuthVerifyNum_toMail       send auth num to mail                    @argument { String } mailAddr > register_info.email
         *      isValidate_authNum             sended auth num validation               @argument { String } authNum  > register_info.auth_num
         *      isDuplicated_id                checking duplicated id                   @argument { String } id       > register_info.id
         *  
         *  clear                              clear dialog form input fields           @param { String } formIdentifier
         *  validate_nullFields                check all field and find null value      @param { Object } data  @return { Bool }
         *  show_progress                      show current request's progress          
         *  custom_alert                       show alert according to request result   @param { Boolean , String, String } @return show alert
         * 
         * TODO:
         *  - [x] user Validation checking when redirection
         *  - [x] vuex store -> save User validation status
         */
        
        handleClick_setCurrentUserClassification : function (userType){   
            this.currentUserType = userType;
        }, 

        handleClick_changeLangSet : function (lang){
            this.$i18n.locale = lang;
        },
        
        login : function(){        
            
            let validate = this.$refs.loginForm.validate();            
            if( !validate ) return false;

            //add Classification
            this.loginInfo.classification = this.currentUserType;

            let reqHttpAddr = '/api/auth/login'; 
            let sendData    = { login_id        : this.loginInfo.id, 
                                password        : this.loginInfo.pwd, 
                                classification  : this.loginInfo.classification }

            this.axios.post( reqHttpAddr , sendData ).then( res => {
                
                if( !res.data ){
                    this.custom_alert(false,"Login Faild", "Please Confirm Yout Id And Password");
                    return false;
                }

                this.custom_alert(true,"Login","Success");
                
                //set global user state                
                let vuexPayload = { id : this.loginInfo.id,
                                    classy : this.loginInfo.classification,
                                    accessToken : res.data }

                this.$store.dispatch("login",vuexPayload);
                
                //redirect to user dashboard
                this.$router.push("/" + this.loginInfo.classification);

            }).catch( err => {
                console.log(err.message)
            })
        },

        recover_id : function(){
            //Field null value
            let validate = this.$refs.findIdForm.validate();
           
            if ( !validate ) return false;  
            
            this.findId_confirmData.classification = this.currentUserType;

            let reqHttpAddr = "umc/find/id";
            let sendData    = { find_idInfo : JSON.stringify(this.findId_confirmData) };
            
            this.axios.post(reqHttpAddr, sendData).then( res => {

                if(!res.data[0]){
                    this.custom_alert(false, "Find Id", "Not Valid Dataset");
                    return;
                }
                
                this.foundedId = res.data;
                    
            }).catch((err)=> {
                console.log(err.message)
            });
            
        },
        
        // pwd find request
        recover_pwd : function(){
            //Field null value
            let validate = this.$refs.findPwdForm.validate();
           
            if ( !validate ) return false;

            this.findPwd_confirmData.classification = this.currentUserType;
            
            let reqHttpAddr = "umc/find/pwd";
            let sendData    = { find_pwdInfo : JSON.stringify(this.findPwd_confirmData) };

            this.axios.post(reqHttpAddr,sendData).then( (res) => {
           
                console.log(res.data)
                alert(res.data);
                if(res.data.key){
                    alert("메일 전송 완료");
                    //alert("메일 전송 완료");
                }else{
                    //alert("일치하는 사용자가 없습니다.");
                }

            }).catch( (err) => {
                console.log(err.message);
            }); 
        },
        
        register_std : function(){

            let validate = this.$refs.register.validate();
            if( !validate )  return false;
            
            //ConfirmList Check > isAssigned_std / isValidate_authNum(mail) / isDuplicated_id
            for(let key in this.register_validateItems){
                if( !this.register_validateItems[key] ){
                    alert("validate not complete!!!");
                    return false;
                }
            }
            
            let reqHttpAddr = "/postStudentEnter";
            let sendData    = { registinfo        : JSON.stringify(this.register_info),
                                validate_stdInfo  : JSON.stringify(this.validate_assignedStdInfo) };

            this.axios.post(reqHttpAddr, sendData).then( (res) => {
                
                if( !res.data ){
                    alert("다시 확인하세요");
                    return;
                }
                
                alert('회원가입 완료');
                //location.reload(); // 재시작
                
            }).catch((err)=> {
                console.log(err.message);
            });
        },
 
        isAssigned_std : function(){
            //Field null value
            this.validate_assignedStdInfo.classification = this.currentUserType;
            let fields_nullCheck = this.validate_nullFields(this.validate_assignedStdInfo);
            if( !fields_nullCheck ) return false;
            
            let reqHttpAddr = "/postmembercheck";
            let sendData    = { validate_stdInfo  : JSON.stringify(this.validate_assignedStdInfo)};

            this.axios.post(reqHttpAddr, sendData).then( (res) => {
            
                if(/*res.data.key*/ res.data){
                    this.register_validateItems.assingedStd = true;
                    alert("학생 인증 성공");
                }else{
                    alert("일치하는 학생이 없습니다.");
                }
            }).catch( (err) => {
                console.log(err.message);
            });

        },
        
        sendAuthVerifyNum_toMail  : function (){      

            if( !this.register_info.email ){
                alert("input Email Address");
                return false;
            }
            
            let reqHttpAddr = "/postmailcheck/send";
            // 05 01 12:49 진호 고침
            let sendData    = { mailAddr : this.register_info.email, validate_stdInfo  : JSON.stringify(this.validate_assignedStdInfo) };

            this.axios.post(reqHttpAddr, sendData).then( (res) => {
                alert("메일발송");
            }).catch((err)=> {
                console.log(err.message);
            });
        },

        isValidate_authNum : function (){

            if( !this.register_info.auth_num ){
                alert("인증번호 입력");
                return false;
            }
            
            let reqHttpAddr = "/postmailcheck/mailconfirmcodecheck";
            let sendData    = { authNum : this.register_info.auth_num, validate_stdInfo  : JSON.stringify(this.validate_assignedStdInfo) }; 

            this.axios.post(reqHttpAddr, sendData).then( (res) => {
                console.log("in")
                if(res.data){
                    alert("인증완료!");
                    this.register_validateItems.mailAddr = true;
                }else{
                    alert("잘못된인증번호");
                }
            }).catch((ex)=> {
                console.log('failed',ex);
            });
        },

        isDuplicated_id : function(){

            if(!this.register_info.id){
                alert("id입력");
                return false;
            }
            
            let reqHttpAddr = "/postUserIdDuplicates";
            let sendData    = { id  : this.register_info.id };

            this.axios.post(reqHttpAddr, sendData).then( (res) => {
                if(!res.data){
                    alert("사용중인ID입니다. 다른 아이디를 이용해 주세요");
                }else{
                    alert("사용가능ID입니다.");
                    this.register_validateItems.duplicateId = true;
                    
                }
            }).catch((err)=> {
                console.log(err.message);
            });
        },        
        
        clear : function(formIdentifier){    
            switch (formIdentifier) {
                case "login"    : this.$refs.loginForm.reset();     break;
                case "register" : this.$refs.register.reset();      break;
                case "findId"   : this.$refs.findIdForm.reset();    break;
                case "findPwd"  : this.$refs.findPwdForm.reset();   break;     
            }
        },
        
        validate_nullFields : function (data) {
            for( let key in data ) {                       
               if( !data[key] )   return false;     
            }
            return true;
        },

        show_progress : function () {
            this.$vs.loading()
            setTimeout( ()=> {
                this.$vs.loading.close()
            }, 2000);
        },

        custom_alert : function ( flag=true ,title, message ) {
            //set default success color
            let alert_color   = "rgba(0,0,200,0.9)";
            //change danger color when bool value is false
            if( !flag ) color = "rgba(200,0,0,0.8)";
            
            this.$vs.alert({
                title : title,
                text  : message,
                color : alert_color
            })
        },
        // this.axios.get("https://ipinfo.io").then( res => {
        //     console.log(res.city, res.country);
        // })
    },
    created : function () {

        this.$store.dispatch("clearing");
        // if( this.$store.getters.accessToken ) {
        //     if( this.$store.dispatch("me",this.$store.getters.accessData) ){
        //         this.$router.push("/" + this.$store.getters.classification);
        //     }
        // }
    }
    
}
</script>