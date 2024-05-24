<template>
  <v-container class="container-main pt-0 pl-0" fluid>
    <v-card>
        <!-- {{case_NewBorn}} -->
        <v-simple-table dense class="overflow-auto">
        <thead>
            <tr>
              <th v-show="editMode" class="text-left" style="width:20px">Edit</th> 
              <th v-show="editMode" class="text-left" style="width:20px">ID</th>  
                <th>Time</th>
                <th>Date of Birth</th>
                <th>Time of Birth</th>
                <th>Birth Weight</th>
                <th>Birth Lenght</th>
                <th>H.C</th>
                <th>A.C</th>
                <th>C.C</th>

            </tr>
        </thead>
<tbody>
  <tr v-for="(new_born,index) in case_NewBorn" :key="index">
    <td v-show="editMode">
                        <v-icon color="success" @click="Edit(new_born)">mdi-pencil</v-icon>
                        </td>
                        <td v-show="editMode">{{new_born.id}}</td>
    <td>{{ changeTimeformat(new_born.created_at) }}</td>
    <td>{{ new_born.time_of_birth }}</td>
    <td>{{ new_born.date_of_birth }}</td>
    <td>{{ new_born.birth_weight }}</td>
    <td>{{ new_born.birth_lenght }}</td>
    <td>{{ new_born.hc }}</td>
    <td>{{ new_born.ac }}</td>
    <td>{{ new_born.cc }}</td>

  </tr>
</tbody>
       </v-simple-table>
       <br>
       <v-simple-table dense class="overflow-auto">
 <thead>
            <tr>
         
              <th>Question</th>
                <th>Answer</th>

            </tr>
        </thead>
        <tbody>
            <tr v-for="(new_born,index) in get_new_born" :key="index">
                <td style="width: 100px;">{{ new_born.question }}</td>
                <td>{{ new_born.answer }}</td>
            </tr>
        </tbody>

       </v-simple-table>
       <br>
       <v-row class="ml-10">
  <v-col class="mb-2" xs="12" md="6">
    CODE: 0 - No Abnormality
  </v-col>
  <v-col class="mb-2" xs="12" md="6">
    X - Abnormality (describe abnormality)
  </v-col>
</v-row>
       <insert-dialog 
            :newBornRecord="newBornRecord"
            :dialog="insertDialog"  
            @closeDialog="insertDialog = false"
            :dialogSetting="insertDialogSetting" 
            @toggleSave="Insert()"
        ></insert-dialog>
        <edit-dialog 
            :newBornRecord="tempEditVitalsign" 
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
import NewBordRecordDialog from "./PatientNewBornRecordAdd.vue"
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
        'insert-dialog':NewBordRecordDialog,
        'edit-dialog':NewBordRecordDialog,
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
        newBornRecord:{
            patient_id: parseInt(this.case_data.patient_id),
            case_no: parseInt(this.case_data.case_no),
            date_of_birth:"",
            time_of_birth:"",
            birth_weight: "",
            birth_lenght: "",
            hc: "",
            ac: "",
            cc: "",
            general_aperance: "",
            skin: "",
            head_neck: "",
            eyes: "",
            ent: "",
            thorax: "",
            lungs: "",
            heart: "",
            abdomen: "",
            genatalia: "",
            trunk_spine: "",
            extremities: "",
            anus: "",
            reflexes: "",
            impression:""
            },
      insertDialog:false,
      editDialog:false,
      editMode:false,
      tableHeight:window.innerHeight - 180,
      tempEditVitalsign:{}
   }
   
  },
  methods:{
    // ...mapActions(['getNewBorn']),

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
                url : 'new_born_insert',
                data : this.newBornRecord,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Insert"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                // this.commit('getNewBorn')
                this.$store.dispatch('getNewBorn')
                this.insertDialog = false
                // this.get_new_born

            }).catch(err =>{
                console.log(err)
            });
        },
        Update(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'new_born_update',
                data : this.tempEditVitalsign,
            })
            .then(res =>{
                this.snackbar.show=true
                this.snackbar.text= "Success Update"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                // this.getNewBorn()
                this.$store.dispatch('getNewBorn')
                this.editDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        changeTimeformat(val){
            return moment(val).format("YYYY-MM-DD hh:mm:ss")
        }
        
  },
  computed:{
    ...mapState([
            'case_NewBorn',
        ]),
        get_new_born(){
            for (let i = 0; i < this.case_NewBorn.length; i++) {
                const element = this.case_NewBorn[i];
                let new_born_info = [
                    {question:"General Appearance(maturity,activity,tone,cry,color,edema)",
                    answer:element.general_aperance},
                    {question:"Skin(inertia,rashes,hematoma)",
                    answer:element.general_aperance},
                    {question:"Head, neck(molding,caput,craniolabels,cephalometoma)",
                    answer:element.head_neck},
                    {question:"Eye(abnormalities,conjuctivitis)",
                    answer:element.eyes},
                    {question:"Ear, Nose, Throat(lips,gums,palate)",
                    answer:element.ent},
                    {question:"Thorax (including breast atopy)",
                    answer:element.thorax},
                    {question:"Lungs",
                    answer:element.lungs},
                    {question:"Heart",
                    answer:element.heart},
                    {question:"Abdomen(including cord)",
                    answer:element.abdomen},
                    {question:"Genitalia (testes, meatus)",
                    answer:element.genatalia},
                    {question:"Trunk and Spine",
                    answer:element.trunk_spine},
                    {question:"Extremities (including clavicles and abduction of hip joints)",
                    answer:element.extremities},
                    {question:"Anus",
                    answer:element.anus},
                    {question:"Reflexes (grasp, sucking, swallowing)",
                    answer:element.reflexes},
                    {question:"Impression",
                    answer:element.impression},
                ]
                return new_born_info
            }
        }
    },
    // mounted(){
    //     this.getNewBorn()
    // },
}
</script>

<style>

</style>