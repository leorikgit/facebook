<template>
    <div class="flex flex-col items-center mb-8">

         <div class="relative">
             <div class="position-absolute">
                 <div class="w-100 h-64 overflow-y-hidden z-10">
                     <img class="object-cover" src="https://image.shutterstock.com/z/stock-photo-colorful-hot-air-balloons-flying-over-mountain-at-dot-inthanon-in-chiang-mai-thailand-1033306540.jpg" alt="User bacground image">
                 </div>
                 <div class="flex items-center absolute bottom-0 left-0 -mb-8 ml-12 z-20">
                     <div class="w-32">
                         <img class="w-32 h-32 object-cover rounded-full border-4 border-gray-200 shadow-lg" src="https://toppng.com/uploads/preview/app-icon-set-login-icon-comments-avatar-icon-11553436380yill0nchdm.png" alt="user avatar">
                     </div>
                     <p class="ml-4 text-gray-100 text-2xl" v-if="profileStatus === 'Success'">{{user.data.attributes.name}}</p>
                 </div>
             </div>
             <div class="flex items-center absolute bottom-0 right-0 mb-4 mr-12 z-20">
                 <button class="py-1 px-3 bg-gray-400 rounded " v-if="friendButton" @click="$store.dispatch('sendFriendRequest', $route.params.userId)">{{friendButton}}</button>
             </div>
         </div>
        <p v-if="postLoading">Loading posts...</p>
        <post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"></post>

        <p class="mt-4 text-gray-700" v-if="! postLoading && posts.data.length < 1">No posts found. Get started...</p>

    </div>
</template>

<script>
    import Post from "../../components/Post";
    import {mapGetters} from 'vuex';
    export default {
        name: "Show",
        mounted() {
            this.$store.dispatch('fetchUser', this.$route.params.userId)
            axios.get('/api/users/' +  this.$route.params.userId + '/posts')
                .then(res=>{
                    this.posts = res.data;
                })
                .catch(error=>{
                    console.log('unable to load user');
                })
                .finally(()=>{
                    this.postLoading = false;
                });
        },
        data: () => {
            return {
                posts: [],
                postLoading: true
            }
        },
        components:{
            Post
        },
        computed:{
            ...mapGetters({
                user: 'User',
                friendButton: 'friendButton',
                profileStatus : 'profileStatus'
            })
        }

    }
</script>

<style scoped>

</style>
