<template>
<v-container fluid grid-list-md>
  <v-layout row>
    <v-flex xs12 md12 lg12>
        <v-layout>
            <v-flex xs12 md8 lg8>
                <v-card color="white" height="100%">
                    <h2>PROFILE</h2>    
                    <!--  Form : UserID, School 수정불가 -->
                    <v-layout wrap style="margin:3%">
                        <v-flex xs12 md12 lg12>
                            <b-form-group 
                                label="User ID:">
                                <b-form-input 
                                    type="text"
                                    disabled
                                    v-model="login_id">
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                
                        <!-- Form : english, japan, katakana -->

                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="Name:">
                                <b-form-input 
                                    type="text"
                                    v-model="name"
                                    :disabled = 'item_disabled'
                                    v-validate="'required'">                            
                                </b-form-input>
                            </b-form-group>
                        </v-flex>               

                        <v-flex xs6 sm6 md6 lg6> 
                            <b-form-group 
                                label="katakanaName:">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="name_kana"
                                    :disabled = 'item_disabled'
                                    v-validate="'required'">     
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <!-- Form : e-mail -->
                        <v-flex xs12 sm12 md12 lg12>
                            <b-form-group
                                label="E-mail:">
                                <b-form-input id="Name"
                                    type="text"
                                    v-model="email"
                                    :disabled = 'item_disabled'
                                    v-validate="'required'">                        
                                </b-form-input>
                            </b-form-group>
                        </v-flex>
                        <!-- Form : birth, major -->
                        <v-flex xs2 sm2 md2 lg2>
                            <b-form-group
                                    label="Birth Year:">
                                    <b-form-input
                                        type="text"
                                        v-model="birth.y"
                                        :disabled = 'item_disabled'
                                        v-validate="'required'">                            
                                    </b-form-input>
                                </b-form-group>
                        </v-flex>
                        <v-flex xs2 sm2 md2 lg2>
                            <b-form-group
                                    label="Birth Month:">
                                    <b-form-input
                                        type="text"
                                        v-model="birth.m"
                                        :disabled = 'item_disabled'
                                        v-validate="'required'">                            
                                    </b-form-input>
                                </b-form-group>
                        </v-flex>
                        <v-flex xs2 sm2 md2 lg2>
                            <b-form-group
                                    label="Birth Day:">
                                    <b-form-input
                                        type="text"
                                        v-model="birth.d"
                                        :disabled = 'item_disabled'
                                        v-validate="'required'">                            
                                    </b-form-input>
                                </b-form-group>
                        </v-flex>
                        
                        <v-flex xs12 sm12 md12 lg12 text-xs-right>
                            <v-btn v-if="item_disabled == false" @click="item_disabled = !item_disabled" color="success" type="submit">Save</v-btn>
                            <v-btn v-else @click="item_disabled = !item_disabled" color="success" type="submit">edit</v-btn>
                        </v-flex>                           
                    </v-layout>
                </v-card>                
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
                <v-card color="white" height="100%">
                    <v-layout wrap row>
                        <v-flex xs12 sm12 md12 lg12>   
                            <v-card-text style="text-align :center;" >
                                <div>
                                    <v-avatar responsive :size=130>
                                        <img v-if="!profile_photo_url" :src="src ? src : '/profileImages2/nullImage.JPG'" class="img-circle userImg">
                                        <img v-else :src= "profile_photo_url" class="img-circle userImg">
                                            
                                    </v-avatar>
                                </div>
                                <div style="padding:10%">
                                    <h3>{{name}}</h3>
                                    <h5>{{email}}</h5>
                                </div>
                            </v-card-text>                 
                        </v-flex>
                        <v-flex xs12 sm12 md12 lg12 text-xs-right style="margin:5%; margin-top:70%">   
                            <label for="file_input"><v-icon>settings</v-icon></label>                        
                            <input v-show="false" accept=".jpg, .jpeg, .png" id="file_input" type="file" @change="onFileChange"/>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex> 
        </v-layout>
        </v-flex>


  </v-layout>
</v-container>
</template>

<style>
    .input-file {
        opacity: 0;
        width: 0;
        height: 0;
    }

</style>

<script>
  export default {
    data : () =>({
        item_disabled : false,
        login_id : 'root',
        name : '矢野',
        name_kana : 'ヤノ',
        email : 'yanoemail@email.halo',
        profile_photo_url:'https://png.pngtree.com/element_origin_min_pic/16/07/09/205780ec8d7cd7e.jpg',
        birth_date : '1995-10-01',
        birth : {
            y : "1995",
            m : "10",
            d : "01"
        },
    }),
    methods : {
        //프로필 사진 수정
            onFileChange(e){
            
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return;
                this.createImage(files[0]);

           // this.uploadImage();
            },

            createImage(file){
                var image = new Image();
                var reader= new FileReader();

                reader.onload = (e)=>{
                    this.profile_photo_url = e.target.result;
                };
                reader.readAsDataURL(file);
            },
    }
}
</script>