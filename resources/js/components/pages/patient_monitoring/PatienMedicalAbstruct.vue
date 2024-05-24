<template>
  <v-container class="container-main pt-0 pl-0" fluid>
    <v-card style="padding:10px;" flat class="abstruct_fontSize" id="printMedicalAbstruct">
      <v-row>
        <!-- {{case_data}} -->
        <v-col xs="12" md="6"> Name of Patient: {{case_data.patient_name}} </v-col>
        <v-col xs="12" md="6"> Date: 2024/01/08 </v-col>
      </v-row>
      <v-row>
        <v-col xs="12" md="6"> Date Admitted: {{case_data.admission_date}}</v-col>
        <v-col xs="12" md="6"> Date Discharged: 2024/01/08 </v-col>
      </v-row>
      <v-row>
        <v-col cols="12"> Admitting Diagnosis: test admitting diagnosis</v-col>
      </v-row>
      <br>
     
      <v-row>
        <v-col cols="3" xs="12" md="6">
          <b> Medical History</b>
          <br>
         <v-textarea></v-textarea>
        </v-col>
        <v-col xs="12" md="6">
          <v-row> <v-col>LMP:{{case_data.lmp}}</v-col></v-row>
          <v-row> <v-col>EDC:{{case_data.edc}}</v-col></v-row>
          <v-row> <v-col>AOG:{{case_data.aog}}</v-col></v-row>
        </v-col>
      </v-row>
     
      <v-row>
        <v-col><b>Pertinent Physical Examination</b></v-col>
      </v-row>
      <v-row>
        <v-col xs="12" sm="6" md="4">Temp: {{case_data.temperature}}</v-col>
        <v-col xs="12" sm="6" md="4">Cardiac Rate: {{case_data.pulse_rate}}</v-col>
        <v-col xs="12" sm="6" md="4">Respiratory Rate: {{case_data.respiratory_rate}}</v-col>
      </v-row>
      <v-row>
        <v-col xs="12" sm="6" md="4">Weight: {{case_data.weight}}</v-col>
        <v-col xs="12" sm="6" md="4">B.P: {{case_data.blood_presure}}</v-col>
        <v-col></v-col>
      </v-row>
     
      <v-row rows="3">
        <v-col><b>Course In The Wards</b>
          <br><br>
          Lorem ipsum dolor sit amet,
          consectetur adipiscing
          elit. Sed do eiusmod tempor
          incididunt ut labore et dolore magna
          aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco
        </v-col>
      </v-row>
     
      <v-row rows="3">
        <v-col><b>Medication</b>
          <br><br>
          Lorem ipsum dolor sit amet,
          consectetur adipiscing
          elit. Sed do eiusmod tempor
          incididunt ut labore et dolore magna
          aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco
        </v-col>
      </v-row>
     
      <v-row rows="3">
        <v-col><b>Final Diagnosis</b>
          <br><br> 
          Lorem ipsum dolor sit amet,
          consectetur adipiscing
        </v-col>
      </v-row>
     
      <v-row>
        <v-col>
          <b>Remarks</b>
          <br><br>
          
          Lorem ipsum dolor sit amet,
          consectetur adipiscing
        </v-col>
      </v-row>
     
    </v-card>
     <v-row>
       <v-btn class="mb-1 ml-5" color="success" @click="printMedicalAbstruct()"><v-icon>mdi-printer</v-icon> Print</v-btn>
      </v-row>
  </v-container>
</template>
<script>
import moment from "moment";
import { mapActions, mapState } from "vuex";

export default {
  props: {
    case_data: {
      type: Object,
    },
  },
  components: {
    // 'toolbar':ToolbarComponent,
    // "float-action": FloatAction,
    // 'agree-dialog':AgreeDialog,
    // "insert-dialog": VitalSignDialog,
    // "edit-dialog": VitalSignDialog,
    // snackbar: SnackBar,
  },
  data() {
    return {
      floatbtn: {
        add: true,
        edit: true,
        delete: true,
      },
      insertDialogSetting: {
        title: "Insert Vital Sign",
        submitBtn: "Save",
      },
      editDialogSetting: {
        title: "Update Vital Sign",
        submitBtn: "Update",
      },
      snackbar: {
        show: false,
        color: "success",
        text: null,
      },
      vitalSign: {
        time: moment().format("HH:mm:ss"),
        case_no: parseInt(this.case_data.case_no),
        blood_presure: "",
        temperature: "",
        pulse_rate: "",
        respiratory_rate: "",
        fetal_heart_tone: "",
        internal_examination: "",
      },
      insertDialog: false,
      editDialog: false,
      editMode: false,
      tableHeight: window.innerHeight - 180,
      tempEditVitalsign: {},
    };
  },
  methods: {
    ...mapActions(["getVitalSign"]),

    toggleInsertDialog() {
      this.insertDialog = true;
    },
    toggleEditMode(val) {
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
      this.editMode = !this.editMode;
    },
    Edit(data) {
      console.log(data);
      this.tempEditVitalsign = structuredClone(data);
      this.editDialog = true;
    },
    Insert() {
      axios({
        method: "post",
        url: "vital_sign_insert",
        data: this.vitalSign,
      })
        .then((res) => {
          console.log(res.data);
          this.snackbar.show = true;
          this.snackbar.text = "Success Insert";
          this.snackbar.color = "success";
          // this.$refs.Insert.resetValidation()
          this.getVitalSign();
          this.insertDialog = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    Update() {
      this.loadMore = true;
      axios({
        method: "post",
        url: "vital_sign_update",
        data: this.tempEditVitalsign,
      })
        .then((res) => {
          console.log(res.data);
          this.snackbar.show = true;
          this.snackbar.text = "Success Update";
          this.snackbar.color = "success";
          // this.$refs.Insert.resetValidation()
          this.getVitalSign();
          this.editDialog = false;
        })
        .catch((err) => {
          console.log(err);
        });
    },
     printMedicalAbstruct() {
         this.$htmlToPaper("printMedicalAbstruct");
    // const printWindow = window.open("", "_blank");
    // const printContent = document.getElementById("printMedicalAbstruct").innerHTML;

    // printWindow.document.write(`
    //   <html>
    //     <head>
    //       <title>Print</title>
    //     </head>
    //     <body>${printContent}</body>
    //   </html>
    // `);

    // printWindow.document.close();
    // printWindow.print();
  },
  },
  computed: {
    ...mapState(["case_VitalSign"]),
  },
  mounted() {
    this.getVitalSign();
  },
};
</script>

<style>
.abstruct_fontSize {
  font-size: 15px;
}

.container-main {
  margin-left: 10px; /* Add margin to the left */
}

.container-main v-row {
  margin-bottom: 10px; /* Add margin between rows */
}

.container-main hr {
  margin-top: 10px; /* Add margin above the hr element */
  margin-bottom: 20px; /* Add margin below the hr element */
}
</style>

