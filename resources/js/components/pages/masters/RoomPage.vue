<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
         <v-row>
            <v-col>
        <v-text-field v-model="search" label="search" outlined dense />

            </v-col>
            <v-col>
        <v-btn color="success" dense @click="toggleInsertDialog($event)">Add</v-btn>

            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="roomData"
            :search="search"
            :items-per-page="10"
            class="elevation-1"
        >
        <template v-slot:item.room_type="{ item }">
    <td>
      {{ item.room_type === 1 ? 'Private' : 'Ward'}}
    </td>
  </template>
            <template v-slot:item.action="{ item }">
                <v-icon color="warning" @click="View(item)">mdi-eye</v-icon>
                <v-icon color="success" @click="Edit(item)">mdi-pencil</v-icon>
                <v-icon color="error" @click="toggleDelete(item.id)">mdi-delete</v-icon>
            </template>
        </v-data-table>
        <insert-dialog 
            :room="tempRoom" 
            :dialog="insertDialog" 
            :dialogSetting="insertDialogSetting" 
            @closeDialog="insertDialog = false" 
            @toggleSave="Insert()"
        ></insert-dialog>
        <edit-dialog 
            :room="tempEditRoom" 
            :dialog="editDialog" 
            :dialogSetting="editDialogSetting" 
            @closeDialog="editDialog = false" 
            @toggleSave="Update()"
        ></edit-dialog>
  <view-dialog 
            :room="tempEditRoom" 
            :dialog="viewDialog" 
            @closeDialog="viewDialog = false" 
            @toggleSave="Update()"
        ></view-dialog>
        <agree-dialog :agree="agree" @closeAgree="closeAgree()" @toggleAgree="toggleAgree()"></agree-dialog>
        <snackbar :snackbar="snackbar"></snackbar>
        <!-- NOTE : Float Action -->
        <float-action 
        @toggleDelete="toggleDelete"
        @toggleEditMode="toggleEditMode(editMode)"
        :floatbtn="floatbtn"
        :editMode="editMode"
        :selectedRowsCnt="selectedRows.length"
        ></float-action>
    </v-container>
</template>

<script>
import ToolbarComponent from '../../includes/Toolbar'
// import FloatAction from '../../includes/FloatAction.vue'
import AgreeDialog from '../../includes/AgreeDialog.vue'
import RoomDialog from './RoomDialog.vue'
import RoomView from './RoomView.vue'
import SnackBar from '../../includes/SnackBar.vue'
import auditRecord from '../../includes/auditFunction'

import { mapActions, mapState } from 'vuex'
export default {
    name:'PatientRegistration',
    components:{
        'toolbar':ToolbarComponent,
        // 'float-action':FloatAction,
        'agree-dialog':AgreeDialog,
        'insert-dialog':RoomDialog,
        'edit-dialog':RoomDialog,
        'view-dialog':RoomView,
        'snackbar':SnackBar,
    },
    data() {
        return {
            toolbar:{
                title:'Room Master',
                subTitle:'Register room',
            },
            floatbtn:{
                add:true,
                edit:true,
                delete:true,
            },
            agree:{
                dialog:false,
                title:'Delete',
                text:'Are you sure you want to delete',
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
            viewDialog:false,
            deleteDialog:false,
            editMode:false,
            selectedRows:[],
            tempName:null,
            tempId:null,
            dialogBtn:false,

            tempRoom:{
                room_type:null,
                name:null,
                beds:[]
            },
            tempEditRoom:{},
            headers: [
                { text: 'ID', align: 'start', sortable: true, value: 'id' },
                { text: 'Type', value: 'room_type' },
                { text: 'Name', value: 'name' },
                { text: 'Bed No.', value: 'beds.length' },
                { text: 'Action', value: 'action', sortable: false },
            ],

        }
    },
    methods:{
        vacantBed(val){
            return val
        },
        ...mapActions(['getRoom']),
        closeAgree(){
            this.agree.dialog = false
        },
        toggleAgree(){
            this.Delete()
        },
        toggleInsertDialog(){
            this.tempRoom = {
                room_type:null,
                name:null,
                beds:[]
            },
            this.insertDialog = true
        },
        toggleEditMode(val){
            this.tempRoom = {
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
            this.tempEditRoom = structuredClone(data)
            this.editDialog = true
        },
        View(data){
             this.tempEditRoom = structuredClone(data)
            this.viewDialog = true
        },
        Insert(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'room_insert',
                data : this.tempRoom,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Insert"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                this.getRoom()
                  let audit = {
                action : "New Room",
                description : "New Room Registered",
                name : this.loggedInUser.name,
                position : this.loggedInUser.access,
                drawerLink : "Room",
                date : moment().format("YYYY-MM-DD")
               }
               auditRecord.Insert(audit)
                this.insertDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        Update(){
            
            this.loadMore = true
            axios({
                method : 'post',
                url : 'room_update',
                data : this.tempEditRoom,
            })
            .then(res =>{
                console.log(res.data)
                this.snackbar.show=true
                this.snackbar.text= "Success Update"
                this.snackbar.color="success"
                // this.$refs.Insert.resetValidation()
                this.getRoom()
                 let audit = {
                action : "Update Room",
                description : "Room Info Updated",
                name : this.loggedInUser.name,
                position : this.loggedInUser.access,
                drawerLink : "Room",
                date : moment().format("YYYY-MM-DD")
               }
               auditRecord.Insert(audit)
                this.editDialog = false

            }).catch(err =>{
                console.log(err)
            });
        },
        Delete(){
            axios({
                method : 'post',
                url : 'room_delete',
                data : {
                    id:this.tempId
                },
            })
            .then(res =>{
                this.getRoom()
                this.snackbar.show=true
                this.snackbar.text= "Success Delete"
                this.snackbar.color="success"
                this.closeAgree()
                 let audit = {
                action : "Delete Room",
                description : "Room Info Deleted",
                name : this.loggedInUser.name,
                position : this.loggedInUser.access,
                drawerLink : "Room",
                date : moment().format("YYYY-MM-DD")
               }
               auditRecord.Insert(audit)
            }).catch(err =>{
                console.log(err)
            });
        },
    },
    computed:{
    //      roomDataSearch() {
    //   const newSearch = this.search.toLowerCase();
    //   return this.roomData.filter(item => {
    //     return (
    //       item.name.toLowerCase().includes(newSearch)
    //       // Add more fields to search if needed
    //     );
    //   });
    // },
        ...mapState([
            'rules',
            'roomData',
            'selectRoomType',
            'loggedInUser'
        ]),
    },
    watch:{
    },
    
    mounted(){
        this.getRoom()
    },
}
</script>

<style>

</style>