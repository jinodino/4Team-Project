
<template>
<!-- 
    *** 예외 처리 ***
    1. 생년월일 숫자, 자릿수 예외처리
    2. 이메일 형식 예외처리
 -->
 
<v-container fluid grid-list-md>
    <v-layout row>
        <v-flex xs12 md8 lg8>
            <v-card color="white" height="100%">
                <h2> 個人 PROFILE</h2>    
                <!--  Form : UserID, School 수정불가 -->
                <v-layout wrap style="margin:3%">
                    <v-flex xs12 md12 lg12>
                        <b-form-group 
                            label="User ID:">
                            <b-form-input 
                                type="text"
                                disabled
                                :value="info.login_id"
                                >
                            </b-form-input>
                        </b-form-group>
                    </v-flex>
            
                    <!-- Form : english, japan, katakana -->

                    <v-flex xs6 sm6 md6 lg6> 
                        <b-form-group 
                            label="Name:">
                            <b-form-input 
                                type="text"
                                v-model="editInfo.name"
                                :value="info.name"
                                :disabled = 'item_disabled'
                                v-validate="'required'">                            
                            </b-form-input>
                        </b-form-group>
                    </v-flex>               

                    <v-flex xs6 sm6 md6 lg6> 
                        <b-form-group 
                            label="katakanaName:">
                            <b-form-input id="Name"
                                type="text"
                                v-model="editInfo.name_kana"
                                :value="info.name_kana"
                                :disabled = 'item_disabled'
                                v-validate="'required'">     
                            </b-form-input>
                        </b-form-group>
                    </v-flex>
                    <!-- Form : e-mail -->
                    <v-flex xs12 sm12 md12 lg12>
                        <b-form-group
                            label="E-mail:">
                            <b-form-input id="Name"
                                type="text"
                                v-model="editInfo.email"
                                :value="info.email"
                                :disabled = 'item_disabled'
                                v-validate="'required'">                        
                            </b-form-input>
                        </b-form-group>
                    </v-flex>
                    <!-- Form : birth, major -->
                    <v-flex xs2 sm2 md2 lg2>
                        <b-form-group
                                label="Birth Year:">
                                <b-form-input
                                    type="number"
                                    v-model="editBirth.y"
                                    :value="birth.y"
                                    :disabled = 'item_disabled'
                                    v-validate="'required'">                            
                                </b-form-input>
                            </b-form-group>
                    </v-flex>
                    <v-flex xs2 sm2 md2 lg2>
                        <b-form-group
                                label="Birth Month:">
                                <b-form-input
                                    type="number"
                                    v-model="editBirth.m"
                                    :value="birth.m"
                                    :disabled = 'item_disabled'
                                    v-validate="'required'">                            
                                </b-form-input>
                            </b-form-group>
                    </v-flex>
                    <v-flex xs2 sm2 md2 lg2>
                        <b-form-group
                            label="Birth Day:">
                            <b-form-input
                                type="number"
                                v-model="editBirth.d"
                                :value="birth.d"
                                :disabled = 'item_disabled'
                                v-validate="'required'">                            
                            </b-form-input>
                        </b-form-group>
                    </v-flex>
                    
                    <v-flex xs12 sm12 md12 lg12 text-xs-right>
                        <v-btn v-if="item_disabled == false" @click="updateProfile()" color="success" type="submit">Save</v-btn>
                        <v-btn v-if="item_disabled == false" @click="changeEditMode()" color="error" type="submit">cancel</v-btn>
                        <v-btn v-else @click="changeEditMode()" color="success" type="submit">edit</v-btn>
                    </v-flex>                           
                </v-layout>
            </v-card>                
        </v-flex>
        <v-flex xs12 sm12 md4 lg4>
            <v-card color="white" height="100%">
                <v-layout wrap row>
                    <v-flex xs12 sm12 md12 lg12>   
                        <v-card-text style="text-align :center;" >
                            <div>
                                <v-avatar responsive :size=130>
                                    <img :src="info.profile_photo_url != null ? info.profile_photo_url : '/profileImages2/nullImage.JPG'" class="img-circle userImg">
                                </v-avatar>
                            </div>
                            <div style="padding:10%">
                                <h3>{{info.name}}</h3>
                                <h5>{{info.login_id}}</h5>
                            </div>
                        </v-card-text>                 
                    </v-flex>
                    <v-flex xs12 sm12 md12 lg12 text-xs-right style="margin:5%; margin-top:70%">   
                        <label :for="user.login_id"><v-icon>settings</v-icon></label>                        
                        <input v-show="false" accept=".jpg, .jpeg, .png" :id="user.login_id" type="file" @change="onFileChange"/>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-flex> 
    </v-layout>
    
    <v-layout row>
        <v-flex xs12>
            <v-container fluid grid-list-sm>
                <v-tabs
                    slot="extension"
                    v-model="tab"
                    grow
                    fixed-tabs
                >
                    <v-tab v-for="org_agent in user.affiliation" :key="org_agent.no">
                        <p class="subheading">{{org_agent.org_name}}</p>
                    </v-tab>
                </v-tabs> 
            
                <v-tabs-items v-model="tab">
                    <v-tab-item v-for="org_agent in user.affiliation" :key="org_agent.no.key">
                        <org-profile :orgAgentId="org_agent.org_agent_id"></org-profile>
                    </v-tab-item>
                </v-tabs-items>
            </v-container>
        </v-flex>
    </v-layout>
