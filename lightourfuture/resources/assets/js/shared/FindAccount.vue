<style>
    .wrap-cont {
        width               :100%;
        height              :100%;
        display             : grid;
        grid-template-rows  : 0.1fr 0.9fr;
    }

    .header{
        background : linear-gradient(to right, rgb(0, 128, 128) ,rgb(17,80,206)   );
        padding-top: 8px;
        padding-left: 15px;
    }
    

    
</style>

<template>
    <div class="wrap-cont">
        <div class="header">
            <img :src="logo" width="138px" height="auto" @click="redirect('main')" class="hidden-xs-only">
        </div>
        <div class="stepper">
            <v-app>
                <v-tabs fixed-tabs>
                    <v-tab v-for="(find,key) in findType" :key="key">
                        {{ find }}
                    </v-tab>
                    
                        <v-tab-item>
                            <v-card flat style="height: 500px">
                                <div style="width: 100%">
                                    <b-form style="width: 100%; padding-top:5%; padding-left:30%;">
                            
                                        <b-row class="my-1">
                                            <b-col sm="2"><label for="input-none">이름:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" v-model="findId_confirmData.name"></b-form-input>
                                            </b-col>
                                        </b-row>

                                        <b-row class="my-1" style="padding-top:2%; padding-bottom:2%">
                                            <b-col sm="2"><label for="input-none">생년월일:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" placeholder="971001" v-model="findId_confirmData.birth"></b-form-input>
                                            </b-col>
                                        </b-row>

                                        <b-row class="my-1">
                                            <b-col sm="2"><label for="input-none">이메일:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" v-model="findId_confirmData.email" v-validate="'required|email'"></b-form-input>
                                            </b-col>
                                        </b-row>
                                        <b-col sm="8" style="padding-top:2%;">                                        
                                            <v-btn block color="success" dark @click="recover_id()">찾기</v-btn>
                                        </b-col>
                                    </b-form>
                                </div>
                                <div style="padding-left:32%; padding-top: 2%; text-align:center;">
                                     <b-col sm="7">
                                        <p v-if="existiongUser=='success'" style="font-size: 20px; border-style: solid; border-color: #008080;">{{ userID }}</p>
                                        <p v-else-if="existiongUser=='fail'" style="font-size: 20px; border-style: solid; border-color: #008080;">일치하는 사용자가 없습니다.</p>
                                    </b-col>
                                </div>
                            </v-card>
                        </v-tab-item>

                        <v-tab-item>
                            <v-card flat>
                                <div style="width: 100%">
                                    <b-form style="width: 100%; padding:5%; padding-left:30%;">
                                        <b-row class="my-1">
                                            <b-col sm="2"><label for="input-none">아이디:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" v-model="findPwd_confirmData.id"></b-form-input>
                                            </b-col>
                                        </b-row>

                                        <b-row class="my-1" style="padding-top:2%; padding-bottom:2%">
                                            <b-col sm="2"><label for="input-none">생년월일:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" placeholder="년도" v-model="findPwd_confirmData.birth"></b-form-input>
                                            </b-col>
                                        </b-row>

                                        <b-row class="my-1">
                                            <b-col sm="2"><label for="input-none">이메일:</label></b-col>
                                            <b-col sm="6">
                                                <b-form-input id="input-none" type="text" v-model="findPwd_confirmData.email" v-validate="'required|email'"></b-form-input>
                                            </b-col>
                                        </b-row>
                                        <b-col sm="8" style="padding-top:2%;">                                        
                                            <v-btn block color="success" dark @click="recover_pwd()">찾기</v-btn>
                                        </b-col>
                                    </b-form>
                                </div>
                            </v-card>

                            <vs-dialog
                            vs-color="danger"
                            vs-title="확인"
                            vs-type="alert "
                            :vs-active.sync="alertDialog">
                            You are sure to delete this image?
                            </vs-dialog>
                        </v-tab-item>
                    </v-tabs>
            </v-app>
        </div>
    </div>
</template>




<script>
    export default {
        data(){
            return {
                logo: "/images/Index/logo_white.png",
                findType : ["아이디 찾기", "비밀번호 찾기"], //tab menu

                findId_confirmData :  //find id form
                {
                    name            : "손진호",
                    email           : "sjh@gmail.com",
                    birth           : "950420",
                    classification  : this.$store.getters.classification
                },

                findPwd_confirmData : //find password form
                {
                    id              : "st01",
                    email           : "sjh@gmail.com",
                    birth           : "950420",
                    classification  : this.$store.getters.classification
                },

                existiongUser : "", //아이디 찾기 시 찾는 유저가 존재하는지, 존재하지 않는지 여부
                userID        : "", //찾는 아이디 

                dialogTitle   : "확인",
                alertDialog   : false ,
                dialogColor   : "rgb(237, 82, 73)"
            }
        },
     
        methods : {

            redirect(targetPage) {
                if (targetPage == "main") {
                    this.$router.push("/");
                }
            },
            //아이디 찾기
            recover_id : function(){
                
                //Field null value
                let fields_nullCheck = this.validate_nullFields(this.findId_confirmData);
                if( !fields_nullCheck ) return false;
                
                console.log(this.findId_confirmData);

                let reqHttpAddr = "umc/find/id";
                let sendData    = { find_idInfo : JSON.stringify(this.findId_confirmData) };
                
                this.axios.post(reqHttpAddr, sendData).then( res => {

                    console.log(res.data);
                    if(res.data == 0){
                        this.existiongUser = "fail";
                    }else{
                        this.existiongUser = "success";
                        this.userID = res.data;
                    }
                }).catch((err)=> {
                    console.log(err.message)
                });
            },

            //비밀번호 찾기
            recover_pwd : function(){
            //Field null value
            let fields_nullCheck = this.validate_nullFields(this.findPwd_confirmData);
            if( !fields_nullCheck ) return false;

            console.log(this.findPwd_confirmData);
            
            let reqHttpAddr = "umc/find/pwd";
            let sendData    = { find_pwdInfo : JSON.stringify(this.findPwd_confirmData) };

            this.axios.post(reqHttpAddr,sendData).then( (res) => {
           
                console.log(res.data)

                if(res.data == "yes"){
                    let title = "이메일이 발송되었습니다. 확인해주세요.";
                    this.alertDialog = true;

                }else{
                    let title = "일치하는 사용자가 없습니다.";
                    this.alertDialog = true;
                }

            }).catch( (err) => {
                console.log(err.message);
            }); 
        },  

        //입력 값에 빈 값이 있는지 체크
        validate_nullFields : function (data) {
            for( let key in data ) {                       
               if( !data[key] )  return false;     
            }
            return true;
        },

        custom_alert : function ( flag ,title, message ) {
            //set default success color
            let alert_color   = "rgba(0,0,200,0.9)";
            //change danger color when bool value is false
            if( !flag ) alert_color = "rgba(200,0,0,0.8)";
            
            this.$vs.alert({
                title : title,
                text  : message,
                color : alert_color,
                accept : !flag
            });

        },
    }
}

</script>
