<template>
    <v-container class="fill-height"  fluid>
        <v-row align="center" justify="center" >
            <v-card class="mx-6 mt-5" elevation="5" :loading="loading" width="450">
                <template slot="progress">
                    <v-progress-linear
                    color="black"
                    height="5"
                    indeterminate
                    ></v-progress-linear>
                </template>
                <v-card-title class="justify-center py-8">
                    <span class="display-3">
                        {{systemData ? systemData.name : null}}
                    </span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-btn to="login">Admin Login</v-btn>
                    <v-btn to="request_document">Patient Request</v-btn>
                    <v-btn @click="getAddress2()">Address</v-btn>
                </v-card-text>
            </v-card>
        </v-row>
    </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
export default {
    name : 'LoginPage',
    data() {
        return {
            showPassword: false, 
            disableBtn: false,
            loading: false,
            insertSystemDataDialog:false,
            tempName:null,
            addressData:[],
        };
    },
    computed: {
        ...mapState([
            'rules',
            'systemData',
            'province',
            'municipality',
            'barangay',
        ])
    },

    methods: {
        ...mapActions([
            'login'
        ]),
        
        resetForm(){
            if(this.$refs.LoginForm){
                this.$refs.LoginForm.reset();
            }

            if(this.$refs.RegisterForm){
                this.$refs.RegisterForm.reset();
            }
        },

        adminLogin() {
            axios({
                method: "get",
                url: `login`,
            })   
            .then((res)=>{
                console.log(res.data)
                // window.location.reload()
            })
            .catch(err =>{
                console.log(err)
                alert('Something Went Wrong')
            })
        },

        patientRequest(){
            axios({
                method: "get",
                url: `patient_request`,
            })   
            .then((res)=>{
                console.log(res.data)
                // window.location.reload()
            })
            .catch(err =>{
                console.log(err)
                alert('Something Went Wrong')
            })
        },

        handleRegistration(){
            this.disableBtn = true
            if(this.$refs.RegisterForm.validate()){
                const myForm = document.getElementById('RegisterForm');
                const formdata = new FormData(myForm);

                axios({
                    method : "POST",
                    url : 'register',
                    data: formdata,
                    config: {
                        headers: { "Content-Type": "multipart/form-data" },
                    },
                })
                .then(() => {
                    //  this.login()
                    // this.$router.replace('/home')
                    window.location.reload()
                })
                .catch(err =>{
                    this.disableBtn = false
                    console.log(err)
                    alert('Something Went Wrong')
                })
            }
        },
        getAddress2(){
            let province = []
            let municipality = []
            let complete =[]
            this.province.map(data=>{
                
                if(data.name == "Cavite"){
                    province.push(data.name)
                    complete.push({province:data.name})
                }
            })
            console.log(province,'province')
            province.map((data,index)=>{
                complete[index].municipality = []
                this.municipality.filter(data1=>{
                    if(data == data1.province){
                        complete[index].municipality.push({name:data1.name})
                    }
                })
            })
            complete.map((data,index)=>{
                data.municipality.map((mun,ind)=>{
                    mun.barangay =[]
                    this.barangay.filter(brgy=>{
                        let munName = mun.name
                        if(mun.name.includes('-')){
                            let arrMun = mun.name.split('-')
                            arrMun [1] = arrMun[1].charAt(0).toUpperCase() + arrMun[1].slice(1)
                            munName = arrMun.join(' ')
                        }
                        if(mun.name.includes("'")){
                            let arrMun = mun.name.split("'")
                            munName = arrMun.join('')
                        }
                        let upperMunName = munName.toUpperCase()
                        if(
                            upperMunName == brgy.citymun.toUpperCase() || 
                            'CITY OF ' + upperMunName == brgy.citymun.toUpperCase() || 
                            upperMunName + ' CITY' == brgy.citymun.toUpperCase() || 
                            brgy.citymun.includes(munName)
                        ){
                            mun.barangay.push({name:brgy.name})
                        }
                        if(brgy.citymun == 'Sampaloc' && upperMunName == 'MANILA' && brgy.code.toString().includes('133906')){
                            mun.barangay.push({name:brgy.name})
                        }
                    })
                })
            })

            complete.map(data=>{
                data.municipality.map(data1=>{
                    if(data1.barangay.length == 0){
                        console.log(data1.name,data)
                    }
                })
            })
            this.addressData = complete
            this.insertAddress()

            console.log('complete',complete)
        },
        insertAddress(){
            axios({
                method : 'post',
                url : `insert_address`,
                data:{address:this.addressData}
            })
            .then(res =>{
                console.log(res)
            }).catch(err =>{
                console.log(err)
            });
        }

    },
    watch: {
        systemData(val){
            if (val === null) {
                this.insertSystemDataDialog = true
                console.log('toktok')
            }
        }
    },

    mounted() {
        if (this.systemData === null) {
            this.insertSystemDataDialog = true
            console.log('toktok')
        }
    },
};
</script>
