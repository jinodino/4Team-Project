<script>
import { Pie } from 'vue-chartjs'
export default {
  extends: Pie,
  data () {
     return {
        select: "",
        values: [],
        labels: [],
        info : [
           
        ],
        stdinfo:[
           
        ], 
        stdInterviewInfo:[],
        index:""
    }
  }, 
  mounted : function(){ 

    //현재 페이지 url 받아오기
    var link = location.hash; 

    var response = link.split("/");
    
    if(response[2] == "applyManagement"){
        this.companyList();
    }else{
        this.interveiwSituation();
    }

    },

    methods: {  
        chart : function (){
            this.renderChart({
                labels  : this.labels, 
                datasets:   [
                                {
                                    backgroundColor : ['#f87979', '#ff0000', '#00ff00', '#0000ff'],
                                    data            : this.values
                                }
                            ]
                }, 
                {
                    responsive: true, 
                    maintainAspectRatio: false, 
                    onClick:this.handle
                }
            )
        },
        handle (point, event) {

            var link = location.hash; 
            var response = link.split("/");

            const item = event[0];
            this.select = this.labels[item._index];
            if(response[2] == "applyManagement"){
                let reqHttpAddr = "/professor_interview_info_list"; 
                let sendData    =   {
                                        professorId     : this.professorId, //교수ID
                                        PageName        : response[2],           //현재 페이지 
                                        select          : this.select       //선택한 기업 || 선택 진행여부
                                    };  

                this.axios.post(reqHttpAddr, sendData)
                .then((res) => {
                    this.info       = res.data.info; //기업면접 날짜, 시간, 장소
                    this.stdinfo    = res.data.std; //기업에 지원한 학생의 정보(이름, 아이디, 면접일(날짜, 시간, 장소), 일본어능력JPT, JLPT, 내정수, 면접 승인여부, 사진)
                    //{name:"rrr", interviewDate:"2018.05.30", time:"18:00", place:"300호", jlpt:"2급", jpt:"900", statusCount:"1",interview:"x",avatar:"/images/professor/jang.jpg"} 
                }).catch( (err) => {
                    console.log(err.message);
                });
            }else{ //현재페이지가 내정관리 페이지일 경우
                    console.log(item._index);
                    this.stdinfo = this.stdInterviewInfo[item._index];
                    this.index = item._index;
                    console.log(this.stdInterviewInfo[item._index]);
                    // this.stdinfo = this.stdInterviewInfo[]
                    //선택학생목록 (학생사진, 이름, 면접일정(회사이름, 지금까지 본 면접들(차수, 장소, 시간)))
                    //{name:"장다연", company:"soft" interviewDate:"2018.05.30", time:"18:00", place:"300호", avatar:"/images/professor/jang.jpg"}
                   
                }           
            if(response[2] == "applyManagement"){
                this.$emit('on-receive', {    
                    interviewInfo : this.info,
                    stdInfo       : this.stdinfo,
                    selectCompany : this.select
                });
            }else{
                this.$emit('on-receive', {    
                    stdInfo       : this.stdinfo,
                    type          : this.index
                });
            }
            
        },
        companyList(){

            //기업 목록, 면접 일정, 장소, 면접 지원 학생(이름, 면접차수, 면접시간) 받아오기 
            let reqHttpAddr = "/professor_newChart_companyInfo"; 
            let sendData    =   {
                                    professorId : this.professorId //교수ID
                                };  
            
            this.axios.post(reqHttpAddr, sendData)
            .then((res) => {
                //현재 페이지가 지원관리일 경우
                this.labels     = res.data.company; //company명
                this.values     = res.data.value;//기업에 지원한 학생 수
                this.chart();
            }).catch( (err) => {
                console.log(err.message);
            });
        },
        interveiwSituation : function(){

            this.labels=["면접 미진행", "면접진행", "내정1사 이상", "구직활동종료" ];
            this.values=[];
            /*
            면접 미진행     : 학생수, 학생이름
            면접 진행       : 학생수, 학생이름, 학생별 면접 진행중인 회사명
            내정1사이상     : 학생수, 학생이름, 학생별 면접 진행중인 회사명, 내정받은 회사
            구직활종 종료   : 학생수, 학생이름, 내정확정회사명
            */
            
            //내정현황 label에 대한 학생 수와 학생이름, 면접 진행중, 내정, 내정확정 회사 명 
            let reqHttpAddr = "/professor_statusManagement"; 
            let sendData    =   {
                                    professorId : this.professorId, //교수ID
                                };  
            this.axios.post(reqHttpAddr, sendData)
            .then((res) => {
                this.values = res.data.values; //label에 대한 학생 수 
                this.stdInterviewInfo = res.data.stdInterviewInfo;
                this.chart(); 
            }).catch( (err) => {
                console.log(err.message);
            });
        }
    },
}

</script>