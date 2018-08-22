<v-flex xs12>
      <!-- 검색 조건 -->
      <v-expansion-panel>
        <v-expansion-panel-content expand-icon="mdi-menu-down">
          <div slot="header" class="Titlefont">検索条件開く</div>
          <v-card>
            <v-container fluid>
              <v-flex xs12  text-xs-center id="mainbar">
                企業情報
              </v-flex>
              <v-flex xs12>
                <v-layout wrap>
                  <!-- 대분류 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin: 8px;">
                      {{business.business_big_infos.title}}
                      <input type="checkbox" align-left @click="Check_all(0)" v-model ="allbig">
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">
                    <div style=" margin: 8px;">
                      <v-layout row>
                        <v-flex xs6 sm3 v-for="(check, bigkey) in business.business_big_infos.value" :key="bigkey"> 
                          <input type="checkbox" :value="check.id" name="continents" v-model="Search.big_infos"> {{check.content}}
                        </v-flex>
                      </v-layout>
                    </div>
                  </v-flex>
                  <!-- 소분류 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin-top: 8px;">
                      {{business.business_small_infos.title}}
                      <input type="checkbox" align-left @click="Check_all(1)" v-model ="allsmall">
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">

                    <input id="itemtoggle" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                      <label for="itemtoggle">
                        
                      </label>
                    <v-layout wrap style=" margin: 1px;">
                      <v-flex xs6 sm3 >
                        <input type="checkbox" :value="business.business_small_infos.value[0].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[0].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="business.business_small_infos.value[1].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[1].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="business.business_small_infos.value[2].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[2].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="business.business_small_infos.value[3].id" name="small_infos" v-model="Search.small_infos"> {{business.business_small_infos.value[3].content}}
                      </v-flex>
                    </v-layout>

                    <v-flex xs12 id="expand">
                      <section>
                        <v-layout row> 
                          <v-flex xs6 sm3  v-for="(check, smallkey) in business.business_small_infos.value" :key="smallkey"> 
                            <p v-if="smallkey > 3">
                              <input type="checkbox" :value="check.id" name="small_infos" v-model="Search.small_infos"> {{check.content}}
                            </p>
                          </v-flex>
                        </v-layout>
                      </section>
                    </v-flex>
                  </v-flex>

                  <!-- 사원수 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin: 8px;">
                      社員数
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">
                    <div style=" margin: 8px;">
                      <v-flex xs8>
                        <v-layout row>
                          <v-text-field 
                            type="number"
                            v-model="Search.worker_count_infos[0]"
                            label="最小"
                            :min=1
                            :max=5000
                          ></v-text-field >
                          <v-text-field 
                            type="number"
                            v-model="Search.worker_count_infos[1]"
                            label="最大"
                            :min=1
                            :max=5000
                          ></v-text-field >
                        </v-layout>
                      </v-flex>
                    </div>
                  </v-flex>
                </v-layout>
              </v-flex>
              
              <v-flex xs12 text-xs-center id="mainbar">
                採用情報
              </v-flex>
              <v-flex xs12>
                <v-layout wrap>

              <!-- 업무내용 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin: 8px;">
                      {{employment.work_infos.title}}
                      <input type="checkbox" align-left @click="Check_all(2)" v-model ="allwork">
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">
                    
                    <input id="toggle" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                      <label for="toggle">
                        
                      </label>
                    <v-layout wrap style=" margin: 1px;">
                      <v-flex xs6 sm3 >
                        <input type="checkbox" :value="employment.work_infos.value[0].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[0].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.work_infos.value[1].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[1].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.work_infos.value[2].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[2].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.work_infos.value[3].id" name="work_infos" v-model="Search.work_matchings_infos"> {{employment.work_infos.value[3].content}}
                      </v-flex>
                    </v-layout>

                    <v-flex xs12 id="expand">
                      <section>
                        <v-layout row>
                          <v-flex xs6 sm3 v-for="(work_check, workkey) in employment.work_infos.value" :key="workkey"> 
                            <p v-if="workkey > 3">
                              <input type="checkbox" :value="work_check.id" name="work_infos" v-model="Search.work_matchings_infos">{{work_check.content}}
                            </p>
                          </v-flex>
                        </v-layout>
                      </section>
                    </v-flex>
                  </v-flex>

              <!-- 복리후생 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin: 8px;">
                      {{employment.welfare_infos.title}}
                      <input type="checkbox" align-left @click="Check_all(3)" v-model ="allwelfare">
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">
                    <div style=" margin: 8px;">
                      <v-layout row>
                        <v-flex xs6 sm2  v-for=" (welfare_check, welkey) in employment.welfare_infos.value" :key="welkey"> 
                          <input type="checkbox" :value = "welfare_check.id" v-model="Search.welfare_content_infos"> {{welfare_check.content}}
                        </v-flex>
                      </v-layout>
                    </div>
                  </v-flex>

              <!-- 원하는 인재상 -->
                  <v-flex xs4 md2 lg2  text-center id="tableTitle">
                    <div style=" margin: 8px;">
                      {{employment.desired_employment_infos.title}}
                      <input type="checkbox" align-left @click="Check_all(4)" v-model ="alldesired">
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem">
                    
                    <input id="toggleemployment" type="checkbox" checked style="display: none; visibility: hidden; text-align:right; text-align:right">
                      <label for="toggleemployment">
                        
                      </label>
                    <v-layout wrap style=" margin: 1px;">
                      <v-flex xs6 sm3 >
                        <input type="checkbox" :value="employment.desired_employment_infos.value[0].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[0].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.desired_employment_infos.value[1].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[1].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.desired_employment_infos.value[2].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[2].content}}
                      </v-flex>
                      <v-flex xs6 sm3>
                        <input type="checkbox" :value="employment.desired_employment_infos.value[3].id" name="employment" v-model="Search.desired_employments_infos"> {{employment.desired_employment_infos.value[3].content}}
                      </v-flex>
                    </v-layout>

                    <v-flex xs12 id="expand">
                      <section>
                        <v-layout row>
                          <v-flex xs6 sm3    v-for=" (desired, deskey) in employment.desired_employment_infos.value" :key="deskey"> 
                            <p v-if="deskey > 3">
                              <input type="checkbox" :value = "desired.id" name="employment" v-model="Search.desired_employments_infos"> {{desired.content}}
                            </p>
                          </v-flex>
                        </v-layout>
                      </section>
                    </v-flex>
                  </v-flex>

                  <v-flex xs4 md2 lg2  text-center id="tableTitle" style="border-bottom: 1px solid #BDBDBD;">
                    <div style=" margin: 8px;">
                      1年給料
                    </div>
                  </v-flex>
                  <v-flex xs8 md10 lg10 id="tableItem" style="border-bottom: 1px solid #BDBDBD;">
                    <div style=" margin: 8px;">
                      <v-flex xs8>
                        <v-layout row>
                          <v-text-field 
                            type="number"
                            v-model="Search.pay_infos[0]"
                            label="最小"
                            :min=100
                            :max=5000000
                          ></v-text-field >
                          <v-text-field 
                            type="number"
                            v-model="Search.pay_infos[1]"
                            label="最大"
                            :min=100
                            :max=5000000
                          ></v-text-field >
                        </v-layout>
                      </v-flex>
                    </div>
                  </v-flex>
                  <v-flex xs12>
                    <v-btn large @click="searchCompanyEmploymentList" color="success"> 
                      <v-icon>search</v-icon>
                      検索する
                    </v-btn>
                    <v-btn large @click="initializeSearchReq">
                      <v-icon>search</v-icon>
                      検索条件戻す
                    </v-btn>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-container>
          </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>