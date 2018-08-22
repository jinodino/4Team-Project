<template>
    <v-container>
        <v-tabs
            slot="extension"
            v-model="tab"
            grow
            fixed-tabs
        >
        
            <v-layout>  
                <v-flex xs6 style="margin-left:2px">
                    <div v-if="tab == '0'" @click="tab = '0'" id="tabon" class="title">{{user.affiliation[0].org_name}}</div>
                    <div v-else @click="tab = '0'" id="taboff" >{{user.affiliation[0].org_name}}</div>  
                </v-flex>
                <v-flex xs6 style="margin-right:2px">
                    <div v-if="tab == '1'" @click="tab = '1'" id="tabon" class="title">{{user.affiliation[1].org_name}}</div>
                    <div v-else @click="tab = '1'" id="taboff" >{{user.affiliation[1].org_name}}</div>
                </v-flex>
            </v-layout>
            <!-- <v-tab v-for="org_agent in user.affiliation" :key="org_agent.no">
                <p class="display-1">Agent{{org_agent.no}}</p>
            </v-tab> -->
        </v-tabs>
      
        <v-tabs-items v-model="tab">
            <v-tab-item v-for="org_agent in user.affiliation" :key="org_agent.no.key">
                <agent-address-book :orgAgentId="org_agent.org_agent_id" :orgAgent="org_agent"></agent-address-book> 
            </v-tab-item>
        </v-tabs-items>
    </v-container>
  <!-- <div class="container-fluid">
    <div class="row text-center section">
      <div class="col-xs-6  col-md-6"><h1>회원 초대</h1></div>
    </div>
    <div>

      교수 초대
      <v-data-table
      id="inspire"
        v-model="selected"
        :headers="headers"
        :items="school_items"
        select-all
        :pagination.sync="pagination"
        item-key="text"
        class="elevation-1"
      >
        <template slot="headers" slot-scope="props" class="row text-center section">
          <tr>
            <th
              v-for="header in props.headers"
              :key="header.text"
              class="text-xs-center"
            >
              {{ header.text }}
            </th>
          </tr>
        </template>
        <template slot="items" slot-scope="props">
          <tr :active="props.selected" >
            <td>
              <v-checkbox
                @click="props.selected = !props.selected" 
                primary
                hide-details
                :input-value="props.selected"
                v-if="props.item.join == 'X'"
              ></v-checkbox>
            </td>
            <td class="text-xs-center">{{ props.item.text }}</td>
            <td class="text-xs-center">{{ props.item.name }}</td>
            <td class="text-xs-center">{{ props.item.inclusion }}</td>
            <td class="text-xs-center">{{ props.item.classification }}</td>
            <td class="text-xs-center">{{ props.item.join }}</td>
          </tr>
        </template>
      </v-data-table>
    </div>




    <div>
      기업 회원 초대
      <v-data-table
      id="inspire"
        v-model="selected"
        :headers="headers"
        :items="company_items"
        select-all
        :pagination.sync="pagination"
        item-key="text"
        class="elevation-1"
      >
        <template slot="headers" slot-scope="props" class="row text-center section">
          <tr>
            <th
              v-for="header in props.headers"
              :key="header.text"
              class="text-xs-center"
            >
              {{ header.text }}
            </th>
          </tr>
        </template>
        <template slot="items" slot-scope="props">
          <tr :active="props.selected" >
            <td>
              <v-checkbox
                @click="props.selected = !props.selected" 
                primary
                hide-details
                :input-value="props.selected"
                v-if="props.item.join == 'X'"
              ></v-checkbox>
            </td>
            <td class="text-xs-center">{{ props.item.text }}</td>
            <td class="text-xs-center">{{ props.item.name }}</td>
            <td class="text-xs-center">{{ props.item.inclusion }}</td>
            <td class="text-xs-center">{{ props.item.classification }}</td>
            <td class="text-xs-center">{{ props.item.join }}</td>
          </tr>
        </template>
      </v-data-table>
    </div>
    <div class="text-left">
      <button type="button" class="btn btn-primary pull-right col-md-1" @click="mailsend()">{{ sebmit }}</button>
      <button type="button" class="btn btn-primary pull-right col-md-1" @click="dialog = !dialog">{{ register }}</button>
    </div>
    <v-dialog v-model="dialog" persistent width="50%">
      <v-card>
          <v-card-text> 
              <h1>주소록 등록</h1>
             <emailregist></emailregist>
          </v-card-text>
      </v-card>
    </v-dialog>
  </div> -->


</template>

<script>

import AddressBook from './UserInvite_address_book';

export default {
  components:{ 'agent-address-book' : AddressBook },

    created : function (){
        this.user.login_id = this.$store.getters.identification;
        this.user.classification = this.$store.getters.classification;

        //소속 에이전트 기관 가져오기
        let user = this.user;
        this.axios.post('/agent/getOrgs', {user})
        .then(rep=>{
            this.user.affiliation = rep.data;
        })
        .catch(ex=>{
        console.log(ex);
        });

    },

    data : ()=>({
        user : {
            login_id : null,
            classification : null,
            affiliation : [],
        },

        tab : "0",
  }),

  methods: {
    StoggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.school_items.slice()
    },
    CtoggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.company_items.slice()
    },
    changeSort (column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending
      } else {
        this.pagination.sortBy = column
        this.pagination.descending = false
      }
    },
    mailsend() {
      let url = "/MailSned";
      let info = { info : this.selected};
      this.axios.post(url, info).then((res) => {
          //this.result = response.data;
          if(!res.data) return;

          alert('전송되었습니다');
      });
    }
  }
}
</script>


<style scoped lang="css" src="../../static/css/agent/agnetAndStudentStyleSheet.css"></style>