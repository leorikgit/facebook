const state = {
    user: null,
    userStatus: null,
}
const getters = {
    User: state => {
        return state.user;
    }
}
const actions= {
    fetchUser({commit, state}, userId){
        commit('setUserStatus', 'Loading');
        axios.get('/api/users/' + userId)
            .then(res=>{
                commit('setUserStatus', 'Success');
               commit('setUser', res.data);
            }).catch(error => {
            commit('setUserStatus', 'Error');
        }).finally(()=>{

        })
    }
}
const mutations = {
    setUser(state, user){
        state.user = user;
    },
    setUserStatus(state, status){
        state.userStatus = status;
    }
}
export default{
    state, getters, actions, mutations
}
