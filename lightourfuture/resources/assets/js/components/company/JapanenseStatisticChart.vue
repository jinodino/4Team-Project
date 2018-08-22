<script>
import { Bar } from 'vue-chartjs'

export default {
    extends : Bar,

    props : [
        "jlpt",
        "max"
    ],
    
    methods : {
        chart : function (){
            this.renderChart(
            {
                
                labels: [ "JLPT 3級" , "JLPT 2級", "JLPT 1級"],
                datasets: [
                    {
                        label: 'JLPT 習得現況',
                        backgroundColor: '#008080',
                        pointBackgroundColor: 'white',
                        borderWidth:1,
                        pointBorderColor: '#000000',
                        data: this.jlpt
                    }
                ]
            },
            {
                scales: {
                    yAxes: [{
                        ticks: { 
                            min : 0,
                            max : this.max,
                            stepSize : 5,
                        },
                        gridLines: { display: true }
                    }],
                    xAxes: [{
                        ticks: { 
                            stepsize : 5,
                            beginAtZero: true
                        },
                        gridLines: { display: false }
                    }]
                },
                legend: { display: false },
                tooltips: {
                    enabled: true,
                    mode: 'single',
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return tooltipItems.yLabel + " 名";
                        }
                    }
                },
                responsive : false,
                maintainAspectRatio : false,
                height: 40,
            }
          );
        },      
    
    },
    mounted() {
        this.chart();
    },
    watch : {
        jlpt : function(data){
            this.chart();
        },
        max : function (data) {
            this.chart();
        }
    }

}
</script>
