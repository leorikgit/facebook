const state = {
    user: null,
    userStatus: null,
    friendButton: null
}
const getters = {
    User: state => {
        return state.user;
    },
    friendButton: (state, getters, rootState) => {
        if(getters.User.data.attributes.friendship === null){
            return 'Add Friend';
        }
        if (getters.User.data.attributes.friendship.data.attributes.confirmed_at === null) {
            return 'Pending';
        }
    },
    profileStatus: state => {
        return state.userStatus;
    },
}
const actions = {
    fetchUser({commit, dispatch}, userId){
        axios.get('/api/users/' + userId)
            .then(res=>{
                commit('setUserStatus', 'Success');
                commit('setUser', res.data);
            }).catch(error => {
            commit('setUserStatus', 'Error');
        }).finally(()=>{

        })
    },
    sendFriendRequest({commit, state}, friendId){
        commit('setButtonText', 'Loading...');
        axios.post('/api/friend-request/', {'friend_id': friendId})
            .then(res=>{

                commit('setUserFriendship', res.data);
            }).catch(error => {

        });
    },


}
const mutations = {
    setUser(state, user){
        state.user = user;
    },
    setUserStatus(state, status){
        state.userStatus = status;
    },
    setButtonText(state, text){
        state.friendButton = text;
    },
    setUserFriendship(state, friendship){
        state.user.data.attributes.friendship = friendship;
    }
}
export default{
    state, getters, actions, mutations
}
