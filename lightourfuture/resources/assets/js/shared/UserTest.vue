<!-- User Table Test REST Controller -->
<template>
    <div>
        <form v-on:submit.prevent="addItem">
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                    <label>USER ID:</label>
                    <input type="text" class="form-control" v-model="create_id">
                </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <label>CLASSIFICATION:</label>
                    <input type="text" class="form-control col-md-6" v-model="create_classi" />
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                    <label>PASSWORD:</label>
                    <input type="text" class="form-control col-md-6" v-model="create_password" />
                    </div>
                </div>
                </div><br />
                <div class="form-group">
                <button class="btn btn-primary">Add Item</button>
            </div>
        </form>
        <table class="table table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>Classification</td>
                <td>join_date</td>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.login_id" >
                    <td>{{ item.login_id }}</td>
                    <td>{{ item.classification }}</td>
                    <td>{{ item.join_date }}</td>
                    <td><button class="btn btn-danger" v-on:click="getItem(item.login_id)">Edit</button></td>
                    <td><button class="btn btn-danger" v-on:click="deleteItem(item.login_id)">Delete</button></td>
                </tr>
            </tbody>
        </table>
        <form v-on:submit.prevent="updateItem">
            <div class="form-group">
                <label>NOW ID</label>
                <input type="text" class="form-control" :value='this.now_id'>
            </div>

            <div class="form-group">
                <label name="product_price">CHANGE PASSWORD</label>
                <input type="text" class="form-control" v-model="change_password">
            </div>

            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
        <input type="button" value="세션확인" v-on:click="sessiontest()">
        <input type="button" value="세션확인" v-on:click="sessionuser()">
        <input type="button" value="세션확인" v-on:click="sessionde()">
    </div>
</template>
<script>

    export default {
        data(){
            return{
                items  : [],
                item : [],
                create_id : '',
                create_classi : '',
                create_password : '',
                now_id : '',
                change_password : '',
            }
        },

        created: function()
        {
            this.fetchItems();
        },

        methods: {
            sessionde() {
                let uri = '/sessionde';
                this.axios.get(uri).then((response) => {
                    console.log(response);
                    
                });
            },
            sessionuser() {
                let uri = '/sessionuser';
                this.axios.get(uri).then((response) => {
                    console.log(response);
                    
                });
            },
            sessiontest() {
                let uri = '/sessiontest';
                this.axios.get(uri).then((response) => {
                    console.log(response);
                    
                });
            },
            fetchItems()
            {
                let uri = 'http://127.0.0.1:8000/umc';
                this.axios.get(uri).then((response) => {
                    this.items = response.data;
                    
                });
            },
            deleteItem(id)
            {
                let uri = `http://127.0.0.1:8000/umc/${id}`;
                console.log(id);
                this.items.splice(id, 1);
                this.axios.delete(uri);
            },
            addItem(){
                let uri = 'http://127.0.0.1:8000/umc';
                let info = {
                    'id' : this.create_id,
                    'classi' : this.create_classi,
                    'password' : this.create_password,
                }
                this.axios.post(uri, info).then((response) => {
                    this.$router.push({name: 'usertest'})
                })
            },
             getItem(id)
            {
              let uri = `http://127.0.0.1:8000/umc/${id}/edit`;
                this.axios.get(uri).then((response) => {
                    this.item = response.data;
                    this.now_id = this.item[0].login_id;
                    console.log(this.item[0].login_id);
                    //this.$router.push({name: 'usertest'});
                });
            },

            updateItem(id)
            {
                let info = {
                    'change_password' : this.change_password,
                }
                let uri = 'http://127.0.0.1:8000/umc/' + this.now_id;
                    this.axios.patch(uri, info).then((response) => {
                     this.$router.push({name: 'usertest'});
                    });
            }
        }
    }
    
</script>