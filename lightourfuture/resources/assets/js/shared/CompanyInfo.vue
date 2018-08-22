<template>
    <v-container fluid >
        <v-layout row v-if="info != null"  style="background-color: white; border-right: 1px solid #BDBDBD;">
            <v-flex xs12 md12 lg12 text-center id="mainbar">
                企業情報
            </v-flex>
            <v-flex xs12 md4 lg4>
                <v-card style="height: 220px; padding : 5px;" flat>
                    <v-card-media
                    contain
                    height="218px"
                    :src="info.org_companies.photo_url"
                    >
                    </v-card-media>
                </v-card>
            </v-flex>
            
            <v-flex xs12 md8 lg8 style="padding-left:5px; padding-top : 10px">
                <v-layout wrap>
                    <v-flex xs12 md6 lg6>
                        <v-layout wrap>
                            <v-flex xs4 md4 lg4>
                            <b> ● 会社名</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>: 
                                {{info.org_companies.org_name === null ? info.org_companies.org_name_kana : info.org_companies.org_name}}
                                {{info.org_companies.org_name !== null && info.org_companies.org_name_kana !== null ? "(" + info.org_companies.org_name_kana + ")" : "─"}}
                            </v-flex>
                            
                            <v-flex xs4 md4 lg4>
                            <b> ● 設立日</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>: 
                                {{info.org_companies.establish_date !== null ? info.org_companies.establish_date : "─"}}
                            </v-flex>

                            <v-flex xs4 md4 lg4>
                            <b> ● 社員数</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>: 
                                {{info.org_companies.worker_count !== null ? info.org_companies.worker_count : "─"}}
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 md6 lg6>
                        <v-layout wrap>
                            <v-flex xs4 md4 lg4>
                            <b> ● 代表者名</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>: 
                                {{info.org_companies.ceo_name === null ? info.org_companies.ceo_name_kana : info.org_companies.ceo_name}}
                                {{info.org_companies.ceo_name !== null && info.org_companies.ceo_name_kana !== null ? "(" + info.org_companies.ceo_name_kana + ")" : ""}}
                            </v-flex>
                            
                            <v-flex xs4 md4 lg4>
                            <b> ● 資本金</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>: 
                                {{info.org_companies.capital !== null ? info.org_companies.capital : "─"}}
                            </v-flex>

                            <v-flex xs4 md4 lg4>
                            <b> ● 上場</b> 
                            </v-flex>
                            <v-flex xs8 md8 lg8>:
                                {{info.org_companies.listed_company_ox === "o" ? "上場" : (info.org_companies.listed_company_ox === "x" ? "未上場" : "─") }}
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 md12 lg12 >
                        <v-layout wrap>
                            <v-flex xs4 md2 lg2>
                                <b> ● 住所</b> 
                            </v-flex>
                            <v-flex xs8 md10 lg10>:
                                {{info.org_companies.address !== "" ? info.org_companies.address : "─"}}
                            </v-flex>
                            <v-flex xs4 md2 lg2>
                                <b> ● ホームページ</b> 
                            </v-flex>
                            <v-flex xs8 md10 lg10>:
                                <a :href="info.org_companies.homepage_url">{{info.org_companies.homepage_url !== null ? info.org_companies.homepage_url : "─"}}</a>
                                
                            </v-flex>
                            <v-flex xs4 md2 lg2>
                                <b> ● 事業大分類</b> 
                            </v-flex>
                            <v-flex xs8 md10 lg10>
                                <v-chip id="gray" text-color="white" style="float:left; margin-left: 10px" disabled v-for="(big_value) in info.business_bigs " :key="big_value.key">{{big_value.content}}</v-chip>
                            </v-flex>
                            <v-flex xs4 md2 lg2>
                                <b> ● 事業小分類</b> 
                            </v-flex>
                            <v-flex xs8 md10 lg10>
                                <v-chip  id="gray"  text-color="white" style="float:left; margin-left: 10px" disabled v-for="(smalls_value) in info.business_smalls" :key="smalls_value.key">{{smalls_value.content}}</v-chip>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
        <v-layout  row v-if="info != null" style="background-color: white; border-right: 1px solid #BDBDBD; border-left: 1px solid #BDBDBD;">
            <v-flex xs4 md2 lg2  text-center id="tableTitle">
                <v-card-text>事業内容</v-card-text>
            </v-flex>
            <v-flex xs8 md10 lg10 id="tableItem">
                <v-card-text>{{info.org_companies.business_content }}</v-card-text>
            </v-flex>

            <v-flex xs4 md2 lg2  text-center id="tableTitle">
                <v-card-text>社内雰囲気</v-card-text>
            </v-flex>
            <v-flex xs8 md10 lg10 id="tableItem">
                <v-card-text>{{info.org_companies.company_atmosphere }}</v-card-text>
            </v-flex>

            <v-flex xs4 md2 lg2  text-center id="tableTitle" style="border-bottom: 1px solid #BDBDBD;">
                <v-card-text>エージェントコメント</v-card-text>
            </v-flex>
            <v-flex xs8 md10 lg10 id="tableItem" style="border-bottom: 1px solid #BDBDBD;">
                <v-card-text>{{info.org_companies.recommendation_comment }}</v-card-text>
            </v-flex>
        </v-layout>
        <v-layout  v-if="info.org_imgs[0]" row style="border-right: 1px solid #BDBDBD; border-left: 1px solid #BDBDBD;">
            <v-flex xs12 md12 lg12 text-center id="mainbar">
                企業写真
            </v-flex>
            <v-layout style="background-color:white">
                <v-flex x12 md12 lg12 style="padding-top: 10px;">
                <!-- blog img -->
                    <v-flex v-for="imgurl in info.org_imgs" :key="imgurl.key">
                        <button type="button" @click="imgbtn = !imgbtn, dialogimg = imgurl.photo_url" style="width:70%; margin-left: auto; margin-right: auto; display: block;">
                        <img :src="imgurl.photo_url" style="width:100%; margin-left: auto; margin-right: auto; display: block;"> 
                        </button><br>
                    </v-flex>
                </v-flex>
            </v-layout>
        </v-layout>
        <v-dialog v-model="imgbtn" width="80%">
            <v-card>
                <v-container>
                <img :src="dialogimg" style="max-width:100%; margin-left: auto; margin-right: auto; display: block;"> 
                </v-container>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
  props : ['orgCompanyId'],

  created : function(){
    this.getCompanyInfo();
  },

  data : ()=> ({
    info : null,
    imgbtn : false,
    dialogimg : '',
  }),
  
  methods : {

    //기업 정보 가져오기
    getCompanyInfo(){
      let org_company_id = this.orgCompanyId;
      this.axios.post('/company/getCompanyInfo',{org_company_id})
                .then(rep=>{
                  this.info = rep.data;
                })
                .catch(ex=>{
                  console.log(ex);
                });
    }
  },

  watch : {
      orgCompanyId : function (val){
                        this.getCompanyInfo();
                      }
  }

}
</script>

<style>
  li {
    list-style-type : disc
  }
</style>

<style scoped lang="css" src="../static/css/agent/agnetAndStudentStyleSheet.css"></style>
