<template>
    <div v-if="authUser" class="flex flex-1 flex-col overflow-y-hidden h-screen">
        <Nav></Nav>
        <div class="flex overflow-y-hidden flex-1">
            <Sidebar></Sidebar>
           <div class="w-2/3 overflow-x-hidden">
               <router-view :key="$route.fullPath"></router-view>

           </div>
        </div>

    </div>
</template>

<script>
    import Nav from './Nav';
    import Sidebar from "./Sidebar";
    import {mapGetters} from "vuex";
    export default {
        name: "App",
        components:{
            Sidebar,
           Nav
        },
        mounted() {
            this.$store.dispatch('fetchAuthUser');
        },
        watch:{
            $route(to, from){
                this.$store.dispatch('setPageTitle', to.meta.title);
            }
        },
        created() {
            this.$store.dispatch('setPageTitle', this.$route.meta.title);
        },
        computed:{
            ...mapGetters({

                authUser: 'authUser'
            })
        }
    }
</script>

<style scoped>

</style>
