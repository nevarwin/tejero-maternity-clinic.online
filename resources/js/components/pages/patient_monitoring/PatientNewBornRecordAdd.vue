<template>
  <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="dialog" persistent max-width="800px">
        <v-form  id="Insert" ref="Insert" @submit.prevent="toggleSave" enctype="multipart/form-data">
            <v-card>
                <v-card-title><span>New Born Record Form</span> 
                    <v-spacer></v-spacer>
                        <v-icon color="white" @click="closeDialog()">mdi-close</v-icon>
                </v-card-title>
                <v-card-text>
                    <v-container class="mt-5 ">
                        <v-row>
                            <v-col>
                                <v-text-field v-model="newBornRecord.date_of_birth" outlined dense label="Date of Birth"></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field v-model="newBornRecord.time_of_birth" outlined dense label="Time of Birth"></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field type="number" v-model="newBornRecord.birth_weight" outlined dense label="Birth Weight(kg)"></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field type="number" v-model="newBornRecord.birth_lenght" outlined dense label="Birth Lenght(cm)"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-text-field type="number" v-model="newBornRecord.hc" outlined dense label="Head Circumference"></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field type="number" v-model="newBornRecord.ac" outlined dense label="Abdominal Circumference"></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field type="number" v-model="newBornRecord.cc" outlined dense label="Chest Circumference"></v-text-field>
                            </v-col>
                        </v-row>
                            
                            <v-text-field v-model="newBornRecord.general_aperance" outlined dense label="General Appearance(maturity,activity,tone,cry,color,edema)"></v-text-field>
                            <v-text-field v-model="newBornRecord.skin" outlined dense label="Skin(inertia,rashes,hematoma)"></v-text-field>
                            <v-text-field v-model="newBornRecord.head_neck" outlined dense label="Head, neck(molding,caput,craniolabels,cephalometoma)"></v-text-field>
                            <v-text-field v-model="newBornRecord.eyes" outlined dense label="Eye(abnormalities,conjuctivitis)"></v-text-field>
                            <v-text-field v-model="newBornRecord.ent" outlined dense label="Ear, Nose, Throat(lips,gums,palate)"></v-text-field>
                            <v-text-field v-model="newBornRecord.thorax" outlined dense label="Thorax (including breast atopy)"></v-text-field>
                            <v-text-field v-model="newBornRecord.lungs" outlined dense label="Lungs"></v-text-field>
                            <v-text-field v-model="newBornRecord.heart" outlined dense label="Heart"></v-text-field>
                            <v-text-field v-model="newBornRecord.abdomen" outlined dense label="Abdomen(including cord)"></v-text-field>
                            <v-text-field v-model="newBornRecord.genatalia" outlined dense label="Genitalia (testes, meatus)"></v-text-field>
                            <v-text-field v-model="newBornRecord.trunk_spine" outlined dense label="Trunk and Spine"></v-text-field>
                            <v-text-field v-model="newBornRecord.extremities" outlined dense label="Extremities (including clavicles and abduction of hip joints)"></v-text-field>
                            <v-text-field v-model="newBornRecord.anus" outlined dense label="Anus"></v-text-field>
                            <v-text-field v-model="newBornRecord.reflexes" outlined dense label="Reflexes (grasp, sucking, swallowing)"></v-text-field>
                            <v-textarea v-model="newBornRecord.impression" outlined dense label="Impressions">

                            </v-textarea>
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
// import { mapActions, mapState } from 'vuex'
export default {
    props:{
        newBornRecord:{
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