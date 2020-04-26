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
                     <p class="ml-4 text-gray-100 text-2xl">{{user.data.attributes.name}}</p>
                 </div>
             </div>
         </div>
        <p v-if="postLoading">Loading posts...</p>
        <post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"></post>

    </div>
</template>

<script>
    import Post from "../../components/Post";
    export default {
        name: "Show",
        mounted() {
            axios.get('/api/users/' +  this.$route.params.userId)
            .then(res=>{
                this.user = res.data;
            })
            .catch(error=>{
                console.log('unable to load user');
            })
            .finally(()=>{
                this.userLoading = false;
            });

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
                user: null,
                userLoading: true,
                posts: [],
                postLoading: true
            }
        },
        components:{
            Post
        }

    }
</script>

<style scoped>

</style>
