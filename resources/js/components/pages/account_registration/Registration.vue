<template>
    <v-container class="container-main pt-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
        <v-row>
            <v-col>
                <v-text-field v-model="search" label="search" outlined dense />
            </v-col>
            <v-col>
                <v-btn color="success" dense @click="toggleInsertDialog($event)"
                    >Add</v-btn
                >
            </v-col>
        </v-row>
        <v-simple-table dense :height="tableHeight">
            <thead>
                <tr>
                    <!-- <th class="text-left" style="width:20px">ID</th>  -->
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Access</th>
                    <th class="text-left" style="width: 20px">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in registerDataSearch" :key="index">
                    <td v-show="editMode">{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.username }}</td>
                    <td>{{ item.email }}</td>

                    <td>
                        <template v-if="editMode">
                            <v-select
                                v-model="selectedStatus"
                                :items="['Active', 'Inactive']"
                                dense
                            ></v-select>
                        </template>
                        <template v-else>
                            {{
                                item.status == "Active" ? "Active" : "Inactive"
                            }}
                        </template>
                    </td>
                    <td>{{ item.access }}</td>
                    <td>
                        <v-icon color="success" @click="Edit(item)"
                            >mdi-pencil</v-icon
                        >
                        <v-icon color="error" @click="toggleDelete(item.id)"
                            >mdi-delete</v-icon
                        >
                    </td>
                </tr>
            </tbody>
        </v-simple-table>

        <v-overlay :value="loadMore" overlay>
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- NOTE: insert Dialog -->
        <v-dialog v-model="insertDialog" persistent max-width="400px">
            <v-form
                id="Insert"
                ref="Insert"
                @submit.prevent="Insert"
                enctype="multipart/form-data"
            >
                <v-card>
                    <v-card-title>
                        <span>Add</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="insertDialog = false"
                            >mdi-close</v-icon
                        >
                    </v-card-title>
                    <v-card-text>
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
                                <v-col cols="12">
                                    <v-text-field
                                        label="Username"
                                        v-model="tempUsername"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="username"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Password"
                                        v-model="tempPassword"
                                        class="required"
                                        dense
                                        type="password"
                                        :rules="rules.password"
                                        persistent-placeholder
                                        outlined
                                        name="password"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Email"
                                        v-model="tempEmail"
                                        class="required"
                                        dense
                                        type="email"
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="email"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-select
                                        outlined
                                        label="Status"
                                        v-model="status_staff"
                                        :rules="rules.required"
                                        :items="['Active', 'Inactive']"
                                        name="status"
                                        dense
                                    ></v-select>
                                </v-col>
                                <v-col>
                                    <v-select
                                        outlined
                                        label="Position"
                                        :rules="rules.required"
                                        v-model="access_position"
                                        :items="[
                                            'Admin',
                                            'Staff',
                                            'Super Admin',
                                            'Doctor',
                                        ]"
                                        name="access"
                                        dense
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="insertDialog = false"
                            >Close</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="Insert()"
                            >Save</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>

        <!-- NOTE: Edit Dialog -->
        <v-dialog v-model="editDialog" persistent max-width="400px">
            <v-form
                id="Update"
                ref="Update"
                @submit.prevent="Update"
                enctype="multipart/form-data"
            >
                <v-card>
                    <v-card-title>
                        <span>Edit</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="editDialog = false"
                            >mdi-close</v-icon
                        >
                    </v-card-title>
                    <v-card-text>
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
                                <v-col cols="12">
                                    <v-text-field
                                        label="Username"
                                        v-model="tempUsername"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="username"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="New Password"
                                        v-model="tempPassword"
                                        class="required"
                                        dense
                                        type="password"
                                        :rules="rules.password"
                                        persistent-placeholder
                                        outlined
                                        name="password"
                                    ></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-select
                                        outlined
                                        label="Status"
                                        v-model="status_staff"
                                        :items="['Active', 'Inactive']"
                                        name="status"
                                        dense
                                    ></v-select>
                                </v-col>
                                <v-col>
                                    <v-select
                                        outlined
                                        label="Position"
                                        v-model="access_position"
                                        :items="[
                                            'Admin',
                                            'Staff',
                                            'Super Admin',
                                            'Doctor',
                                        ]"
                                        name="access"
                                        dense
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <input type="hidden" :value="tempId" name="id" />
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="editDialog = false"
                            >Close</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="Update()"
                            >Save</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <v-dialog v-model="deleteDialog" persistent max-width="400px">
            <v-card>
                <v-card-title>
                    <span>Delete</span>
                    <v-spacer></v-spacer>
                    <v-icon color="white" @click="deleteDialog = false"
                        >mdi-close</v-icon
                    >
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <span>Are you sure you want to Delete</span>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        :disabled="dialogBtn"
                        @click="deleteDialog = false"
                        >Close</v-btn
                    >
                    <v-btn
                        color="blue darken-1"
                        text
                        :disabled="dialogBtn"
                        @click="Delete()"
                        >Save</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

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
import ToolbarComponent from "../../includes/Toolbar";
// import FloatAction from '../../includes/FloatAction.vue'
import { mapActions, mapState } from "vuex";
export default {
    name: "Employee",
    components: {
        toolbar: ToolbarComponent,
        // 'float-action':FloatAction,
    },
    data() {
        return {
            employee: [],
            empIds: null,
            toolbar: {
                title: "Register",
                subTitle: "Register Management System",
            },
            floatbtn: {
                add: true,
                edit: true,
                delete: true,
            },
            search: "",
            selectedStatus: "",
            selectAll: false,
            selectedRows: [],
            editMode: false,
            loadMore: false,
            insertDialog: false,
            dialogBtn: false,
            addDialog: false,
            editDialog: false,
            deleteDialog: false,
            verifyEditDialog: false,
            tableHeight: window.innerHeight - 180,
            pathStorage: window.base_dir,
            // NOTE: Custom Variable
            registerData: [],
            unitTypes: [
                {
                    id: 1,
                    name: "Register",
                },
                {
                    id: 2,
                    name: "Computer",
                },
            ],
            status_staff: null,
            access_position: null,
            tempUnitNo: null,
            tempUnitType: null,
            tempSeq: null,
            tempId: null,
            tempToDeleteId: null,
            tempName: null,
            tempUsername: null,
            tempPassword: null,
            tempIp: null,
            tempEmail: null,
            tempStatus: null,
        };
    },
    methods: {
        toggleInsertDialog() {
            this.insertDialog = true;
        },
        toggleDelete(id) {
            console.log("delete");
            this.tempToDeleteId = id;
            this.deleteDialog = true;
            // sdfasdf
        },
        Insert() {
            if (this.$refs.Insert.validate()) {
                this.loadMore = true;
                var myform = document.getElementById("Insert");
                var formdata = new FormData(myform);
                axios({
                    method: "post",
                    url: "register_insert",
                    data: formdata,
                })
                    .then((res) => {
                        console.log(res.data);
                        this.loadMore = false;
                        // this.$refs.Insert.resetValidation()
                        this.insertDialog = false;
                        this.getRegister();
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        toggleEditMode(val) {
            this.editMode = !this.editMode;
        },
        Edit(item) {
            console.log(item);
            this.tempName = item.name;
            this.tempUsername = item.username;
            this.tempIp = item.ip;
            this.tempPassword = "";
            this.tempId = item.id;
            this.access_position = item.access;
            this.status_staff = item.status == "1" ? "Active" : "Inactive";
            this.editDialog = true;
        },
        Update() {
            if (this.$refs.Update.validate()) {
                this.loadMore = true;
                var myform = document.getElementById("Update");
                var formdata = new FormData(myform);

                axios({
                    method: "post",
                    url: "register_update",
                    data: formdata,
                })
                    .then((res) => {
                        console.log(res.data);
                        this.loadMore = false;
                        // this.$refs.Insert.resetValidation()
                        this.editDialog = false;
                        this.getRegister();
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        Delete() {
            console.log(this.tempToDeleteId);
            this.loadMore = true;

            axios({
                method: "post",
                url: "register_delete",
                data: { id: this.tempToDeleteId },
            })
                .then((res) => {
                    console.log(res.data);
                    this.loadMore = false;
                    this.deleteDialog = false;
                    this.getRegister();
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getRegister() {
            this.loadMore = true;
            this.registerData = [];
            axios({
                method: "post",
                url: "api/get_user",
                data: { id: 1 },
            })
                .then((res) => {
                    console.log(res.data);
                    this.loadMore = false;
                    this.registerData = res.data;
                })
                .catch((err) => {
                    this.dialogBtn = false;
                    console.log(err);
                });
        },
        updateStatus(newStatus) {
            // Map 'Active' to '1' and 'Inactive' to '0'
            const statusValue = newStatus === "Active" ? "1" : "0";

            // Make an API call or update the 'registerData' array directly
            // this.updateStatusOnServer(item.id, statusValue);
        },
        // updateStatusOnServer(id, newStatus) {
        //     // Make an API call to update the status in the database
        //     axios.post('update_status', { id, status: newStatus })
        //         .then(response => {
        //             console.log(response.data);
        //             // Update the 'registerData' array or reload the data from the server
        //             this.getRegister();
        //         })
        //         .catch(error => {
        //             console.error(error);
        //         });
        // },
    },
    computed: {
        registerDataSearch() {
            const newSearch = this.search.toLowerCase();
            return this.registerData.filter((item) => {
                return (
                    item.name.toLowerCase().includes(newSearch) ||
                    item.username.toLowerCase().includes(newSearch) ||
                    item.email.toLowerCase().includes(newSearch)
                    // Add more fields to search if needed
                );
            });
        },
        ...mapState(["rules"]),
        seqs() {
            return this.insertDialog
                ? _.reverse(_.range(1, this.registerData.length + 2))
                : _.reverse(_.range(1, this.registerData.length + 1));
        },
        last_seq() {
            return this.registerData.length + 1;
        },
    },
    watch: {
        insertDialog(val) {
            if (!val) {
                this.tempUnitNo = null;
                this.tempUnitType = null;
                this.$refs.Insert.resetValidation();
                // this.closeInsertDialog()
            }
        },
    },
    created() {
        // console.log(window.device_ip,window.base_dir,window.location.pathname)
        // if(window.device_ip != '10.169.141.105' && window.location.pathname == '/personal/registration'){
        //     console.log('meok')
        //     this.$router.push('./home')
        // }
        this.getRegister();
    },
};
</script>
<style scoped>
th,
td {
    /* border: black 1px solid; */
    /* padding:0px !important; */
    text-align: center;
}
.mt-4 {
    margin-top: 16px;
}
.inline {
    display: inline-flex;
}
.uppercase >>> input {
    text-transform: uppercase;
}
/* .v-card__title {
    background: #455A64 !important;
    color: #ffffff !important;
    padding: 4px 16px !important;
} */
/* .boxing{
    display: block;
    text-align: -webkit-center;
    42069 42070 42071 42072 42073 42074
} */
</style>
