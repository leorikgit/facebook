<template>
    <div class="flex flex-col items-center p-4">
        <NewPost></NewPost>
        <post></post>
    </div>
</template>

<script>
    import NewPost from "../js/components/NewPost";
    import Post from "../js/components/Post";
    export default {
        name: "NewsFeed",
        components:{
            Post,
            NewPost
        }

    }
</script>

<style scoped>

</style>
