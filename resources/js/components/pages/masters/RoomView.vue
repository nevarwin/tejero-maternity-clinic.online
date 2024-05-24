<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-form  id="Insert" ref="Insert" @submit.prevent="toggleSave" enctype="multipart/form-data">
                <v-card>
                    <v-card-title>
                        <span>VIEW ROOM</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="closeDialog()">mdi-close</v-icon>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-autocomplete
                                        readonly
                                        disabled
                                        label="Room Type"
                                        v-model="room.room_type"
                                        :items="selectRoomType"
                                        item-value="id"
                                        item-text="name"
                                        class="required"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="room_type"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                    readonly
                                    disabled
                                        label="Room Name"
                                        v-model="room.name"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="name"
                                    ></v-text-field>
                                </v-col>
                                
                                <v-col cols="12">
                                    <v-row>
                                        <template v-for="(bed,index) in room.beds">
                                            
                                            <v-col cols="6" :key="index">
                                                <span>{{bed.vacant?"Occuppied":"Vacant"}}</span>
                                                <v-text-field
                                                    readonly
                                                    disabled
                                                    :label="'Bed Name ' + (index+1)"
                                                    v-model="bed.name"
                                                    class="required uppercase"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="name"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text :disabled="dialogBtn" @click="closeDialog()">Close</v-btn>
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
        room:{
            type: Object
        },
        dialog:{
            type:Boolean
        },
    },
    data: () => ({
        snackbarTimeout:3000,
        dialogBtn:false,
    }),
    methods: {
        refresh(){
            this.snackbar.show = false
            location.reload()
        },
        closeDialog(){
            this.$emit('closeDialog')
        },
        toggleSave(){
            if(this.$refs.Insert.validate()){
                this.$emit('toggleSave')
            }
        },
        addRoom(){
            this.room.beds.push({})
        },
        removeBed(index){
            this.room.beds.splice(index,1)
        }
    },
    computed:{
        ...mapState([
            'rules',
            'selectRoomType',
        ]),
        selectMunicipal(){
            let arr = []
            this.provinceMaster.filter(data=>{
                if(data.id === this.patient.province_id){
                    arr = data.municipality
                }
            })
            return arr
        },
        selectBarangay(){
            let arr = []
            this.provinceMaster.filter(data=>{
                if(data.id === this.patient.province_id){
                    data.municipality.filter(municipal=>{
                        if(municipal.id === this.patient.municipality_id){
                            arr = municipal.barangay
                        }
                    })
                }
            })
            return arr
        },
        tempAddressTxt(){
            let province = ''
            let municipality = ''
            let barangay = ''
            let completeAddress = [this.patient.house_address]
            this.provinceMaster.filter(data=>{
                if(data.id === this.patient.province_id){
                    province = data.name
                    completeAddress[3] = data.name
                    data.municipality.filter(municipal=>{
                        if(municipal.id === this.patient.municipality_id){
                            municipality = municipal.name
                            completeAddress[2] = municipal.name
                            municipal.barangay.filter(bar=>{
                                if(bar.id === this.patient.barangay_id){
                                    barangay = bar.name
                                    completeAddress[1] = bar.name
                                }
                            })
                        }
                    })
                }
            })
            if(!this.patient.house_address){
                completeAddress.shift()
            }
            console.log('totok',completeAddress)
            this.patient.address_txt = completeAddress.join(' ')
            return completeAddress.join(' ')
            // return this.tempHouse + ' ' + barangay + ' ' + municipality + ' ' + province
        },
    },
    // watch:{
    //     dialog(val){
    //         if(!val){
    //             this.$refs.Insert.resetValidation()
    //         }
    //     },
    // }

}
</script>

<style>

</style>