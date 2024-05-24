<template>
  <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="dialog" persistent max-width="500px">
        <v-form  id="Insert" ref="Insert" @submit.prevent="toggleSave" enctype="multipart/form-data">
            <v-card>
                <v-card-title><span>Vital Sign Form</span> 
                    <v-spacer></v-spacer>
                        <v-icon color="white" @click="closeDialog()">mdi-close</v-icon>
                </v-card-title>
                <v-card-text>
                    <v-container class="mt-5 ">
                                <v-text-field v-model="vitalSign.time" outlined dense label="Time"></v-text-field>
                                <v-text-field v-model="vitalSign.blood_presure" outlined dense label="Blood Presure"></v-text-field>
                                <v-text-field type="number" v-model="vitalSign.temperature" outlined dense label="Temparature"></v-text-field>
                                <v-text-field v-model="vitalSign.pulse_rate" outlined dense label="Pulse Rate"></v-text-field>
                                <v-text-field v-model="vitalSign.respiratory_rate" outlined dense label="Respiratory Rate"></v-text-field>
                                <v-text-field v-model="vitalSign.fetal_heart_tone" outlined dense label="Fetal Hearth Tone"></v-text-field>
                                <v-text-field v-model="vitalSign.internal_examination" outlined dense label="Internal Examination"></v-text-field>
                    </v-container>
                </v-card-text> 
                <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text :disabled="dialogBtn" @click="closeDialog()">Close</v-btn>
                        <v-btn color="blue darken-1" text :disabled="dialogBtn" @click="toggleSave()">{{dialogSetting.submitBtn}}</v-btn>
                    </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
    props:{
        vitalSign:{
            type: Object
        },
        dialog:{
            type:Boolean
        },
        dialogSetting:{
            type: Object
        }
    },
    data: () => ({
        snackbarTimeout:3000,
        dialogBtn:false,
    }),
    methods:{
        closeDialog(){
            this.$emit('closeDialog')
        },
        
        toggleSave(){
            if(this.$refs.Insert.validate()){
                this.$emit('toggleSave')
            }
        },
    },
    watch:{
        dialog(val){
            if(!val){
                this.$refs.Insert.resetValidation()
            }
        },
    }

}
</script>

<style>

</style>