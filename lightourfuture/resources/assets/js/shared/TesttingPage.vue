<template>
<v-app>
  <v-container  fluid grid-list-sm>
    <v-layout row v-for="(value, index) in info" :key="value">
        <v-flex xs12 md3 lg3 border text-center>
          NO. {{value.student_no}}   ssss
          <v-card-media src="https://vuetifyjs.com/static/doc-images/cards/desert.jpg" height="150px"></v-card-media>
          <v-btn xs12  md12 lg12 @click="disable(index)" >코멘트 저장</v-btn>
        </v-flex>
        <v-flex xs12 md9 lg9 border>
          <v-layout text-center>
            <v-flex xs2 md2 lg2 border>
              이름
            </v-flex>
            <v-flex xs5 md5 lg5 border>
              {{value.name_eng + " / " + value.name_kana}}
            </v-flex>
            <v-flex xs2 md2 lg2 border>
              국적/성별 
            </v-flex>
            <v-flex xs5 md5 lg5 border>
              
                {{value.country_code + " /"}}
                {{value.gender === "M" ? "남자" : "여자"}}
              
            </v-flex>
          </v-layout>

          <v-layout text-center>
            <v-flex xs2 md2 lg2 border>
              일어 능력
            </v-flex>
            <v-flex xs5 md5 lg5 border>
              
                {{"JPT : " + value.JPT + " / JLPT : " + value.JLPT}}
              
            </v-flex>
            <v-flex xs2 md2 lg2 border>
              채용 년도
            </v-flex>
            <v-flex xs5 md5 lg5 border>
              {{value.employ_year + "년"}}
            </v-flex>
          </v-layout>
          <v-layout>
            <v-flex xs12 md12 lg12>
              
                <!-- 코멘트 수정 등록 바뀌게 -->
                <v-text-field 
                multi-line 
                label="코멘트" 
                v-model="value.recommendation_comment"
                rows="4"
                >
                </v-text-field>
              
            </v-flex>
          </v-layout>     
        </v-flex>


    </v-layout>
  </v-container>
</v-app>
</template>

<script>
export default {
  data : ()=> ({
    disabled : ["s"],
    info : [
      {
        student_no : "4513842",
        name_eng : "KimChulsu",
        name_kana : "チョウス",
        country_code : "KR",
        gender : "M",
        JPT : "700",
        JLPT : "1",
        employ_year : "2018",
        recommendation_comment : "성적도 몸도 마음도 일본어도 매우매우매우 우수하디유. 야는 안델꼬 가문 정말정말 손 볼 거라는 건 알아두시는 것이 좋을 거시라유"
      },
      {
        student_no : "4513842",
        name_eng : "KimChulsu",
        name_kana : "チョウス",
        country_code : "KR",
        gender : "M",
        JPT : "700",
        JLPT : "1",
        employ_year : "2018",
        recommendation_comment : "성적도 몸도 마음도 일본어도 매우매우매우 우수하디유. 야는 안델꼬 가문 정말정말 손 볼 거라는 건 알아두시는 것이 좋을 거시라유"
      }
    ],
  }),
  methods : {
    disable(d) {
      alert(this.disabled[d]  + " : " + d);
      this.disabled[0] = !this.disabled[d];
    }
  }
}
</script>

<style>
</style>
