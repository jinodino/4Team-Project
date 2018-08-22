<template>
    <v-container fluid grid-list-xs grid-list-md grid-list-lg>
        <v-flex xs12 sm12 md12 lg12 d-flex :hover="true">
            <full-calendar :events="fcEvents" locale="en"></full-calendar>
        </v-flex>
    </v-container>
</template>

<script>
    export default{

        data (){
            return{
                fcEvents : [
   {
    title: 'Perfect Day for Rain',
    start: '2018-05-25',
    end: '2018-05-28'
  },
  {
    title: 'Wait another month for VueConf',
    start: '2018-05-21',
    end: '2018-05-22',
    cssClass: 'vueconf'
  },
    {
    title: 'Wait another month for VueConf',
    start: '2018-05-21',
    end: '2018-05-22',
    cssClass: 'vueconf'
  },
    {
    title: 'Wait another month for VueConf',
    start: '2018-05-21',
    end: '2018-05-22',
    cssClass: 'vueconf'
  },
    {
    title: 'Wait another month for VueConf',
    start: '2018-05-21',
    end: '2018-05-22',
    cssClass: 'vueconf'
  },
    {
    title: 'Wait another month for VueConf',
    start: '2018-05-21',
    end: '2018-05-22',
    cssClass: 'vueconf'
  },
  {
    title: 'A single sunny day',
    start: '2018-05-29',
    end: '2018-05-29'
  }
        ]
            }
        }
        

    }

</script>
