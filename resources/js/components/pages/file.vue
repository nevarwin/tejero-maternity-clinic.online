<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
        <!-- <v-text-field
        v-model="search"
        prepend-inner-icon="mdi-file"
        block
        dense
        label="Search Files"
        outlined
      /> -->
        <v-file-input
            outlined
            dense
            label="Attachment"
            multiple
            v-model="attachment"
            @change="saveFile(attachment)"
        />
        <v-btn color="success" @click="uploadFiles()">Upload File</v-btn>
        <v-card class="mt-2">
            <v-simple-table>
                <thead>
                    <tr>
                        <th>files</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in listOfFiles" :key="index">
                        <td>{{ item.file }}</td>
                        <td>
                            <v-btn
                                small
                                class="ml-2"
                                @click="downloadFile(item)"
                            >
                                Download <v-icon>mdi-download</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </tbody>
            </v-simple-table>
        </v-card>
        <snackbar :snackbar="snackbar"></snackbar>
    </v-container>
</template>

<script>
import ToolbarComponent from "../includes/Toolbar.vue";
import axios from "axios";
import SnackBar from "../includes/SnackBar.vue";

export default {
    components: {
        toolbar: ToolbarComponent,
        snackbar: SnackBar,
    },
    data() {
        return {
            search: "",
            listOfAttachments: [],
            listOfFiles: [],
            attachment: null,
            toolbar: {
                title: "Documents",
                subTitle: "Download Files",
            },
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
            // console.log(item, "items");
            // return;
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
                console.log(this.listOfAttachments);
            }
        },
        uploadFiles() {
            let fileAttach = this.attachment
                ? this.attachment.map((rec) => {
                      return rec.name;
                  })
                : null;
            if (fileAttach) {
                if (fileAttach.length > 0) {
                    fileAttach = JSON.stringify(fileAttach);
                } else {
                    fileAttach = null;
                }
            } else {
                fileAttach = null;
            }
            axios({
                method: "post",
                url: "api/saveFile",
                data: {
                    file: this.listOfAttachments,
                },
            }).then(() => {
                axios({
                    method: "post",
                    url: "api/saveFileDatabase",
                    data: {
                        file: fileAttach,
                        path: "C:/Apache24/htdocs/clinic_system/storage/app/public/clinic_files",
                    },
                }).then(() => {
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Insert";
                    this.snackbar.color = "success";
                    location.reload();
                });
            });
        },
        downloadFile(val) {
            console.log(val);
            //   val.file = JSON.stringify([val.file]);
            window.open(`downloadFiles/${val.file}`, "_blank");
        },
    },
    mounted() {
        axios({
            method: "get",
            url: "api/getFiles",
        }).then((res) => {
            for (let index = 0; index < res.data.length; index++) {
                const element = res.data[index];
                let file = JSON.parse(element.file);
                element.file = file[0];
                this.listOfFiles.push(element);
            }
        });
    },
};
</script>

<style></style>
