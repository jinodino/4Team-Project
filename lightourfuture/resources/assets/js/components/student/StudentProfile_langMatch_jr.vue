<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          * 外国語能力 *
        </v-card-title>
        <v-container>
          <v-layout row wrap v-for="(match, key, index) in langMatchEdit" :key="index">
            <v-flex xs5>
              <v-select
              :items="langList"
              item-text="language"
              item-value="no"
              v-model="match.language_code"
              disabled
              >
              </v-select>
            </v-flex>
            <v-flex xs5>
              <v-select
              :items="langLevelList"
              item-text="language_level"
              item-value="no"
              v-model="match.language_level_code"
              disabled
              >
              </v-select>
            </v-flex>
            <v-flex xs1>
              <v-btn icon @click="deleteLang(key, match.language_code)" color="error" >
                <v-icon>remove</v-icon>
              </v-btn>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>  
    </v-flex>
  </v-layout>

</template>

<script>
export default {

  props : ['langMatchEdit', 'langList', 'langLevelList',],
  data : ()=>({

  }),
  methods : {
    deleteLang(index, lang_code){
      this.$emit('delete-lang', index, lang_code);
    }
  }

}
</script>

<style>

</style>
