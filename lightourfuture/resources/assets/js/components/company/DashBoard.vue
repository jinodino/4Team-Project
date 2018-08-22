<template>
    <v-container fluid>

        <div>
            <h1>{{this.applySummary}}</h1><br> <hr><hr><hr>
            <h1>{{this.matchedSchools}}</h1><br>
            <h1>{{this.countryList}}</h1>
            <!-- Company Profile Image -->
            <!-- Notifications -->
            <!-- Recrut Students Number -->
            <!-- Interview Schedule -->
            <!--  -->
        </div>    
    </v-container>
</template>

<script>
    export default {
        data (){
            return {
                currentRecrutingStatus : true,
                notificaitonList       : null,
                appliedStdNum          : null,
                applySummary           : null,
                matchedSchools         : null,
                countryList            : null,
            }
        },

        methods : {

            getApplySummarySchool : function () {
                
                let reqHttpAddr = "/collegeMatchingEmploymentList";
                let sendData    = { requester : this.$store.getters.identification };

                this.axios.post(reqHttpAddr, sendData).then(res => {

                    if(!res.data) return ;

                    this.applySummary = res.data;
                    
                }).catch(err => {
                    console.log(err.message);
                })
            },

            getMatchedCountryAndSchools : function (){
                let reqHttpAddr = "/countryMatchingCollegeList";
                let sendData    = {requester : this.$store.getters.identification };

                this.axios.post(reqHttpAddr , sendData).then( res => {
                    if(!res.data) return;

                    this.matchedSchools = res.data;

                }).catch( err => {
                    console.log(err.message);
                })
            },
            /**
             * @desc when user clicked change Recruting Status button then send Request Mail To Agent
             * @param  { String }  currentCompanyIdentification
             * @return { Boolean }
             */
            reqChangeRecruiteStatusToAgent : function (currentCompanyIdentification){
                // let reqHttpAddr   = "";
                // let identificiton = currentCompanyIdentification;
                
                // this.axios.post()
                // .then( res => {

                //     if( !res.data ) {
                //         alert("false")
                //         return false;
                //     }

                //     alert("request Success");
                // }).error( err => {
                //     console.log(err.message);
                // })
            },    

            
        },

        

        created : function (){
            this.getMatchedCountryAndSchools();
            console.log(this.$store.getters)
            console.log(this.$store.getters.classification)
        }

    }
</script>
