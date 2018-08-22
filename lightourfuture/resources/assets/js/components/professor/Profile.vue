
<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm12 md12 lg12>
                <v-layout row>
                    <v-flex xs12 sm12 md8 lg8>
                        <v-card height="100%">
                            <div style="background-color:#008080; color:white; height:45px">
                                <h2 style="padding-left:1%; padding-top:0.5%;">PROFILE</h2>    
                            </div>
                            <!--  Form : UserID, School 수정불가 -->
                            <v-layout row wrap style="margin:3%">
                                <v-flex xs6 sm6 md6 lg6>
                                    <b-form-group 
                                        label="User ID:">
                                        <b-form-input 
                                            type="text"
                                            disabled
                                            label-class="font-weight-bold pt-0"
                                            v-model="profileInfo.userId">
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>

                                <v-flex d-flex xs6 sm6 md6 lg6>
                                    <b-form-group 
                                        label="School:">
                                        <b-form-input 
                                            type="text"
                                            disabled
                                            v-model="profileInfo.school">
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>
                        
                                <!-- Form : Name_korea, english, japan, katakana -->
                                <v-flex xs12 sm12 md12 lg12> 
                                    <b-form-group :label="$t('Professor_profile.name_kr')">
                                        <b-form-input id="Name"
                                            type="text"
                                            v-model="profileInfo.korea"
                                            v-validate="'required'">
                                        </b-form-input>
                                    </b-form-group> 
                                </v-flex>

                                <v-flex xs6 sm6 md6 lg6> 
                                    <b-form-group 
                                        :label="$t('Professor_profile.name_ka')">
                                        <b-form-input 
                                            type="text"
                                            v-model="profileInfo.japan"
                                            v-validate="'required'">                            
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>               

                                <v-flex xs6 sm6 md6 lg6> 
                                    <b-form-group 
                                        :label="$t('Professor_profile.name_ja')">
                                        <b-form-input id="Name"
                                            type="text"
                                            v-model="profileInfo.katakana"
                                            v-validate="'required'">     
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>
                                <!-- Form : e-mail -->
                                <v-flex xs12 sm12 md12 lg12>
                                    <b-form-group
                                        label="e-mail:">
                                        <b-form-input id="Name"
                                            type="text"
                                            v-model="profileInfo.email"
                                            v-validate="'required'">                        
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>
                                <!-- Form : birth, major -->
                                <v-flex xs2 sm2 md2 lg2>
                                    <b-form-group
                                             :label="$t('Professor_profile.birthY')">
                                            <b-form-input
                                                type="text"
                                                v-model="birth.y"
                                                v-validate="'required'">                            
                                            </b-form-input>
                                        </b-form-group>
                                </v-flex>
                                <v-flex xs2 sm2 md2 lg2>
                                    <b-form-group
                                            :label="$t('Professor_profile.birthM')">
                                            <b-form-input
                                                type="text"
                                                v-model="birth.m"
                                                v-validate="'required'">                            
                                            </b-form-input>
                                        </b-form-group>
                                </v-flex>
                                <v-flex xs2 sm2 md2 lg2>
                                    <b-form-group
                                            :label="$t('Professor_profile.birthD')">
                                            <b-form-input
                                                type="text"
                                                v-model="birth.d"
                                                v-validate="'required'">                            
                                            </b-form-input>
                                        </b-form-group>
                                </v-flex>
                                <v-flex xs6 sm6 md6 lg6>
                                    <b-form-group 
                                        :label="$t('Professor_profile.major')">
                                        <b-form-input id="Name"
                                            type="text"
                                            v-model="profileInfo.major"
                                            v-validate="'required'">                            
                                        </b-form-input>
                                    </b-form-group>
                                </v-flex>

                                <!-- Form : japaneseLanguageAbility -->                
                                <v-flex xs6 sm6 md6 lg6>
                                    <b-form-group
                                        :label="$t('Professor_profile.jaYN')">
                                        <b-form-select
                                            :options="languageAbility"
                                            v-model="profileInfo.japaneseLanguageAbility"
                                            v-validate="'required'">
                                        </b-form-select>
                                    </b-form-group>      
                                </v-flex>

                                <v-flex xs12 sm12 md12 lg12 text-xs-right>
                                    <v-btn @click="validateBeforeSubmit()" color="success" type="submit">Save</v-btn>
                                </v-flex>  

                            </v-layout>
                        </v-card>                
                    </v-flex>

                    <v-flex xs12 sm12 md4 lg4>
                        <v-card color="white" height="100%">
                            <v-layout wrap row>
                                <v-flex d-flex xs12 sm12 md12 lg12 style="height:25%;margin-bottom: -60%" >
                                    <v-card-media src="/images/professor/cat.jpg" height="100%"/>
                                </v-flex>
                                
                                <v-flex xs12 sm12 md12 lg12>   
                                    <v-card-text style="text-align :center;" >
                                        <div>
                                            <v-avatar responsive :size=130>
                                                <img v-if="!profileImage.data" :src="src ? src : '/profileImages2/nullImage.JPG'" class="img-circle userImg">
                                                <img v-else :src= "profileImage.data" class="img-circle userImg">
                                            </v-avatar>
                                        </div>
                                        <div style="padding:10%">
                                            <h3>{{profileInfo.korea}}</h3>
                                            <h5>{{profileInfo.email}}</h5>
                                        </div>
                                    </v-card-text>                 
                                </v-flex>
                                <v-flex xs12 sm12 md12 lg12 text-xs-right style="margin:5%; margin-top:70%">   
                                    <label for="file_input"><v-icon>settings</v-icon></label>                        
                                    <input v-show="false" accept=".jpg, .jpeg, .png" id="file_input" type="file" @change="onFileChange"/>
                                </v-flex>
                            </v-layout>
                        </v-card>
                    </v-flex> 
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</template>


