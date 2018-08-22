<template>
    <div class="wrap-cont">
        <div>
            <pageHeader/>
        </div>
        
        <div class="stepper">
            <v-app>
                <v-container>
                <v-stepper v-model="stage" alt-labels>
                    <v-stepper-header>
                        <v-stepper-step :complete="stage > 1" step="1"> Student Verify </v-stepper-step> <v-divider/>
                        <v-stepper-step step="2"> Create Account   </v-stepper-step>
                    </v-stepper-header>
                        
                    <v-stepper-items>
                        <!-- step 1 -->
                        <v-stepper-content step="1">
                            <div class="data-field">
                                <b-form-group label-class="font-weight-bold pt-0" label="Professor Account">
                                    <b-form-input 
                                        size="lg"
                                        type="text"
                                        v-model.trim="stdVerify.collegeCode">
                                    </b-form-input>
                                </b-form-group>

                                <b-form-group  label-class="font-weight-bold pt-0" label="Name">
                                    <b-form-input 
                                        size="lg"
                                        type="text"
                                        v-model.trim="stdVerify.name">
                                    </b-form-input>
                                </b-form-group>

                                <b-form-group  label-class="font-weight-bold pt-0" label="Birth">
                                    <b-form-input 
                                        size="lg"
                                        type="text"
                                        placeholder="Ex) 1994.10.10 => 941017"
                                        v-model.trim="stdVerify.birth">
                                    </b-form-input>
                                </b-form-group>
                            
                                <b-form-group  label-class="font-weight-bold pt-0" label="Email Address">
                                    <b-form-input 
                                        size="lg"
                                        type="text"
                                        v-model.trim="stdVerify.emailAddress">
                                    </b-form-input>
                                </b-form-group>
                                
                                 <br>
                                <v-btn color="primary" @click="isAssignedStd()">Verify</v-btn>
                                <v-btn flat @click="redirectMain()">Cancel</v-btn>
                            </div>
                        </v-stepper-content>
                        <!-- step 2 -->
                        <v-stepper-content step="2">
                            <div class="data-field">

                                <b-form-group  label-class="font-weight-bold pt-0" label="Verify Number">
                                    <b-form-input 
                                        size="lg"
                                        type="text"
                                        v-model.trim="createAccount.credential">
                                    </b-form-input>
                                </b-form-group> 
                                
                                <b-form inline>
                                    <b-form-group label-class="font-weight-bold pt-0" label="ID">
                                        <b-form-input 
                                            size="lg"
                                            type="text"
                                            :state="verifies.duplicateId"
                                            v-model.trim="inputId">
                                        </b-form-input>
                                        <v-btn color="success" @click="isDuplicated()">Check Duplicate</v-btn>
                                    </b-form-group>
                                </b-form>

                                <b-form-group  label-class="font-weight-bold pt-0" label="Password">
                                    <b-form-input 
                                        size="lg"
                                        type="password"
                                        v-model.trim="createAccount.pwd">
                                    </b-form-input>
                                </b-form-group>

                                <b-form-group  label-class="font-weight-bold pt-0" label="Check Password">
                                    <b-form-input 
                                        size="lg"
                                        type="password"
                                        :state="pwdVerifyStatus"
                                        v-model.trim="pwdCheck">
                                    </b-form-input>
                                </b-form-group> <br>
                                <v-btn color="primary" @click="createNewAccount()">Verify</v-btn>
                                <v-btn flat @click="redirectMain()">Cancel</v-btn>
                            </div>
                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>
            </v-container>    
            </v-app>      
        </div>
    </div>
</template>

