<template>
     <div>
        <span class="language-pack" v-for="(value , key) in languagePacks" :key="key"
              @click="changeLanguagePack(value)">
                {{key}}
        </span>
    </div>
</template>

<style scpoed>

    .language-pack {
        color     : rgb(0, 128, 128);
        padding   : 0 1.5em 0 1.5em;
        font-size : 1.5em;
        border-right : 0.1vw rgb(0, 128, 128) solid;
    }
</style>


<script>
    export default {
        data() {
            return {
                languagePacks : {
                    KOR : "kr",
                    JAP : "ja"
                },
            }
        },
        methods : {
            changeLanguagePack ( selectedLang ){
                this.$i18n.locale = selectedLang;
            },
        },

    }

</script>