<style lang="css" src="../../static/css/Professor/Profile.css"></style>


<script>

/**@author Da Yeon */

    export default {
        props : ['src'],
        data () {
            return {
                //프로필 이미지 저장
                profileImage : {
                    data         : '',
                    name         : '',
                    folder       : 'profileImages2',
                    key          : "photo_url"
                },
                //생년월일
                birth       : {
                                y : "",
                                m : "",
                                d : ""
                            },
                //프로필 정보 
                profileInfo : {
                                userId                  : "",
                                school                  : "",
                                schoolId                : "",
                                birth                   : "",                    
                                korea                   : "",
                                japan                   : "",
                                katakana                : "",
                                email                   : "",
                                major                   : "",
                                japaneseLanguageAbility : "",
                                imageUrl                : "",
                            },
                //교수님 일본어 가능성 유무 select
                languageAbility             : [
                                                'o', 'x' //교수는 일상회화, 비지니스 등 실력으로 나누지 않고 할 수 있는가 없는가로만 나눔
                                              ]                                                                                                            
            }
        },
        methods : {
            /**
             * @param  profileInfo
             * @description Professor Profile revision function
             * @returns true,false
             */
            profileSave : function(){
                this.profileInfo.birth = this.birth.y+this.birth.m+this.birth.d;

                let reqHttpAddr = "/professor_profile_save";
                let sendData    = { profileInfo  : JSON.stringify(this.profileInfo),
                                    professorId  : this.$store.getters.identification,
                                    profileImage : this.profileImage
                                };   

                this.axios.post(reqHttpAddr, sendData)
                .then( (res) => {
                    this.profileInfo        = res.data[0];
                    this.profileImage.data  = res.data[0].imageUrl;
                    //alert(res.data);
                }).catch( (err) => {
                }).catch( (err) => {
                    console.log(err.message);
                });
            
            }, 
            
            //input null Value Check
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.profileSave();
                        this.$swal({
                            type    : 'success',
                            title   : 'save',
                        });
                        return;
                    }
                    this.$swal({
                        type: 'error',
                        title: 'Oops...',
                        text: this.$i18n.t("Professor_stdManagement.messa")
                    });
                });
            },


            //프로필 사진 수정
            onFileChange(e){
            
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return;
                this.createImage(files[0]);

           // this.uploadImage();
            },

            createImage(file){
                var image = new Image();
                var reader= new FileReader();

                reader.onload = (e)=>{
                    this.profileImage.data = e.target.result;
                    this.profileImage.name = file.name;
                };
                reader.readAsDataURL(file);
            },

            userInfo : function(){
                /**
                * @param  profileInfo
                * @description I get a professor profile from database.
                * @returns profileInfo
                */


                let reqHttpAddr = "/professor_profile"; 
                let sendData    = {professorId : this.$store.getters.identification};   //교수ID  
                
                this.axios.post(reqHttpAddr, sendData)
                .then((res) => {
                    console.log(res.data);
                    this.profileInfo = res.data[0];
                    this.profileImage.data = res.data[0].imageUrl;
                    let userbirth = [];
                    userbirth = res.data[0].birth.split("-"); 
                    this.birth.y = userbirth[0];
                    this.birth.m = userbirth[1];
                    this.birth.d = userbirth[2];
                    
                }).catch( (err) => {
                    console.log(err.message);
                });
                
            }
        },
        mounted (){
            this.userInfo();
        }
    }
</script>

<style>

</style>