</v-container>
</template>

<style>
    .input-file {
        opacity: 0;
        width: 0;
        height: 0;
    }

</style>

<script>
import OrgProfile from './AgentOrgProfile.vue';
export default {
    components : {
        'org-profile' : OrgProfile,
    },
    created() {
        this.user.login_id = this.$store.getters.identification;
        this.user.classification = this.$store.getters.classification;

        //에이전트 소속 가져오기
        let user = this.user;
        this.axios.post('/agent/getOrgs', {user})
                    .then(rep=>{
                        this.user.affiliation = rep.data;
                    })
                    .catch(ex=>{
                    console.log(ex);
                    });

        

        this.getProfile();
    },

    data : () =>({
        user : {
            login_id : null,
            classification : null,
            affiliation : [],
        },

        tab : '0',
        item_disabled : true,

        info : {},    
        editInfo : {
            name : null,
            name_kana : null,
            email : null,
            birth_date : null,
        },
        
        birth : {
            y : "",
            m : "",
            d : ""
        },

        editBirth : {
            y : "",
            m : "",
            d : ""
        },

        profileImage : {
            url : null,
            data : null,
            type : null,
        }
    }),
 

    methods : {
        
        //수정 모드
        changeEditMode(){
             this.item_disabled = !this.item_disabled;
             this.initiateEditInfo();
        },

        initiateEditInfo(){
            this.editInfo.name = this.info.name;
            this.editInfo.name_kana = this.info.name_kana;
            this.editInfo.email = this.info.email;
            this.editInfo.name = this.info.name;
            this.editInfo.birth_date = this.info.birth_date;

            let birthDay = this.info.birth_date.split('-');
            this.birth.y = birthDay[0];
            this.birth.m = birthDay[1];
            this.birth.d = birthDay[2];

            this.editBirth.y = birthDay[0];
            this.editBirth.m = birthDay[1];
            this.editBirth.d = birthDay[2];

        },
        
        // 프로필 정보 호출
        getProfile() {
            //FIXME: co01 -> this.$store.state.identification
            let reqHttpAddr = "/agent/getProfile";
            let login_id = this.user.login_id;
            
            this.axios.post(reqHttpAddr,{login_id})
                        .then( res => {
                            this.info = null;
                            this.info = res.data.info;
                            this.profileImage.url = this.info.profile_photo_url;

                            this.initiateEditInfo();


                            // 생년월일 '-' 기준으로 나누기
                            
                            // [0] => year / [1] => month / [2] => day
                          


                        })
                        .catch( ex => {
                            console.log(ex.message);
                        }); 
        },

        //이메일 유효성 검사
        email_check( email ) {    
            var regex=/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            let returnBool =  regex.test(email); 
            return returnBool;
        },
        updateProfile() {
            //FIXME: co01 -> this.$store.state.identification
            let reqHttpAddr = "/agent/updateProfile";

            if(!this.email_check(this.editInfo.email)){
                alert('間違ったメールアドレスです。');
                return;
            }
            
            let sendData    = { 
                requester : this.$store.getters.identification,
                info      : {
                    name : this.editInfo.name,
                    nameKana  : this.editInfo.name_kana,    
                    email     : this.editInfo.email,
                    birth     : this.editBirth.y + '-' + this.editBirth.m + '-' + this.editBirth.d,
                }
                
            }

            this.axios.post(reqHttpAddr,sendData)
                        .then( res => {
                            
                            alert("セーブされました。");
                            this.item_disabled = !this.item_disabled;

                        })
                        .catch( err => {
                            console.log(err.message);
                        }); 
        },

        uploadImage(){
            let reqHttpAddr = '/agent/uploadImage';
            let type = 'agent';
            let id = this.user.login_id;
            let profileImage = this.profileImage;

            this.axios.post(reqHttpAddr,{type, id, profileImage})
                        .then( res => {
                           if(res.data.returnBool){
                               this.getProfile();
                           }
                        })
                        .catch( ex => {
                            console.log(ex.message);
                        }); 
        },

        //프로필 사진 수정
        onFileChange(e){
        
            var files = e.target.files || e.dataTransfer.files;
            if(!files.length)
                return;
            this.createImage(files[0]);
        },

        createImage(file){
            var image = new Image();
            var reader= new FileReader();

            reader.onload = (e)=>{
                this.profileImage.type = file.type;
                this.profileImage.data = e.target.result;

                this.uploadImage();
            };
            reader.readAsDataURL(file);
        },
    }
}
</script>