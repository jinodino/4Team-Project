<template>
    <div>
        <v-layout row justify-center>
            <v-dialog v-model="dialogActive" fullscreen>
                <v-card :height="'100%'">
                    <div class="dialog-late">
                        <div style="background:red;"></div>
                        <div style="background:blue;"></div>
                        <div style="background:green;"></div>
                        <div style="background:black"></div>
                    </div>
               </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<style>
.dialog-late {
    width: 100%;
    height: 100%;
    display : grid;
    grid-template-rows: 0.8fr 3.2fr 0.8fr 5.2fr;
    grid-row-gap : 0.2em;
}
</style>


<script>
    export default {
        props : [
            "student",
            "type"
        ],

        data () {
            return {
                dialogActive : false,
                selectedStd_skillInfo : null,
                selectedStd_personalInfo : null,
            }
        },

        methods : {

        },

        },
        mounted () {
            this.getSelectedStdDetails(this.student);
            this.dialogActive = true;
        },

        watch : {
            student (value) {
                this.getSelectedStdDetails(value);
            }
        }
    }
</script>
