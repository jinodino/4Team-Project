<script>
    import {Radar} from 'vue-chartjs';
    export default {
        extends:Radar,
        mounted() {
            this.renderChart({

                labels  : ["hi","a","sd","f"],
                datasets: 
                [
                    {
                        label : 'dd',
                        backgroundColor: "rgba(200,0,200,0.2)",
                        data  : [5,3,6,3],
                    }
                ]
            },
            {
                responsive: true,
                maintainAspectRatio: false,
                fontFamily : "NotoSans",
                title : {
                    display : true,
                    text : "Skill Sheet",
                    size : 15,
                },
                scale : {
                    ticks : {
                        min : 1,
                        max : 7,
                        beginAtZero : false,
                        stepSize : 1,
                    }
                },
                labels : {
                    display : false,
                }   
            });
        },
        methods : {
            chart(){
                  
            }
        }
    }
</script>