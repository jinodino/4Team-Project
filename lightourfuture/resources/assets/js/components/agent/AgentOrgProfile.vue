<template>
<!-- 수정 저장하기 -->
    <v-container fluid grid-list-md v-if="orgAgent != null">
        <v-layout row>
            <v-flex xs12 md8 lg8>
                <v-card color="white" height="100%">
                    <h2>所属 PROFILE</h2>    
                    <!--  Form : UserID, School 수정불가 -->
                    <v-layout wrap style="margin:3%">
                        <v-flex xs6 md6 lg6>
                            <b-form-group 
                                label="Agency ID:">
                                <b-form-input 
                                    type="text"
                                    disabled
                                    :value="orgAgent.org_agent_id"
                                    >
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <v-flex xs6 md6 lg6>
                            <b-form-group 
                                label="Manager Name:">
                                <b-form-input 
                                    type="text"
                                    :value="orgAgent.manager_name"
                                    disabled
                                    >
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                
                        <!-- Form : english, japan, katakana -->
                        <!-- 이름 -->
                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="Name(漢字) :">
                                <b-form-input 
                                    type="text"
                                    :value="orgAgent.org_name"
                                    v-model="editInfo.org_name"
                                    :disabled = 'item_disabled'>                            
                                </b-form-input>
                            </b-form-group>
                        </v-flex>               

                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="Name(カナ):">
                                <b-form-input id="Name"
                                    type="text"
                                    :value="orgAgent.org_name_kana"
                                    v-model="editInfo.org_name_kana"
                                    :disabled = 'item_disabled'>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>

                        <!-- 주소 -->

                        <v-flex xs2 sm2 md2 lg2> 
                            <b-form-group 
                                label="country:">
                                <b-form-input id="Name"
                                    type="text"
                                    :value="orgAgent.country_kana"
                                    disabled>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <v-flex xs10 sm10 md10 lg10> 
                            <b-form-group 
                                label="address:">
                                <b-form-input id="Name"
                                    type="text"
                                    :value="orgAgent.address"
                                    v-model="editInfo.address"
                                    :disabled = 'item_disabled'>     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>

                        <!-- Form : e-mail -->
                        <v-flex xs12 sm12 md12 lg12>
                            <b-form-group
                                label="homepageUrl">
                                <b-form-input id="Name"
                                    type="text"
                                    :value="orgAgent.homepage_url"
                                    v-model="editInfo.homepage_url"
                                    :disabled = 'item_disabled'>                        
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
                                        <img :src="orgAgent.photo_url != null ? orgAgent.photo_url : '/profileImages2/nullImage.JPG'" class="img-circle userImg">
                                        <!-- <img v-if="!photo_url" :src="src ? src : '/profileImages2/nullImage.JPG'" class="img-circle userImg"> -->
                                        <!-- <img v-else :src= "photo_url" class="img-circle userImg"> -->
                                            
                                    </v-avatar>
                                </div>
                                <div style="padding:10%">
                                    <h3>{{orgAgentId}}</h3>
                                </div>
                            </v-card-text>                 
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12 text-xs-right style="margin:5%; margin-top:70%">   
                            <label :for="orgAgentId"><v-icon>settings</v-icon></label>                        
                            <input v-show="false" accept=".jpg, .jpeg, .png" :id="orgAgentId" type="file" @change="onFileChange"/>
                        </v-flex>
                    </v-layout>
                </v-card>
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
export default {
    props : ['orgAgentId'],

    created : function(){
        //에이전트 정보 가져오기
        this.getOrgProfile();   
    },

    data : () =>({ 
        item_disabled : true,

        orgAgent : null,

        editInfo:{
            address : null,
            homepage_url : null,
            org_name : null,
            org_name_kana : null,
        },

        profileImage : {
            url : null,
            data : null,
            type : null,
        }
    }),
    methods : {

        changeEditMode(){
             this.item_disabled = !this.item_disabled;
             this.initiateEditInfo();
        },
        
        initiateEditInfo(){
            this.editInfo.address           = this.orgAgent.address;
            this.editInfo.homepage_url      = this.orgAgent.homepage_url;
            this.editInfo.org_name          = this.orgAgent.org_name;
            this.editInfo.org_name_kana     = this.orgAgent.org_name_kana;
        },

        updateProfile() {
            //FIXME: co01 -> this.$store.state.identification
            let reqHttpAddr = "/agent/updateOrgProfile";
            
            let sendData    = { 
                org_agent_id : this.orgAgent.org_agent_id,
                editInfo : this.editInfo,
            }

            this.axios.post(reqHttpAddr,sendData)
                        .then( rep => {
                            if(rep.data.returnBool){
                                alert("セーブされました。");
                                this.getOrgProfile();
                                this.item_disabled = !this.item_disabled ;
                            }else{
                                alert('실패');
                            }
                        })
                        .catch( ex => {
                            console.log(ex);
                        }); 
        },

        getOrgProfile(){
            let agent_id = this.orgAgentId;
            this.axios.post('/agent/getOrgAgentById', {agent_id})
                        .then(rep=>{
                            this.orgAgent = rep.data.orgAgent;
                            this.profileImage.url = this.orgAgent.photo_url;
                            this.initiateEditInfo();
                        })
                        .catch(ex=>{
                            console.log(ex);
                        });
        },

        uploadImage(){
            let reqHttpAddr = '/agent/uploadImage';
            let type = 'orgAgent';
            let id = this.orgAgentId;
            let profileImage = this.profileImage;

            this.axios.post(reqHttpAddr,{type, id, profileImage})
                        .then( rep => {
                            if(rep.data.returnBool){
                               this.getOrgProfile();
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

            //this.uploadImage();
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