<style scoped lang="css" src="../static/css/common/StdRegist.css"></style>
<script>

    import pageHeader from "./RogoHeader.vue";

    export default {

        components : {
            pageHeader
        },

        data(){
            return {
                stage : 0 ,
                loaderBackgroundColor : "rgba(255,255,255,0.8)",

                /**
                 * @desc verify missions
                 */
                verifies : {
                    stdIdentify  : false,
                    emailAddress : false,
                    duplicateId  : false,
                },

                stdVerify : {
                    collegeCode  : null,
                    name         : null,
                    birth        : null,
                    emailAddress : null,
                },

                createAccount : {
                    credential   : "",
                    id  : "",
                    pwd : "",
                },
                
                inputId         : "",
                pwdCheck        : null, 
                pwdVerifyStatus : false,
            }
        },

        methods : {
            redirectMain () {
                this.$router.push("/");
            },

            /**
             * @desc check is assigned student
             * @return { Boolean } 
             */
            isAssignedStd () {
                //Field Null Check
                for( let key in this.stdVerify ) {                       

                    if( !this.stdVerify[key] ) {
                        this.notifier();
                        return false;
                    }
                }

                let reqHttpAddr = "/postmembercheck";
                let sendData    = { stdVerifyInfo  : JSON.stringify(this.stdVerify) };

                this.axios.post(reqHttpAddr, sendData).then( (res) => {
                    //FIXME: 회원가입 해 있는 학생인지 / 학생이 맞는지 2가지 체크 해서 return 값 다르게 보내줘 
                    
                    if( !res.data ) {
                        this.notifier(false, "有効な情報ではありません！")
                        return false;
                    }

                    let notifyMessage = "メールで認証コードを確認してください。"

                    this.notifier(true,notifyMessage);
                    this.verifies.stdIdentify = true;
                    this.stage = 2;
                    
                }).catch( (err) => {
                    console.log(err.message);
                });
                    
            },

            /**
             * @desc id duplidate check
             * @return { Boolean } 
             */
            isDuplicated () {

                if( !this.inputId ) {
                    this.notifier(false,  "アカウントを入力してください！");
                    return false;
                }

                this.createAccount.id = this.inputId;

                let reqHttpAddr = "/postUserIdDuplicates";
                let sendData    = { id  : this.inputId };

                this.axios.post(reqHttpAddr, sendData).then( (res) => {

                    if(!res.data){
                        this.notifier(false, "存在するアカウントです。");
                        return false;
                    }
                    
                    this.notifier(true, "使えるアカウントです！");
                    this.verifies.duplicateId = true;
               
               }).catch((err)=> {
                    console.log(err.message);
                });
            },

            createNewAccount () {

                let fieldCheck = this.dataFieldsValidator(this.createAccount);
                
                if ( !fieldCheck ) {
                    this.notifier(false,"証明してない情報があります。");                    
                }

                let reqHttpAddr = "/postStudentEnter";
                let sendData    = { stdInfo     : this.stdVerify   ,
                                    accountInfo : this.createAccount };

                this.axios.post(reqHttpAddr, sendData).then( (res) => {
                    
                    if( !res.data ){
                        this.notifier(false, "登録失敗です。");
                        return false;
                    }

                    this.notifier(true, "おめでとうございます！メインページでログインしてください！");     
                    this.$router.push("/");
                    //FIXME: 환영 메세지 , 컨펌 , 메인 리다이렉션 
                    
                }).catch((err)=> {
                    console.log(err.message);
                });
            },

            notifier (flag=false, message="全ての情報を入力してください！") {
                 
                let sub     = "Error !";
                let icons   = "whatshot";  
                let content = message;
                let notify_color = "#ed5249";

                if( flag ){
                    notify_color = "#1150ce";
                    sub     = "Success !";
                    icons   = "filter_vintage";
                }

                //FIXME: 다국어 테스팅 
                this.$vs.notify({
                    color : notify_color,                    
                    title : sub,
                    text  : content,
                    time  : 4000,
                    icon  : icons
                })
            },

            dataFieldsValidator (data) {
                for (let key in data) {
                    if( !data[key] ) 
                        return false;
                }
            },
        },

        watch : {
            pwdCheck () {
                if(this.createAccount.pwd == this.pwdCheck){
                    this.pwdVerifyStatus = true;
                }else{
                    this.pwdVerifyStatus = false;
                }
            },

            inputId () {
                 this.verifies.duplicateId = false;
            }
        }
    }
</script>
