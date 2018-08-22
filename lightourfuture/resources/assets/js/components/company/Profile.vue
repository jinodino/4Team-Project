<template>
    <v-container fill-height grid-list-lg fluid >
        <v-layout column class="wrap-cont">
            <v-layout row>
                <v-flex xs12 sm12 md4 lg4 class="data-section"> 
                    <span class="headline sub-title">企業プロフィール</span>
                    <v-card flat tile>
                        <v-card-media
                        :src="companyProfile.photo_url"
                        height="100%"
                        ></v-card-media>
                    </v-card>
                </v-flex>

                <v-flex xs12 sm12 md8 lg8 class="manager-info-section" pa-0 style="padding-left:3em;">
                    <v-flex xs12 style="padding-bottom:0;">
                        <span class="headline sub-title">マネジャー情報</span>
                    </v-flex>
                    
                    <v-flex xs12 sm12 md9 lg9>
                        <v-layout row pa-1>
                            <v-flex xs12 sm12 md6 lg6>                                  
                                <v-text-field  clearable style="padding-bottom:0;"
                                label="名前(英語)"
                                v-model="managerInfo.name"
                                />
                            </v-flex>   
                            
                            <v-flex xs12 sm12 md6 lg6>                                  
                                <v-text-field  clearable style="padding-bottom:0;"
                                label="名前(カタカナ)"
                                v-model="managerInfo.katakana"
                                />
                            </v-flex> 

                            <v-flex xs12 sm12 md6 lg6>                                  
                                <v-text-field  clearable style="padding-bottom:0;"
                                label="メールアドレス"
                                v-model="managerInfo.email"
                                />
                            </v-flex> 
                            <v-flex xs12 sm12 md6 lg6>                                  
                                <v-btn  outline color="teal darken-1" class="subheading" width="6em" @click="changeManagerInfo()">
                                    情報変更
                                </v-btn>
                            </v-flex> 
                        </v-layout>
                    </v-flex> 
                   
                    <v-flex xs12 sm12 md12 lg12>
                        <span class="title sub-title">採用サマリー</span>
                    </v-flex>

                    <v-flex xs12 sm12 md12 lg12>
                        <v-chip label color="teal darken-1" text-color="white" style="height:5em;" v-for="data in recruitSummary" :key="data"> 
                            <v-flex xs12 sm12 md12 lg12 style="text-align:center;">
                                <span class="subheading">{{data.key}}</span> <br>
                                <span class="display-1 font-weight-bold"> {{data.value}} </span>
                            </v-flex>
                        </v-chip>
                        <v-chip label outline color="teal darken-1" style="height:5em; padding-bottom:1px;"  @click="companyInfoDialog = true">
                            <v-flex xs12 sm12 md12 lg12 style="text-align:center;">
                                <v-icon color="teal darken-1">
                                    business
                                </v-icon> <br>
                                <span class="sub-title">我社の情報</span>
                            </v-flex>
                        </v-chip>
                    </v-flex>
                </v-flex>
            </v-layout>

            <v-layout row pa-2>
                <v-flex xs12 sm12 md12 lg12>
                    <span class="headline sub-title">マッチングリスト</span>
                </v-flex>

                <v-flex v-if="!matchedCollegeList" xs12 sm12 md12 lg12>
                    <v-alert  :value="true" type="error" height="300px">
                        マッチングが出来ている学校がありません。
                    </v-alert>
                </v-flex>
                    <!-- Exception Handling ( no data in matched CollegeList )-->

                <v-flex xs12 sm12 md12 lg12>
                    <v-layout class="match-list">
                        <v-card v-for="matching in matchedCollegeList" :key="matching.org_matchings_id" hover class="match-school">
                            <br>
                            <v-card-media
                            contain
                            width="100%"
                            :src="matching.photo_url"
                            height="9em"
                            ></v-card-media>

                            <v-card-title primary-title class="justify-center bold">
                                <div>
                                    <p class="title">{{matching.college_name}} </p>
                                </div><br>
                                <p class="subheading"> ( {{matching.org_agent_name}}) </p>
                            </v-card-title>   
                        </v-card> &nbsp;
                    </v-layout>
                    
                </v-flex>
            </v-layout>
        </v-layout>

        <v-dialog
            v-model="companyInfoDialog" 
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            fullscreen
        >
        
        <v-flex xs12 sm12 md12 lg12>
            <v-card>
                <v-toolbar card dark color="teal darken-1" :clipped-right="true">
                    <v-btn icon dark @click.native="companyInfoDialog=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>我社の情報</v-toolbar-title>
                </v-toolbar>
                <company-info :orgCompanyId="companyId"></company-info>
            </v-card>
        </v-flex>
      </v-dialog>
    </v-container>   
