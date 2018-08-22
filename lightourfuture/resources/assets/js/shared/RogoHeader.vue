<template>
    <div class="header">
        <div>
            <img :src="logo" @click="redirectMain()">
        </div>
        <div class="lang-box">
            <span class="language-pack" v-for="(value , key) in languagePacks" :key="key"
                @click="changeLanguagePack(value)">
                {{key}}
            </span>
        </div>
    </div>
</template>

<style scoped lang="css" src="../static/css/common/RogoHeader.css"></style>
<script>
    export default {
        data() {
            return {
                logo : "/images/Index/logo_white.png",
                languagePacks : {
                    KOR : "kr",
                    JAP : "ja"
                },
            }
        },
        methods : {
            redirectMain () {
                this.$router.push("/");
            },

            changeLanguagePack (lang) {
                this.$i18n.locale(lang);
            }
        }
    }   
</script>
