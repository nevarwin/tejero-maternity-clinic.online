<template>

      <v-container class="fill-height" fluid>
        <v-row justify="center">
          <v-col xs="12" sm="6" md="6" lg="5" xl="4">
            <v-img :src="imagePath" alt="My Image" contain fill-height></v-img>
          </v-col>
          <v-col
            xs="12"
            sm="6"
            md="6"
            lg="5"
            xl="4"
            class="d-flex align-center justify-center"
          >
            <v-card width="60%" elevation="5">
              {{ set_id }}
              <v-card-title>Change Password</v-card-title>
              <v-card-text class="mt-3">
                  <v-text-field
                                    v-model="changedPassword"
                                    id="changedPassword"
                                    label="Password"
                                    autocomplete="off"
                                    prepend-icon="mdi-lock" 
                                    :type="showPassword ? 'text' : 'password' "
                                    :append-icon="showPassword ? 'mdi-eye': 'mdi-eye-off'"
                                    @click:append="showPassword = !showPassword"
                                    @focus="$event.target.removeAttribute('readonly');"
                                    required
                                    readonly
                                    outlined
                                    dense
                                    name="password"
                                    :rules="rules.password"
                                ></v-text-field>
                 <v-text-field
                                    v-model="checkedPassword"
                                    id="checkedPassword"
                                    label="Confirmed Password"
                                    autocomplete="off"
                                    prepend-icon="mdi-lock" 
                                    :type="showPassword ? 'text' : 'password' "
                                    :append-icon="showPassword ? 'mdi-eye': 'mdi-eye-off'"
                                    @click:append="showPassword = !showPassword"
                                    @focus="$event.target.removeAttribute('readonly');"
                                    required
                                    readonly
                                    outlined
                                    dense
                                    name="password"
                                    :rules="rules.password"
                                ></v-text-field>
                <v-btn block color="success" @click="updatePassword()"
                  >Change Password</v-btn
                >
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      <snackbar
          :snackbar="snackbar"
        />
      </v-container>
</template>

<script>
import { mapActions, mapState } from "vuex";
import myImage from "../../../../../public/images/changePassword.jpg";
import verificationPinDialog from "./verificationPinDialog.vue"
import snackbar from "../../includes/SnackBar.vue"
import axios from "axios";
export default {
  props:{
    id:{
      type:Number
    }
  },
  name: "LoginPage",
  components:{
    "verificationPinDialog":verificationPinDialog,
    "snackbar":snackbar

  },
  data() {
    return {
      changedPassword:null,
      checkedPassword:null,
      imagePath: myImage,
      showPassword: false,
      disableBtn: false,
      loading: false,
      employee: {},
      tab: null,
      snackbar:{
                show:false,
                color:'success',
                text:'',
            },
    };
  },
  computed: {
    ...mapState(["rules", "systemData","user_id"]),
  },

  methods: {
    ...mapActions(["login"]),

    patientRequest() {
      // setTimeout(() => {
      // window.location.reload()
      console.log("toto");
      this.$router.push("/");
      // }, 1000);
    },

    async updatePassword() {
      console.log(this.user_id)
      // console.log(this.$store.user_id)
        if(this.changedPassword == this.checkedPassword){
            axios({
                method:"POST",
                url:"api/change_password_account",
                data:{id:this.user_id,password:this.changedPassword}
            })
           this.snackbar.show=true
                this.snackbar.text= "Change Success"
                this.snackbar.color="success"
                setTimeout(() => {
                    // this.$router.replace('/login')
                    window.location.href = "https://tejero-maternity-clinic.online/login2";
                }, 2000);
                // this.$router.replace('/login')

            
        }else{
           this.snackbar.show=true
                this.snackbar.text= "Both fields is not the same"
                this.snackbar.color="error"
        }
      // await axios({
      //   method: "POST",
      //   url: "api/verify_account",
      //   data: { email: this.emailAddress },
      // }).then(async (res) => {
      //   // CONDITION FOR SENDING PIN
      //   if (res.data) {
      //     await axios({
      //       method: "POST",
      //       url: "api/send_pin_number",
      //       data: { id: res.data.id, email: this.emailAddress },
      //     }).then(async (res) => {
      //       await axios
      //         .post("http://localhost/clinic_system/api/mail/store", {
      //           to: this.emailAddress,
      //           pin: res.data,
      //         })
      //         .then((response) => {
      //           console.log(response.data);
      //         })
      //         .catch((error) => {
      //           console.error("An error occurred:", error);
      //         });
      //     });
      //   } else {
      //     console.log("Email not register");
      //   }
      // });
    },

    resetForm() {
      if (this.$refs.LoginForm) {
        this.$refs.LoginForm.reset();
      }

      if (this.$refs.RegisterForm) {
        this.$refs.RegisterForm.reset();
      }
    },

    handleLogin() {
      this.disableBtn = true;
      if (this.$refs.LoginForm.validate()) {
        const myForm = document.getElementById("LoginForm");
        const formdata = new FormData(myForm);

        axios({
          method: "POST",
          url: `login`,
          data: formdata,
          config: {
            headers: { "Content-Type": "multipart/form-data" },
          },
        })
          .then((res) => {
            console.log(res.data);
            this.login(res.data);
            // this.$router.replace('/home')

            window.location.reload();
          })
          .catch((err) => {
            this.disableBtn = false;
            console.log(err);
            alert("Something Went Wrong");
          });
      }
    },

    handleRegistration() {
      this.disableBtn = true;
      if (this.$refs.RegisterForm.validate()) {
        const myForm = document.getElementById("RegisterForm");
        const formdata = new FormData(myForm);

        axios({
          method: "POST",
          url: "register",
          data: formdata,
          config: {
            headers: { "Content-Type": "multipart/form-data" },
          },
        })
          .then(() => {
            //  this.login()
            // this.$router.replace('/home')
            window.location.reload();
          })
          .catch((err) => {
            this.disableBtn = false;
            console.log(err);
            alert("Something Went Wrong");
          });
      }
    },
  },

  mounted() {},
  watch: {},
};
</script>

<style></style>
