
<template>
    <v-container fluid>
        <company-info :orgCompanyId="org_company_id"></company-info>
        <employment-info v-if="employment_id != null" :employmentId="employment_id"></employment-info>
    </v-container>
</template>

<script>
import CompanyInfo from "../../shared/CompanyInfo";
import EmploymentInfo from "../../shared/Recruit";

export default {
  components : {
    "company-info": CompanyInfo,
    "employment-info": EmploymentInfo
  },

  data : () => ({
    employment_id  : "",
    org_company_id : "",
  })
}
</script>