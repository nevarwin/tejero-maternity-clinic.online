<template>

    <!-- <v-card elevation="0">
        <v-toolbar
            dense
            color="elevation-0"
            
            >
            <v-toolbar-title>Cad Plan Monitoring</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon small>
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </v-toolbar>
    </v-card> -->
    <v-list-item class="title-page">
        <template>
            <v-list-item-content>
                <v-list-item-title>
                    <span>{{toolbar.title}}</span>
                </v-list-item-title>
                <v-list-item-subtitle>
                    <template>
                        <span>{{toolbar.subTitle}}</span>
                            <!-- <template>
                                <v-badge color="red" content="3" style="position:absolute;" class="mt-3 ml-2">
                                </v-badge>
                            </template> -->
                    </template>
                </v-list-item-subtitle>
            </v-list-item-content>
            <template v-if="toolbar.edit">
                <v-btn icon>
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.add">
                <v-btn icon>
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.pass">
                <v-btn icon>
                    <v-icon color="success">mdi-thumb-up</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.fail">
                <v-btn icon>
                    <v-icon color="error">mdi-thumb-down</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.discontinue">
                <v-btn icon>
                    <v-icon>mdi-hand-back-left</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.csv">
                <v-btn icon>
                    <v-icon>mdi-microsoft-excel</v-icon>
                </v-btn>
            </template>
            <template v-if="toolbar.search">
                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </template>
            
        </template>
    </v-list-item>

</template>

<script>
    export default {
        name:'MainApp',
        props:{
            toolbar:{
                type: Object
            },

        },
        data: () => ({
            items:[
                {text:'Home', icon:'mdi-home', to:'home'},
                {text:'Hello', icon:'mdi-hand-wave', to:'hello'}
            ]
        }),
        methods:{
            toggleDrawer(val){
                this.$emit("toggleDrawer",val)
            },
        }
    }
</script>
<style scoped>
.title-page{
    flex: none !important;
    padding: 16px 0px !important;
}
.title-page.v-list-item{
    padding:0px !important;
}

.title-page .v-list-item__subtitle{
    font-size: 12px !important;
}

.title-page .v-list-item__content{
    padding: 8px 0px;
}
</style>