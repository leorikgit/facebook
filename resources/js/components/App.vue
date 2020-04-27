<template>
    <div class="flex flex-1 flex-col overflow-y-hidden h-screen">
        <Nav></Nav>
        <div class="flex overflow-y-hidden flex-1">
            <Sidebar></Sidebar>
           <div class="w-2/3 overflow-x-hidden">
               <router-view></router-view>

           </div>
        </div>

    </div>
</template>

<script>
    import Nav from './Nav';
    import Sidebar from "./Sidebar";
    export default {
        name: "App",
        components:{
            Sidebar,
           Nav
        },
        mounted() {
            this.$store.dispatch('fetchAuthUser');
        }
    }
</script>

<style scoped>

</style>
