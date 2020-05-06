const state = {
    Posts: null,
    PostsStatus: null,
    postMessage: ''
}
const getters = {
    Posts: state => {
        return state.Posts;
    },
    newsStatus: state => {
        return {PostsStatus: state.PostsStatus}
    },
    postMessage: state =>{
        return state.postMessage;
    }

}
const actions= {
    fetchNewsPosts({commit, state}){
        commit('setPostsStatus', 'loading');
        axios.get('/api/posts').
        then(res=>{

            commit('setPosts', res.data);
            commit('setPostsStatus', 'success');
        }).catch(err=>{
            commit('setPostsStatus', 'error');
        })
    },
    fetchPost({commit, dispatch}, userId){
        commit('setPostsStatus', 'loading');
        axios.get('/api/users/' +  userId + '/posts')
            .then(res=>{
                commit('setPostsStatus', 'success');
                commit('setPosts', res.data);
            }).catch(error => {
            commit('setPostsStatus', 'Error');
        })
    },
    postMessage({commit, state}){
        commit('setPostsStatus', 'loading');
        axios.post('/api/posts', {body:state.postMessage}).
        then(res=>{

            commit('pushNewPost', res.data);

            commit('setPostMessage', '');
            commit('setPostsStatus', 'success');
        }).catch(err=>{
            commit('setPostsStatus', 'error');
        })
    },
    likePost({commit, state}, data){
        axios.post('/api/posts/'+data.postId+'/like').
        then(res=>{

            commit('pushLikes', {likes:res.data, postKey:data.postKey});
        }).catch(err=>{
        })
    },
    postComment({commit, state}, data){
        axios.post('/api/posts/' + data.postId + '/comment', {body:data.body})
            .then(res=>{
            commit('pushComments', {comments:res.data, postKey:data.arrayId});
        }).catch(error => {

        })
    }
}
const mutations = {
    setPostsStatus(state, status){
        state.PostsStatus = status;
    },
    setPosts(state, posts){
        state.Posts = posts;
    },
    setPostMessage(state, message){
        state.postMessage = message;
    },
    pushNewPost(state, newPost){
        state.Posts.data.unshift(newPost);
    },
    pushLikes(state, data){

        state.Posts.data[data.postKey].data.attributes.likes = data.likes
    },
    pushComments(state, data){
        state.Posts.data[data.postKey].data.attributes.comments = data.comments
    }
}
export default{
    state, getters, actions, mutations
}
