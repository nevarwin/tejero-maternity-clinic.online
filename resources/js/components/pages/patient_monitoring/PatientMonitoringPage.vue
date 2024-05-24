<template>
    <v-container class="container-main pt-0 pl-0" fluid>
      <toolbar :toolbar="toolbar"></toolbar>
      <v-text-field v-model="search" label="search" outlined dense />
      
      <v-data-table
        :headers="headers"
        :items="caseData"
        :search="search"
        :footer-props="{ itemsPerPageOptions: [] }"
      >
    
  
        <template v-slot:item.action="{ item }">
          <v-btn @click="caseDialog(item)" outlined block><v-icon>mdi-eye</v-icon></v-btn>
        </template>
      </v-data-table>
  
      <PatientMonitoringDialog
        :dialog="case_monitor"
        @closeDialog="case_monitor = false" 
        :case_monitor_data="case_monitor_data"
      />
      <agree-dialog :agree="agree" @closeAgree="closeAgree()" @toggleAgree="toggleAgree()"></agree-dialog>
      <snackbar :snackbar="snackbar"></snackbar>
    </v-container>
  </template>
<script>
import ToolbarComponent from '../../includes/Toolbar'
import FloatAction from '../../includes/FloatAction.vue'
import AgreeDialog from '../../includes/AgreeDialog.vue'
import SnackBar from '../../includes/SnackBar.vue'
import PatientMonitoringDialog from "./PatientMonitoringDialog.vue";
import { mapActions, mapState } from 'vuex'
export default {
    name:'PatientRegistration',
    components:{
        'toolbar':ToolbarComponent,
        'agree-dialog':AgreeDialog,
        'snackbar':SnackBar,
        'PatientMonitoringDialog':PatientMonitoringDialog,
    },
    data() {
        return {
            toolbar:{
                title:'Patient Monitoring',
                subTitle:'Room and Patient monitor',
            },
            floatbtn:{
                add:true,
                edit:true,
                delete:true,
            },
            agree:{
                dialog:false,
                title:'Discharge',
                text:'Are you sure you want to Discharge the Patient?',
            },
            snackbar:{
                show:false,
                color:'success',
                text:null,
            },
            insertDialogSetting:{
                title:'Insert Room Info',
                submitBtn:'Save'
            },
            editDialogSetting:{
                title:'Update Patient Info',
                submitBtn:'Update'
            },
            search:"",
            tableHeight:window.innerHeight - 180,
            insertDialog:false,
            editDialog:false,
            deleteDialog:false,
            editMode:false,
            selectedRows:[],
            tempName:null,
            tempId:null,
            dialogBtn:false,
            case_monitor:false,
            case_monitor_data:{},
            tempData:{
                case_no:null,
                patient_id:null,
                doctor_id:null,
                room_id:null,
                bed_name:null,
            },
            tempEditData:{},


        }
    },
    methods:{
        ...mapActions(['getCase','set_case_no']),
        closeAgree(){
            this.agree.dialog = false
        },
        
        toggleAgree(){
            this.Delete()
        },
        toggleInsertDialog(){
            this.tempData = {},
            this.insertDialog = true
        },
        toggleEditMode(val){
            this.tempData = {
                room_type:null,
                name:null,
                beds:[]
            },
            this.editMode = !this.editMode
        },
        toggleDelete(id){
            this.tempId = id
            this.agree.dialog = true
        },
        Edit(data){
            console.log(data)
            this.tempEditData = structuredClone(data)
            this.editDialog = true
        },
        Insert(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'case_insert',
                data : this.tempData,
            })
            .then(res =>{
                console.log(res.data)
                if(res.data == 'success'){
                    this.snackbar.show=true
                    this.snackbar.text= "Success Insert"
                    this.snackbar.color="success"
                    // this.$refs.Insert.resetValidation()
                    this.getCase()
                    this.insertDialog = false
                }else if(res.data == 1){
                    this.snackbar.show=true
                    this.snackbar.text= "Case Number Already Exist"
                    this.snackbar.color="error"
                }
                

            }).catch(err =>{
                console.log(err)
            });
        },
        Update(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'case_update',
                data : this.tempEditData,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Update"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                this.getCase()
                this.editDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        Delete(){
            axios({
                method : 'post',
                url : 'case_delete',
                data : {
                    id:this.tempId
                },
            })
            .then(res =>{
                this.getCase()
                this.snackbar.show=true
                this.snackbar.text= "Success Discharge"
                this.snackbar.color="success"
                this.closeAgree()
            }).catch(err =>{
                console.log(err)
            });
        },
        caseDialog(data){
            this.case_monitor = true
            this.case_monitor_data = data
            this.set_case_no(data.case_no)
            
        }
    },
    computed:{
    //      caseDataSearch() {
    //   const newSearch = this.search.toLowerCase();
    //   return this.caseData.filter(item => {
    //     return (
    //       item.case_no.toLowerCase().includes(newSearch) ||
    //       item.patient_name.toLowerCase().includes(newSearch) ||
    //       item.doctor_name.toLowerCase().includes(newSearch) ||
    //       item.room_name.toLowerCase().includes(newSearch) ||
    //       item.bed_name.toLowerCase().includes(newSearch)||
    //       item.discharge_date.toLowerCase().includes(newSearch)
    //       // Add more fields to search if needed
    //     );
    //   });
    // },
    headers() {
      return [
        { text: 'Room', value: 'room_name' },
        { text: 'Bed', value: 'bed_name' },
        { text: 'Patient', value: 'patient_name' },
        { text: 'Case No', value: 'case_no' },
        { text: 'Doctor', value: 'doctor_name' },
        { text: 'Admission Date', value: 'admission_date' },
        { text: 'Discharge Date', value: 'discharge_date' },
        { text: 'Action', value: 'action', sortable: false },
      ];
    },
        ...mapState([
            'rules',
            'caseData',
            'selectRoomType',
            
        ]),
    },
    watch:{
    },
    
    mounted(){
        this.getCase()
    },
}
</script>

<style>

</style>