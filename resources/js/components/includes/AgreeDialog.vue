<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="agree.dialog" persistent max-width="400">
            <v-card>
                <v-card-title>
                    <span>{{agree.title}}</span>
                    <v-spacer></v-spacer>
                    <v-icon color="white" @click="closeAgree()">mdi-close</v-icon>
                </v-card-title>
                <v-card-text>
                    <v-container fluid class="mt-2">
                        {{agree.text}}
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeAgree()">DISAGREE</v-btn>
                    <v-btn color="blue darken-1" text @click="toggleAgree()">AGREE</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
    props:{
        agree:{
            type: Object
        }
    },
    data: () => ({
        snackbarTimeout:3000
    }),
    methods: {
        refresh(){
            this.snackbar.show = false
            location.reload()
        },
        closeAgree(){
            this.$emit('closeAgree')
        },
        toggleAgree(){
            this.$emit('toggleAgree')
        }
    },
}
</script>

<style>

</style>