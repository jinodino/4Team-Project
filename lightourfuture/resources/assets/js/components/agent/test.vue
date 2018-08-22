<!-- User Table Test REST Controller -->
<template>
    <div>
        
        <!-- <form v-on:submit.prevent="fetchItems">
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                    <label>USER ID:</label>
                    <input type="text" class="form-control" v-model="info.id">
                </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                    <label>CLASSIFICATION:</label>
                    <input type="text" class="form-control col-md-6" v-model="info.classification" />
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                    <label>PASSWORD:</label>
                    <input type="text" class="form-control col-md-6" v-model="info.password" />
                    </div>
                </div>
                </div><br />
                <div class="form-group">
                <button class="btn btn-primary">Add Item</button>
            </div>
        </form>
         -->
        <form v-on:submit.prevent="fetchItems">
            <div class="form-group">
                <label>검색 컬럼</label>
                <input type="text" class="form-control" v-model="search.type">
            </div>

            <div class="form-group">
                <label name="product_price">검색</label>
                <input type="text" class="form-control" v-model="search.contents">
            </div>

            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>

        <form v-on:submit.prevent="college_searchs">
            <div class="form-group">
                <label>학교명</label>
                <input type="text" class="form-control" v-model="college_search.college_name">
            </div>
            
            
            <label name="product_price">지역</label><br>
            <div class="form-group">   
                한국<input type="checkbox" class="form-control" value="KR" v-model="college_search.college_country">
                일본<input type="checkbox" class="form-control" value="JP" v-model="college_search.college_country">
                중국<input type="checkbox" class="form-control" value="CN" v-model="college_search.college_country">
                미국<input type="checkbox" class="form-control" value="US" v-model="college_search.college_country">
            </div>

            <div class="form-group">
                <label>대학 타입</label><br>
                4년제<input type="checkbox" class="form-control" value="ilbanndae" v-model="college_search.college_type">
                전문제<input type="checkbox" class="form-control" value="junmundae" v-model="college_search.college_type">
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>

        <table class="table table-hover">
            <tr v-for="(results, key, index) in result" :key="index">
                <td v-for="(search_info, key, index) in results" :key="index">
                    {{search_info}}
                </td>
                <td>
                    {{results.country_code}}
                </td>
            </tr>
            
        </table>
        
    </div>
</template>
<script>

    export default {
        data(){
            return{
                info:{},
                search:{},
                result:{},
                
                college_search:{
                    
                    college_country:[],
                    college_type:[],
                }
            }
        },

        methods: {
            fetchItems()
            {
                let uri = '/test';
                //let uri = '/test_search';
                this.axios.post(uri, this.search).then((response) => {
                    this.result = response.data;
                    //alert(response.data);
                });
            },
            college_searchs()
            {
                //let uri = '/test';
                let uri = '/test_search';
                this.axios.post(uri, this.college_search).then((response) => {
                    //this.result = response.data;
                    alert(response.data);
                });
            },
        }
    }
    
</script>