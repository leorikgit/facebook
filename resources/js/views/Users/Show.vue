<template>
    <div class="flex flex-col items-center mb-8" v-if="status.userStatus === 'success' && user">

         <div class="relative">
             <div class="position-absolute">
                 <div class="w-100 h-64 overflow-y-hidden z-10">
                     <UploadableImage image-height="1500" image-width="300" location="cover"></UploadableImage>
                 </div>
                 <div class="flex items-center absolute bottom-0 left-0 -mb-8 ml-12 z-20">
                     <div class="w-32">
                         <img class="w-32 h-32 object-cover rounded-full border-4 border-gray-200 shadow-lg" src="https://toppng.com/uploads/preview/app-icon-set-login-icon-comments-avatar-icon-11553436380yill0nchdm.png" alt="user avatar">
                     </div>
                     <p class="ml-4 text-gray-100 text-2xl" v-if="status.user === 'success'">{{user.data.attributes.name}}</p>
                 </div>
             </div>
             <div class="flex items-center absolute bottom-0 right-0 mb-4 mr-12 z-20">
                 <button class="py-1 px-3 bg-gray-400 rounded " v-if="friendButton && friendButton !== 'Accept'" @click="$store.dispatch('sendFriendRequest', $route.params.userId)">{{friendButton}}</button>
                 <button class=" mr-1 py-1 px-3 bg-blue-500 rounded " v-if="friendButton && friendButton == 'Accept'" @click="$store.dispatch('acceptFriendRequest', $route.params.userId)">Accept</button>
                 <button class="py-1 px-3 bg-gray-400 rounded " v-if="friendButton && friendButton == 'Accept'" @click="$store.dispatch('ignoreFriendRequest', $route.params.userId)">Ignore</button>
             </div>
         </div>
        <div v-if="status.postStatus === 'loading'">Loading posts...</div>
        <div class="mt-4 text-gray-700" v-else-if="posts.length < 1">No posts found. Get started...</div>
        <post v-else v-for="(post, postKey) in posts.data" :post="post" :key="postKey"></post>



    </div>
</template>

<script>
    import Post from "../../components/Post";
    import UploadableImage from "../../components/UploadableImage";
    import {mapGetters} from 'vuex';
    export default {
        name: "Show",
        mounted() {
            this.$store.dispatch('fetchUser', this.$route.params.userId)
            this.$store.dispatch('fetchPost', this.$route.params.userId)

        },
        data: () => {
            return {

            }
        },
        components:{
            Post,
            UploadableImage
        },
        computed:{
            ...mapGetters({
                user: 'user',
                friendButton: 'friendButton',
                status : 'status',
                authUser: 'authUser',
                posts: 'Posts',
            })
        }

    }
</script>

<style scoped>

</style>
