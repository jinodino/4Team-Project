<template>
    <div class="wrap-cont" >
        <div/>
        <div class="main-content">
            <span class="title-msg">404</span>
            <p class="sub-msg"> {{message}} </p>
            <button class="return-btn" @click="returnMain()"> 
                <span> Return to main page </span>
            </button>
        </div>
        <div/>
    </div>
</template>

<style scoped lang="css" src="../static/css/common/NotFound.css"></style>

<script>
export default {
    data(){
        return {
            message : "It looks like you're lost ",

        }
    },
    methods : {
        returnMain(){            
            this.$router.push("/");
        }
    }

}
</script>
