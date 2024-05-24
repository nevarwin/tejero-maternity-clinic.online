<template>
    <v-app id="inspire">

        <v-main class="ma-2">
            <router-view></router-view>
        </v-main>
        <v-dialog v-model="insertSystemDataDialog" max-width="400" persistent>
            <v-card elevation="5" width="450">
                <template slot="progress">
                    <v-progress-linear
                    color="black"
                    height="5"
                    indeterminate
                    ></v-progress-linear>
                </template>
                <v-card-title class="justify-center py-8">
                    <span class="display-1">
                        Input System Data
                    </span>
                </v-card-title>
                <v-divider></v-divider>
                    <v-card-text>
                        <v-form ref="InsertDataInfo" id="InsertDataInfo">
                            <v-container>
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                            label="Name"
                                            v-model="tempName"
                                            class="required uppercase"
                                            dense
                                            :rules="rules.required"
                                            persistent-placeholder
                                            outlined
                                            name="name"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    
                    <v-card-actions class="pt-0">
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text :disabled="dialogBtn" @click="Insert()">Save</v-btn>
                    </v-card-actions>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script>
import AppBarComponent from '../components/includes/AppBar'
import DrawerComponent from '../components/includes/Drawer'
import {mapActions, mapState} from 'vuex';
    export default {
        components:{
                'app-bar-component':AppBarComponent,
                'drawer-component':DrawerComponent,
        },
        data: () => ({
            drawer: null,
            items:[
                {text:'Home', icon:'mdi-home', to:'home'},
                {text:'Hello', icon:'mdi-hand-wave', to:'hello'},
                {text: 'Test', icon: 'mdi-account', to : 'test'},
                {text: 'Registration', icon: 'mdi-account', to : 'registration'},
            ],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            
            insertSystemDataDialog:false,
            tempName:null,
            dialogBtn:false,

        }),
        methods:{
            ...mapActions(['getSystemData']),
            Insert(){
                
                if(this.$refs.InsertDataInfo.validate()){
                    this.loadMore = true
                    var myform = document.getElementById('InsertDataInfo');
                    var formdata = new FormData(myform);
                    axios({
                        method : 'post',
                        url : 'system_insert',
                        data : formdata,
                    })
                    .then(res =>{
                        console.log(res.data)
                        this.getSystemData()
                        // this.loadMore = false
                        // this.$refs.Insert.resetValidation()
                        // this.insertDialog = false
                        // this.getRegister()

                    }).catch(err =>{
                        console.log(err)
                    });
                }
            },
        },

        computed: {
            ...mapState([
                'loggedInUser',
                'systemData',
                'rules',
            ]),
            
        },
        watch:{
            // insertSystemDataDialog(val){
            //     if([null,"",undefined].includes(this.systemData)){
            //         console.log('meok');
            //         this.insertSystemDataDialog = true
            //     }
            // },
            systemData(val){
                if([null,"",undefined].includes(val)){
                    console.log('meok');
                    this.insertSystemDataDialog = true
                }else{
                    this.insertSystemDataDialog = false
                }
            },
        },
        mounted(){
            if ([null,"",undefined].includes(this.systemData)) {
                this.insertSystemDataDialog = true
                this.getSystemData()
                console.log('toktok1')
            }
        }
    }
</script>