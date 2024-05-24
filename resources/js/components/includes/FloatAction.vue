<template>
    <div class="button-bottom">
        <template>
            <v-tooltip top v-if="floatbtn.delete">
                <template v-slot:activator="{ on }">
                    <v-btn
                        v-show="selectedRowsCnt > 0"
                        @click="toggleDelete"
                        color="success"
                        v-on="on"
                        fab
                    >
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </template>
                <span>Delete</span>
            </v-tooltip>

            <v-tooltip top v-if="floatbtn.add">
                <template v-slot:activator="{ on }">
                    <v-btn
                        color="success"
                        v-show="!editMode"
                        @click="toggleInsertDialog(!insertDialog)"
                        v-on="on"
                        fab
                    >
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                </template>
                <div>Add</div>
            </v-tooltip>

            <v-tooltip top v-if="floatbtn.edit">
                <template v-slot:activator="{ on }">
                    <v-btn
                        color="success" v-on="on"
                        v-show="!editMode"
                        @click="toggleEditMode(!editMode)"
                        fab
                        >
                        <v-icon>mdi-pencil-outline</v-icon>
                    </v-btn>
                </template>
                <span>Edit</span>
            </v-tooltip>

            <v-tooltip top v-if="floatbtn.edit">
                <template v-slot:activator="{ on }">
                    <v-btn
                        color="success"
                        v-show="editMode"
                        @click="toggleEditMode(!editMode)"
                        v-on="on"
                        fab
                        >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
                <span>Close</span>
            </v-tooltip>
        </template>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    export default {
        name:'MainApp',
        props:{
            floatbtn:{
                type: Object
            },
            insertDialog:{
                type:Boolean
            },
            selectedRowsCnt:{
                type:Number
            },
            editMode:{
                type:Boolean
            }
        },  
        data: () => ({
            drawerVal: true,
        }),
        methods:{
            
            toggleInsertDialog(val){
                this.$emit("toggleInsertDialog",val)
            },
            toggleDelete(){
                this.$emit("toggleDelete")
            },
            toggleEditMode(val){
                // console.log(val)
                this.$emit("toggleEditMode",val)
            },
            ...mapActions([])
        },
        computed:{
            ...mapState([
            ])
        }
    }
</script>
<style scoped>
.button-bottom{
    position: absolute;
    bottom: 12px;
    right: 12px;
}
</style>