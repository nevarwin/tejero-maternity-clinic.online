<template>
    <div class="text-center" >
        <v-snackbar
        class="pt-15"
        v-model="snackbar.show"
        :color="snackbar.color"
        :timeout="snackbarTimeout"
        top
        right
        min-width="100"
        >
        {{snackbar.text}}
        </v-snackbar>
    </div>
    
</template>
<script>
    import { mapState } from 'vuex'
    export default {
        props:{
            snackbar:{
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
            }
        },
    }
</script>