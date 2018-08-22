<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <v-layout row wrap>
            
            <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                    <v-card-title primary-title class="justify-center headline" style="font-weight:bold;" >
                        <v-icon color="green" medium>accessibility</v-icon> &nbsp; 종료된 채용공고
                    </v-card-title>

                     <v-data-table
                    :headers="overNoticesHeader"
                    :items="overNotices"
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props" >
                            <tr @click="getNominatedInfo(props.item.employment_id,props.item.interview_id)">
                                <td>{{ props.item.org_agent_name }}</td>
                                <td>{{ props.item.org_name}}</td>
                                <td>{{ props.item.employment_name }}</td>
                                <td>{{ props.item.desired_employee_content }}</td>
                                <td>{{ props.item.working_area }}</td>
                                <td>{{ props.item.pay_min}} ~ {{props.item.pay_max}}</td>
                                <td>{{ props.item.applied}} ({{props.item.nominated}})</td>
                            </tr>
                        </template>
                    </v-data-table>                    
                </v-card>
            </v-flex>

            <v-flex d-flex xs12 sm12 md12 lg12 v-show="showNominatedStdList">
                <v-card>
                    <v-card-title primary-title class="justify-center headline" style="font-weight:bold;" >
                        <v-icon color="green" medium>accessibility</v-icon> &nbsp;  내정 학생
                    </v-card-title>

                    <v-card-title>
                        <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="nominatedStdSearch"
                        ></v-text-field>
                    </v-card-title>     

                     <v-data-table
                    :headers="nominatedStdHeader"
                    :items="nominatedStds"
                    :search="nominatedStdSearch"
                    class="elevation-1"
                    >
                        <template slot="items" slot-scope="props" >
                            <tr>
                                <td>{{ props.item.name_eng}} ({{props.item.name_kana}})</td>
                                
                                <td v-if="props.item.professor_acceptance_ox == 'o'">
                                    <v-chip outline color="blue">Accept</v-chip>
                                </td>

                                <td v-else-if="props.item.professor_acceptance_ox == 'x'">
                                    <v-chip outline color="red">Resign</v-chip>
                                </td>

                                <td v-else>
                                    <v-chip outline color="green">Yet</v-chip>
                                </td>
                            </tr>
                        </template>
                    </v-data-table>                    
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Chart from "./nominateChart.vue";

    export default{
        
        components : {
            "nominated-chart" : Chart,
        },

        data(){
            return {
                
                /**
                 * @desc get from server
                 */
                overNotices   : null,
                nominatedStds : null,

                /**
                 * @desc over notices table header
                 */
                overNoticesHeader    : 
                [   
                    { text : "Agent"             , align : "left"     , value : "org_agent_name" },
                    { text : "School"            , align : "left"     , value : "org_name" },
                    { text : "Title"             , align : "left"     , value : "employment_name" },
                    { text : "Department"        , align : "left"     , value : "desired_employee_content" },
                    { text : "Working Area"      , align : "left"     , value : "working_area" },
                    { text : "Pay"               , align : "left"     , value : "pay_max" },
                    { text : "Nominate"          , align : "left"     , value : "nominated" },
                ],

                /**
                 * @desc nomnated students table header & search text
                 */
                nominatedStdSearch : null,
                nominatedStdHeader : 
                [
                    { text : "Name" , align : "left" , value : "name_eng" },
                    { text : "Status" , align : "left" , value : "professor_acceptance_ox" },
                ]
            }
        },
        methods : {
            getNominatedInfo : function ( notice,interview ) {
                let reqHttpAddr = "/nominatedStdList";
                let sendData    = { requester : this.$store.getters.identification,
                                    notice    : notice,
                                    interview : interview  };

                this.axios.post( reqHttpAddr , sendData ).then( res => {
                    
                    if(!res.data) return;
                    console.log(res.data);
                    this.nominatedStds = res.data;

                }).catch( err => {
                    console.log(err.message);
                })
            }
        },
        created : function () {

            let reqHttpAddr = "/nominatedInfo";
            let sendData    = { requester : this.$store.getters.identification }

            this.axios.post(reqHttpAddr , sendData).then( res => {

                if (!res.data) return;

                for(let i = 0 ; i < res.data.matching_data.length ; i++){
                    res.data.matching_data[i].applied   = res.data.nominated[i].std;
                    res.data.matching_data[i].nominated = res.data.acceptNominate[i].std; 
                }

                this.overNotices = res.data.matching_data;

            }).catch( err => {
                console.log(err.message);
            })
        }
    }
</script>