</template>

<style scoped lang="css" src="../../static/css/Company/Profile.css"></style>

<script>
    import CompanyInfo from "../../shared/CompanyInfo";
    
    export default {
        components: {
            "company-info": CompanyInfo,
        },
        data () {
            return {
                slide: 0,
                sliding: null,
                companyId : null,
                companyInfoDialog : false,
                /**
                 * @desc company info & login manager info
                 */
                companyProfile : null,
                managerInfo    : {
                    name : null,
                    katakana : null,
                    email : null,
                },
                datas : null,

                matchedCollegeList : null,
                recruitSummary : [],
                companyImages : null,
            }
        },

        methods : {
            onSlideStart (slide) {
                this.sliding = true
            },
            
            onSlideEnd (slide) {
                this.sliding = false
            },

            changeManagerInfo () {
                 
                for( let key in this.managerInfo){
                    if( !this.managerInfo[key] ){
                        this.alerting("error","失敗","情報を全部入力してください")
                        return;
                    }
                }

                let reqHttpAddr = "/profileInfoChange";
                let sendData    = { requester : this.$store.getters.identification,
                                    nameEn    : this.managerInfo.name   ,
                                    nameKana  : this.managerInfo.katakana ,
                                    emailAddr : this.managerInfo.email }

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if( !res.data ) {
                        this.alerting("error","失敗","システムエラーです。");
                        return;
                    }else if( res.data == 2){
                        this.alerting("warning","失敗","マネジャー情報に変更事項がありません。");
                        return;
                    }

                    this.alerting("success","成功","マネジャー情報が変わりました。");
                    
                }).catch( err => {  
                    console.log(err.message);
                })
            },
            
            alerting ( type , title, message) {
                this.$swal({
                    type: type,
                    title: title,
                    text: message
                });
            }
        },

        created () {
            
            this.recruitSummary = [];
            
            let reqHttpAddr = "/companyprofile";
            let sendData    = { requester : this.$store.getters.identification }
            
            this.axios.post(reqHttpAddr,sendData).then( res => {

                if( !res.data )  return false;
                this.datas = res.data;
                this.companyId = res.data.comInfo[0].org_company_id;
                this.companyProfile         = res.data.comInfo[0];
                this.managerInfo.name       = res.data.comInfo[0].name;
                this.managerInfo.katakana   = res.data.comInfo[0].name_kana;
                this.managerInfo.email      = res.data.comInfo[0].email;

                this.matchedCollegeList = res.data.matching_school_info;

                this.recruitSummary.push({ key : "マッチング" , value : res.data.matchingSchoolCount});
                this.recruitSummary.push({ key : "総採用件"   , value : res.data.employmentCount });
                this.recruitSummary.push({ key : "終了採用件" , value : res.data.endEmploymentCount});
                this.recruitSummary.push({ key : "志願学生"   , value : res.data.applyStdCount});
                this.recruitSummary.push({ key : "内定学生"   , value : res.data.nominatedStdCount});
                this.recruitSummary.push({ key : "内定承諾"   , value : res.data.nominateOkStdCount});
                
                this.companyImages = res.data.company_publish_images;
            }).catch( err => {
                console.log(err.message);
            });
        },
    }    

</script>
