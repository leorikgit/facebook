const state = {
    user: null,
    userStatus: null,
    friendButton: null
}
const getters = {
    User: state => {
        return state.user;
    },
    friendButton: state => {
        return state.friendButton;
    },
    profileStatus: tate => {
        return state.userStatus;
    },
}
const actions = {
    fetchUser({commit, dispatch}, userId){
        axios.get('/api/users/' + userId)
            .then(res=>{
                commit('setUserStatus', 'Success');
                commit('setUser', res.data);
                dispatch('setFriendButtons');
            }).catch(error => {
            commit('setUserStatus', 'Error');
        }).finally(()=>{

        })
    },
    sendFriendRequest({commit, state}, friendId){
        commit('setButtonText', 'Loading...');
        axios.post('/api/friend-request/', {'friend_id': friendId})
            .then(res=>{
                commit('setButtonText', 'Pending');
            }).catch(error => {
                commit('setButtonText', 'Add Friend');
        });
    },
    setFriendButtons({commit, getters}) {
        
        if(getters.User.data.attributes.friendship === null){
            commit('setButtonText', 'Add Friend');
        }
        if (getters.User.data.attributes.friendship.data.attributes.confirmed_at === null) {
            commit('setButtonText', 'Pending');
        }

    }

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
    }
}
export default{
    state, getters, actions, mutations
}
