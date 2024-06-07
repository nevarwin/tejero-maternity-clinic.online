<template>
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-form
            ref="form"
            @submit.prevent="toggleSave"
            enctype="multipart/form-data"
        >
            <v-card>
                <v-card-title>
                    <span>VIEW ROOM</span>
                    <v-spacer></v-spacer>
                    <v-icon color="white" @click="closeDialog"
                        >mdi-close</v-icon
                    >
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-label>Room Type</v-label>
                                <p>{{ room.room_type }}</p>
                            </v-col>
                            <v-col cols="12">
                                <v-label>Room Name</v-label>
                                <p>{{ room.name }}</p>
                            </v-col>
                            <v-col
                                cols="12"
                                v-for="(bed, index) in room.beds"
                                :key="index"
                            >
                                <v-label>{{
                                    "Bed Name " + (index + 1)
                                }}</v-label>
                                <p>{{ bed.name }}</p>
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
                        @click="closeDialog"
                        >Close</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>

<script>
import { mapState } from "vuex";

export default {
    props: {
        room: { type: Object },
        dialog: { type: Boolean },
    },
    data: () => ({
        snackbarTimeout: 3000,
        dialogBtn: false,
    }),
    methods: {
        closeDialog() {
            this.$emit("closeDialog");
        },
        toggleSave() {
            if (this.$refs.form.validate()) {
                this.$emit("toggleSave");
            }
        },
    },
    computed: {
        ...mapState(["rules", "selectRoomType"]),
    },
};
</script>
