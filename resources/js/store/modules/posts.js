const state = {
    newsPosts: null,
    newsPostsStatus: null,
    postMessage: ''
}
const getters = {
    newsPosts: state => {
        return state.newsPosts;
    },
    newsStatus: state => {
        return {newsPostsStatus: state.newsPostsStatus}
    },
    postMessage: state =>{
        return state.postMessage;
    }

}
const actions= {
    fetchNewsPosts({commit, state}){
        commit('setNewsPostsStatus', 'loading');
        axios.get('/api/posts').
        then(res=>{

            commit('setNewsPosts', res.data);
            commit('setNewsPostsStatus', 'success');
        }).catch(err=>{
            commit('setNewsPostsStatus', 'error');
        })
    },
    postMessage({commit, state}){
        commit('setNewsPostsStatus', 'loading');
        axios.post('/api/posts', {body:state.postMessage}).
        then(res=>{

            commit('pushNewPost', res.data);

            commit('setPostMessage', '');
            commit('setNewsPostsStatus', 'success');
        }).catch(err=>{
            commit('setNewsPostsStatus', 'error');
        })
    },
    likePost({commit, state}, data){
        axios.post('/api/posts/'+data.postId+'/like').
        then(res=>{

            commit('pushLikes', {likes:res.data, postKey:data.postKey});
        }).catch(err=>{
        })
    }
}
const mutations = {
    setNewsPostsStatus(state, status){
        state.newsPostsStatus = status;
    },
    setNewsPosts(state, posts){
        state.newsPosts = posts;
    },
    setPostMessage(state, message){
        state.postMessage = message;
    },
    pushNewPost(state, newPost){
        state.newsPosts.data.unshift(newPost);
    },
    pushLikes(state, data){

        state.newsPosts.data[data.postKey].data.attributes.likes = data.likes
    }
}
export default{
    state, getters, actions, mutations
}
