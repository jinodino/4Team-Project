<template>
    <div>
        <h1>mail</h1>
        <form class="form-inline" v-on:submit.prevent="postLoginUserr">
          <div class="form-group">
           <label for="exampleInputName2">Name</label>
           <input type="text" class="form-control" id="exampleInputName2" placeholder="Jane Doe" v-model="name">
          </div>
          <div class="form-group">
           <label for="exampleInputEmail2">Email</label>
           <input type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com" v-model="address">
          </div>
          <button class="btn btn-primary">SdNED</button>
        </form>
        <br />
        <br />
        <div class="row">
          <div class="col-md-10"></div>
          <div class="col-md-2">
          </div>
        </div>
    </div>
</template>
<script>
  export default {
    data(){
        return{
            name: '',
            address: ''
        }
    },
    // created() {
    //   this.addItem();
    // },
    methods: {
      addItem(){
        // let uri = 'http://localhost:8000/email/' + this.mes;
        // this.axios.get(uri).then((response) => {
        //   //this.$router.push({name: 'emailresult'})
        // })
    },
    
    postLoginUserr : function(){
                // request url
                
                let hajae = {
                    'address': this.address,
                    'name' : this.name
                };

                let url = '/MailSned';
                this.axios.post(url, hajae).then( (response) => {
                    this.postLoginUser = response;

                }).catch((ex)=> {
                    console.log('failed', ex)
                });
            }
  }
}
</script>