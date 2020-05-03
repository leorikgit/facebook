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
            // commit('setNewsPostsStatus', 'success');
        }).catch(err=>{
            commit('setNewsPostsStatus', 'error');
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
    }
}
export default{
    state, getters, actions, mutations
}
