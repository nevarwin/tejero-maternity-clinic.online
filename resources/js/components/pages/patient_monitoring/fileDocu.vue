<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-card flat>
            <v-card-text>
                <v-row>
                    <v-col>
                        <v-text-field
                            v-model="search"
                            prepend-inner-icon="mdi-file"
                            block
                            dense
                            label="Search Files"
                            outlined
                        />
                    </v-col>
                </v-row>

                <v-btn @click="attach_dialog = true" color="success"
                    >ADD FILE</v-btn
                >
                <v-card class="mt-2">
                    <v-simple-table>
                        <thead>
                            <tr>
                                <th>Files</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="filteredFiles.length">
                            <tr
                                v-for="(item, index) in filteredFiles"
                                :key="index"
                            >
                                <td>{{ item.file }}</td>
                                <td>
                                    <v-btn
                                        small
                                        class="ml-2"
                                        @click="downloadFile(item.file)"
                                    >
                                        Download <v-icon>mdi-download</v-icon>
                                    </v-btn>
                                    <v-btn
                                        small
                                        color="error"
                                        class="ml-2"
                                        @click="deleteFile(item)"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <td
                                style="color: red; text-align: center"
                                colspan="2"
                            >
                                NO RECORD FOUND
                            </td>
                        </tbody>
                    </v-simple-table>
                </v-card>
                <snackbar :snackbar="snackbar"></snackbar>
                <v-dialog v-model="attach_dialog" width="300">
                    <v-card>
                        <v-card-title>Add File</v-card-title>
                        <v-card-text class="mt-2">
                            <v-file-input
                                outlined
                                dense
                                label="Attachment"
                                multiple
                                v-model="attachment"
                                @change="saveFile(attachment)"
                            />
                            <v-card-actions>
                                <v-btn color="success" @click="uploadFiles()"
                                    >Upload File</v-btn
                                >
                            </v-card-actions>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import axios from "axios";
import SnackBar from "../../includes/SnackBar.vue";
import { mapActions, mapState } from "vuex";

export default {
    props: {
        case_data: {
            type: Object,
        },
    },
    components: {
        snackbar: SnackBar,
    },
    data() {
        return {
            search: "",
            attach_dialog: false,
            listOfAttachments: [],
            listOfFiles: [],
            attachment: null,
            snackbar: {
                show: false,
                color: "success",
                text: null,
            },
            headers: [
                {
                    text: "File Name",
                    value: "file",
                },
                {
                    text: "Action",
                    value: "action",
                },
            ],
        };
    },
    methods: {
        async saveFile(value) {
            let extension = value[0].name.split(".");
            if (
                extension[1] == "jpg" ||
                extension[1] == "pdf" ||
                extension[1] == "jpeg"
            ) {
                if (value) {
                    value.forEach(async (item) => {
                        const reader = new FileReader();
                        reader.readAsDataURL(item);
                        reader.onloadend = () => {
                            this.listOfAttachments.push({
                                data: reader.result,
                                name: item.name,
                            });
                        };
                    });
                }
            } else {
                alert("JPG or PDF only");
                this.attachment = [];
            }
        },
        uploadFiles() {
            if (this.listOfAttachments.length === 0) {
                alert("Please attach a file");
                return false;
            }

            let fileAttach = this.attachment ? this.attachment[0].name : null;

            axios
                .post("api/saveFile", {
                    file: this.listOfAttachments,
                })
                .then(() => {
                    axios
                        .post("api/saveFileDatabase", {
                            case_no: this.case_data.case_no,
                            file: fileAttach,
                            path: "./storage/public/storage/clinic_files",
                        })
                        .then(() => {
                            this.snackbar.show = true;
                            this.snackbar.text = "Success Insert";
                            this.snackbar.color = "success";
                            this.$store.dispatch("getFiles");
                            this.attach_dialog = false;
                            this.attachment = null;
                            this.listOfAttachments = [];
                        });
                });
        },
        downloadFile(val) {
            // console.log(val)
            window.open(`downloadFiles/${val}`, "_blank");
        },
        deleteFile(val) {
            console.log(val);

            let confirmed = confirm("Are you sure you to delete?");

            if (confirmed) {
                axios({
                    method: "POST",
                    url: "deleteFileDatabase",
                    data: { id: val.id },
                }).then(() => {
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Delete";
                    this.snackbar.color = "success";
                    this.$store.dispatch("getFiles");
                    this.attach_dialog = false;
                    this.attachment = null;
                    this.listOfAttachments = [];
                });
            }
        },
    },
    computed: {
        ...mapState(["case_Files", "case_no"]),
        filteredFiles() {
            if (!this.search.trim()) return this.case_Files;
            return this.case_Files.filter((item) =>
                item.file.toLowerCase().includes(this.search.toLowerCase())
            );
        },
    },
    mounted() {
        // this.listOfFiles = this.case_Files;
        for (let i = 0; i < this.case_Files.length; i++) {
            const element = this.case_Files[i];
            // console.log(element)
            element.file = JSON.parse(element.file);
            this.listOfFiles = element;
        }
    },
};
</script>

<style></style>
