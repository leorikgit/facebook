<template>
    <div class="flex flex-col items-center p-4" >
        <NewPost></NewPost>
        <p v-if="newsStatus.newsPostsStatus === 'loading'">Loading posts...</p>
        <post v-else v-for="(post, postKey) in posts.data" :post="post" :key="postKey"></post>
    </div>
</template>

<script>
    import NewPost from "../../js/components/NewPost";
    import Post from "../../js/components/Post";
    import {mapGetters} from "vuex";
    export default {
        name: "NewsFeed",
        components:{
            Post,
            NewPost
        },
        data: ()=>{
            return {

            }
        },
        mounted() {
            this.$store.dispatch('fetchNewsPosts');
        },
        computed:{
            ...mapGetters({
               newsStatus: 'newsStatus',
                posts: 'newsPosts'
            })
        }

    }
</script>

<style scoped>

</style>
