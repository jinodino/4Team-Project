<template>
    <div>
        <h1> 확인중 </h1>

    </div>
</template>
<script>
/* 테스트용 
    var link = document.location.hash.substr(10, 216);
    var ss = link.split('%20');
    console.log(link);
*/

export default {
    data: () => ({
        result: "",
        pullurl: "",// url 담는 메서드

    }),
    created() {
       
        this.urlcatch();
        this.urlcheck();
        
    },
    methods: {
        urlcatch() 
        {           
            // 현재 url 값 넣어주는 메서드
            this.pullurl =  document.location.hash.substr(10, 216);
            console.log(this.pullurl);
        },

        urlcheck()
        {
         
            // url 체크 
            let url = '/confirm/' + this.pullurl;
            this.axios.get(url).then( (response) => {
                
                this.result= response.data;
                if(this.result.key) {
                    console.log(response.data);
                    alert('당첨');
                    //this.$router.push({name: 'excel'})
                }else {
                    alert('나가');
                    console.log(response.data);
                    //this.$router.push({name: '404'})
                }
            }).catch((ex)=> {
                console.log('failed', ex)
            });
            
        }

    }
}
</script>