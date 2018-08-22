

<template>
    <div id="view-port">
        <!-- nav menu -->
        <transition name="fade">
            <router-view name="index"/>
            <router-view name="login"/>
            <router-view name="stdRegist"/>
            <router-view name="findAccount"/>
            <router-view name="testmap"/>
            <router-view name="a"/>
            <router-view name="commonRegist"/>
        </transition>

        <transition name="fade">
            <router-view :user = "user" :navItems = "navItems"/>
            <router-view name="grid"/>
            <router-view name="iin"/>
            <router-view name="notFound"/>
            <router-view name="toolbar"/>
            <router-view name="chart"/>
            <router-view name ="navbarTest"/>
            <router-view name="usertest"/>
            <router-view name="atagTest"/>
        </transition>
    </div>
</template>

<style>

html {
    width:100%;
    height:100%;
    padding:0;
    margin: 0;
    overflow: auto;
}

#view-port {
    width : 100%;
    height: 100%;
    padding:0;
    margin: 0;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity .8s;
}
.fade-enter, .fade-leave-active {
    opacity: 0;
}

</style>
<script>

import SidebarList from '../static/dataProvide/SidebarList.js';

export default {

    data : ()=> ({
        user : null,
        navItems : null,
    }),


    // methods : {
    //     changeMenu : function (user){
    //         switch(user){
    //             case 'student': 
    //                 this.navItems = SidebarList.student;
    //                 break;
    //             case 'professor': 
    //                 this.navItems = SidebarList.professor;
    //                 break;
    //             case 'agent': 
    //                 this.navItems = SidebarList.agent;
    //                 break; 
    //             case 'company': 
    //                 this.navItems = SidebarList.company;
    //                 break;       
    //         }
    //     }
    // },
    
    // created : function(){
    //     //유저의 계층을 판단
    //     this.user = location.hash.substring(2).split('/')[0];
    //     this.changeMenu(this.user)
    // },
}
</script>
