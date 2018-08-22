<script>
    import {Doughnut} from "vue-chartjs";
    export default{

        props :[
            "nominateData"
        ],
        data(){
            return{

            }
        },
        methods : {
            draw : function () {
                this.renderChart({
                    labels   : this.labels, 
                    datasets :  [
                                    {
                                        backgroundColor : [rgba(200,0,0,0.3) , rgba(0,0,200,0.3)],
                                        data            : this.values
                                    }
                                ]
                    }, 
                    {
                        responsive: true, 
                        maintainAspectRatio: false, 
                    }
                )
            }
        },
        mounted : {

        },
        watch : {
            
        }

    }

</script>