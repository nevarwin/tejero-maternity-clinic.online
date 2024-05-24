<template>
    <v-container class="container-main pt-0 pl-0" fluid>
          <v-dialog v-model="dialog" persistent max-width="500px">
          <v-form  id="Insert" ref="Insert" @submit.prevent="toggleSave" enctype="multipart/form-data">
              <v-card>
                  <v-card-title><span>{{dialogSetting.title}}</span> 
                      <v-spacer></v-spacer>
                          <v-icon color="white" @click="closeDialog()">mdi-close</v-icon>
                  </v-card-title>
                  <v-card-text>
                      <v-container class="mt-5 ">
                                  <v-text-field v-model="medication_sheet.date" outlined dense label="Date"></v-text-field>
                                  <v-text-field v-model="medication_sheet.medication_dosage" outlined dense label="Medication Dosage"></v-text-field>
                                  <v-text-field type="number" v-model="medication_sheet.hours" outlined dense label="Hours"></v-text-field>
                                  <v-text-field v-model="medication_sheet.stat_medication" outlined dense label="Stat Medication"></v-text-field>
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
          medication_sheet:{
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