import User from "./user";

const state = {
    user: null,
    userStatus: null,
    post: null,
    postStatus: null
}
const getters = {
    user: state => {
        return state.user;
    },
    post: state => {
        return state.post;
    },
    friendButton: (state, getters, rootState) => {
        if(getters.user.data.attributes.friendship === null){
            return 'Add Friend';
        }else if (getters.user.data.attributes.friendship.data.attributes.confirmed_at === null && getters.user.data.attributes.friendship.data.attributes.friend_id !== rootState.User.user.data.user_id){
            return 'Pending';
        }else if(getters.user.data.attributes.friendship.data.attributes.confirmed_at !== null){
            return '';
        }
        return "Accept";
    },
    status: state => {
        return {userStatus: state.userStatus , postStatus: state.postStatus}
    },
}
const actions = {
    fetchUser({commit, dispatch}, userId){
        axios.get('/api/users/' + userId)
            .then(res=>{
                commit('setUserStatus', 'success');
                commit('setUser', res.data);
            }).catch(error => {
            commit('setUserStatus', 'Error');
        }).finally(()=>{

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
    sendFriendRequest({commit, state}, friendId){
        axios.post('/api/friend-request/', {'friend_id': friendId})
            .then(res=>{
                commit('setUserFriendship', res.data);
            }).catch(error => {

        });
    },
    acceptFriendRequest({commit, state}, userId){
        axios.post('/api/friend-request-response/', {'user_id': userId, 'status': 1})
            .then(res=>{
                commit('setUserFriendship', res.data);
            }).catch(error => {

        });
    },
    ignoreFriendRequest({commit, state}, userId){
        axios.delete('/api/friend-request-response/delete', { data: {'user_id': userId}})
            .then(res=>{

                commit('setUserFriendship', null);
            }).catch(error => {

        });
    },


}
const mutations = {
    setUser(state, user){
        state.user = user;
    },
    setPosts(state, posts){
        state.post = posts;
    },
    setUserStatus(state, status){
        state.userStatus = status;
    },
    setPostsStatus(state, status){
        state.postStatus = status;
    },
    setUserFriendship(state, friendship){
        state.user.data.attributes.friendship = friendship;
    }
}
export default{
    state, getters, actions, mutations
}
