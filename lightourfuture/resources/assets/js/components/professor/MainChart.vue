<script>
import { Pie } from "vue-chartjs";

export default {
  extends: Pie,

  data() {
    return {
      values: [],
      labels: ["상장기업", "비상장기업"]
    };
  },

  mounted: function() {
    this.companyCount();
    // this.chart();
  },

  methods: {
    chart: function() {
      this.renderChart(
        {
          labels: this.labels,
          datasets: [
            {
              backgroundColor: ["#008080", "blue"],
              data: this.values
            }
          ]
        },
        {
          height: 10,
          responsive: false,
          maintainAspectRatio: false
        //     responsive: true,
        //     maintainAspectRatio: false,
        }
      );
    },

    //정보 받아오기 내정 준 회사
    companyCount: function() {
      let reqHttpAddr = "/professor_home_main_chart";
      let sendData = {
        professorId: this.$store.getters.identification //교수ID
      };

      this.axios
        .post(reqHttpAddr, sendData)
        .then(res => {
          console.log(res.data);
          this.values.push(res.data.listed_company_o);
          this.values.push(res.data.listed_company_x); //'상장기업수', '비상장기업수'
          console.log("this.values");
          console.log(this.values);
          this.chart();
        })
        .catch(err => {
          console.log(err.message);
        });
    }
  }
};
</script>