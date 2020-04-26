<template>
    <div class="flex flex-col items-center p-4">
        <NewPost></NewPost>
        <p v-if="loading">Loading posts...</p>
        <post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"></post>
    </div>
</template>

<script>
    import NewPost from "../../js/components/NewPost";
    import Post from "../../js/components/Post";
    export default {
        name: "NewsFeed",
        components:{
            Post,
            NewPost
        },
        data: ()=>{
            return {
            posts: [],
            loading: true
            }
        },
        mounted() {
            axios.get('/api/posts').
                then(res=>{
                    this.posts = res.data;
                    this.loading = false;
            }).catch(err=>{
                console.log('unable to laod posts');
                this.loading = false;
            })
        }

    }
</script>

<style scoped>

</style>
