<template>
  <v-container class="container-main pt-0 pl-0" fluid>
    <v-card>
        <v-simple-table dense :height="tableHeight">
        <thead>
            <tr>
              <th v-show="editMode" class="text-left" style="width:20px">Edit</th> 
              <th v-show="editMode" class="text-left" style="width:20px">ID</th>  
              <th>Time</th>
                <th>Blood Presure</th>
                <th>Temparature</th>
                <th>Pulse Rate</th>
                <th>Respirator Rate</th>
                <th>Fetal Hearth tone</th>
                <th>Internal Examination</th> 
            </tr>
        </thead>
<tbody>
  <tr v-for="(vital_sign,index) in case_VitalSign" :key="index">
    <td v-show="editMode">
                        <v-icon color="success" @click="Edit(vital_sign)">mdi-pencil</v-icon>
                        </td>
                        <td v-show="editMode">{{vital_sign.id}}</td>
    
    <td>{{ vital_sign.time }}</td>
    <td>{{ vital_sign.blood_presure }}</td>
    <td>{{ vital_sign.temperature }}</td>
    <td>{{ vital_sign.pulse_rate }}</td>
    <td>{{ vital_sign.respiratory_rate }}</td>
    <td>{{ vital_sign.fetal_heart_tone }}</td>
    <td>{{ vital_sign.internal_examination }}</td>

  </tr>
</tbody>
       </v-simple-table>
       <insert-dialog 
            :vitalSign="vitalSign"
            :dialog="insertDialog"  
            @closeDialog="insertDialog = false"
            :dialogSetting="insertDialogSetting" 
            @toggleSave="Insert()"
        ></insert-dialog>
        <edit-dialog 
            :vitalSign="tempEditVitalsign" 
            :dialog="editDialog" 
            :dialogSetting="editDialogSetting" 
            @closeDialog="editDialog = false" 
            @toggleSave="Update()"
        ></edit-dialog>
        <float-action 
        @toggleInsertDialog="toggleInsertDialog($event)"
        @toggleEditMode="toggleEditMode(editMode)"
        :editMode="editMode"
        :floatbtn="floatbtn"
  
        ></float-action>
        <snackbar :snackbar="snackbar"></snackbar>
    </v-card>
    
  </v-container>
</template>

<script>
import FloatAction from '../../includes/FloatAction.vue'
import VitalSignDialog from "./PatienVitalSignAdd.vue"
import SnackBar from '../../includes/SnackBar.vue'
import moment from 'moment'
import { mapActions, mapState } from 'vuex'

export default {
  props:{
    case_data:{
      type: Object
    },
  },
  components:{
        // 'toolbar':ToolbarComponent,
        'float-action':FloatAction,
        // 'agree-dialog':AgreeDialog,
        'insert-dialog':VitalSignDialog,
        'edit-dialog':VitalSignDialog,
        'snackbar':SnackBar,
    },
  data(){
    return{
      floatbtn:{
                add:true,
                edit:true,
                delete:true,
            },
        insertDialogSetting:{
            title:'Insert Vital Sign',
            submitBtn:'Save'
        },
        editDialogSetting:{
            title:'Update Vital Sign',
            submitBtn:'Update'
        },
        snackbar:{
                show:false,
                color:'success',
                text:null,
            },
        vitalSign:{
                time:moment().format("HH:mm:ss"),
                case_no:parseInt(this.case_data.case_no),
                blood_presure:"",
                temperature:"",
                pulse_rate:"",
                respiratory_rate:"",
                fetal_heart_tone:"",
                internal_examination:""
            },
      insertDialog:false,
      editDialog:false,
      editMode:false,
      tableHeight:window.innerHeight - 180,
      tempEditVitalsign:{}
   }
   
  },
  methods:{
    ...mapActions([]),

    toggleInsertDialog(){
            this.insertDialog = true
        }, 
        toggleEditMode(val){
        //   console.log(val,"129")
        //  this.vitalSign ={
        //         time:moment().format("HH:mm:ss"),
        //         case_no: parseInt(this.case_data.case_no),
        //         blood_presure:null,
        //         temperature:null,
        //         pulse_rate:null,
        //         respiratory_rate:null,
        //         fetal_heart_tone:null,
        //         internal_examination:null
        //     }
            this.editMode = !this.editMode
        },
        Edit(data){
            this.tempEditVitalsign = structuredClone(data)
            this.editDialog = true
        }, 
        Insert(){
          axios({
                method : 'post',
                url : 'vital_sign_insert',
                data : this.vitalSign,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Insert"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                // this.getVitalSign()
                this.$store.dispatch('getVitalSign')

                this.insertDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        Update(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'vital_sign_update',
                data : this.tempEditVitalsign,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Update"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                // this.getVitalSign()
                this.$store.dispatch('getVitalSign')
                this.editDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        
  },
  computed:{
    ...mapState([
            'case_VitalSign',
            'case_no'
        ]),
    
    },
    mounted(){
        console.log(this.case_VitalSign,"vital")
        console.log(this.case_no)
    },
}
</script>

<style>

</style>