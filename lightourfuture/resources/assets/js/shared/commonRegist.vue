<template>
    <div class="register-box">
        <b-form @submit="register" v-if="show">
            <b-form-group id="exampleInputGroup1"
                    label="ID:"
                    label-for="exampleInput1"
                    >
                <b-form-input id="exampleInput1"
                        v-model="form.id"
                        required
                        placeholder="Enter ID">
                </b-form-input>
                <b-button @click="isAssignedId()" variant="success"> Duplicate? </b-button>
            </b-form-group>
            
            <b-form-group id="exampleInputGroup2"
                    label="Password:"
                    label-for="exampleInput2">
                <b-form-input id="exampleInput2"
                      type="password"
                      v-model="form.pwd"
                      required
                      placeholder="Enter Password">
        </b-form-input>
      </b-form-group>
     
      <b-button type="submit" variant="primary"> Register </b-button>
    </b-form>

    </div>
</template>

<style>
.register-box{
    position:absolute;
    top:50%;
    left:50%;
    width:30vw;
    height:20vw;
    overflow:hidden;
    /* background-color: bisque; */
    margin-top:-10vw;
    margin-left:-16vw;
}
</style>

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
        form: {
            id: '',
            pwd: '',
            classification : null,
            inclusion : null,
            agent : null,
            no    : null,
        },
        show: true
    }),
    created() {
       
        this.urlcatch();
        this.urlcheck();
        
    },
    methods: {
        urlcatch() 
         {           
            // 현재 url 값 넣어주는 메서드
            this.pullurl =  document.location.hash.split('/');
            console.log(this.pullurl);
            console.log("----");
            console.log(this.pullurl[6]);
        },

        urlcheck()
        {
            // url 체크 
            let url = '/confirm/' + this.pullurl[2] + '/' + this.pullurl[3];
            this.axios.get(url).then( (res) => {
                
                this.result = res.data;
                if(!res.data) {
                    alert('나가');
                    this.$router.push({name: '404'})
                }else {
                    //console.log(res.data);
                    
                }
            }).catch((ex)=> {
                console.log('failed', ex)
            });
            
        },
        
        register : function (){

            // classicication 
            this.form.classification = this.pullurl[3]
            // inclusion 
            this.form.inclusion = this.pullurl[4]
            

            if(typeof(this.pullurl[5]) != 'undefined') {
                // 초대 에이전트
                this.form.agent = this.pullurl[5]
            }

            // No 고유 식별 값
            this.form.no = this.pullurl[6]

            let reqHttpAddr = "/umc";
            let data  = {registerInfo : this.form};

            this.axios.post(reqHttpAddr, data)
            .then (res => {
                
                if( !res.data ) return false;

                alert("Register success Please Login");
                this.$router.push({path:"/"});
            }).catch( error => {
                console.log(error.message)
            })
        },
        onSubmit (evt) {
            evt.preventDefault();
            alert(JSON.stringify(this.form));
        },     
        
        isAssignedId (){
            
            if( !this.form.id ){
                return;
            }

            let reqHttpAddr = "/postUserIdDuplicates";
            let sendData = { id : this.form.id};
        
            this.axios.post(reqHttpAddr , sendData)
            .then( res => {
                console.log(res);
                if( !res.data ){
                    alert("Duplicated Id");
                    return;
                }

                alert("Valid Id");

            }).catch( err => {
                console.log(err.mesage);
            })
        }
    }
}
</script>


