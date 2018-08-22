   
<template>
    <company-list :orgId ="org_college_id" v-if="org_college_id != null"></company-list>
</template>

<script>
import CompanyList from "../../shared/CompanyEmploymentListUp";

export default {
  components: {
    "company-list": CompanyList,
    

  },

  data: () => ({
    org_college_id: null,

  }),

  created: function() {
    this.Find_org_college_id();
  },

  methods: {
    Find_org_college_id: function() {
      let reqHttpAddr = "/professor_profile";
      let sendData = {
        professorId: this.$store.getters.identification
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log("res.data");
          console.log(res.data[0].schoolId);
          this.org_college_id = res.data[0].schoolId;
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>

<style>
</style>