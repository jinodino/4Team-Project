<script>
import { Radar } from 'vue-chartjs'

export default {
    extends: Radar,

    props : [
        'labels',
        'levels',
    ],
    methods : {
        chart : function (){
            this.renderChart(
            {
            
                labels  : this.labels,
                datasets: 
                [
                    {
                        label : '最上(7)  上(6)  中上(5)  中(4)  中下(3)  下(2)  最下(1)',
                        backgroundColor: "rgba(0, 128, 128,0.3)",
                        data  :  this.levels
                    }
                ]
            },
            
            {
                responsive: false,
                maintainAspectRatio: false,
                scale : {
                    ticks : {
                        min : 0,
                        max : 7,
                        beginAtZero : false,
                        stepSize : 1,
                    }
                },
              

            });
    },
    
    },
    mounted() {
        console.log("text")
        this.chart();
    },
    watch : {
        levels : function(data){

            this.chart();
        }
    }

}
</script>
