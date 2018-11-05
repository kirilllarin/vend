export default {
    state: {
        users: [],
        filteredUsers: [],
        user: {},
        currentUser: {},
        notifications: [],
        favorites: [],
        favoritesFetched: false
    },
    getters: {
        users: state => state.users,
        filteredUsers: state => state.filteredUsers,
        user: state => state.user,
        notifications: state => state.notifications,
        favorites: state => state.favorites,
        favoritesFetched: state => state.favoritesFetched,
        currentUser: state => state.currentUser,
        unReadNotifications: state => {
            return state.notifications.filter(n => n.read_at == null)
        },
        isAdmin: state => {
            return state.currentUser.role === 'admin'
        },
        isEditor: state => {
            return state.currentUser.role === 'admin' || state.currentUser.role === 'editor'
        }
    },
    mutations: {
        setUsers(state, users) {
            state.users = state.filteredUsers = users
        },
        setUser(state, user) {
            state.user = user
        },
        setNotifications(state, notifications) {
            state.notifications = notifications
        },
        setFavorites(state, favorites) {
            state.favorites = favorites
        },
        setFavoritesFetched(state, status) {
            state.favoritesFetched = status
        },
        setFavorite(state, {favorite, type, id}) {
            if(favorite.id) {
                state.favorites.unshift(favorite)
            } else {
                state.favorites = state.favorites.filter(f => {
                    return f.type !== type || f.favoritable.id !== id
                })
            }
        },
        setCurrentUser(state, currentUser) {
            state.currentUser = currentUser
        },
        setUsersFilter(state, filters) {
            let filterRole, filterName
            state.filteredUsers = state.users.filter(u => {
                filterRole = filterName = true
                if (filters.name) {
                    filterName = (
                        u.name.toLowerCase().indexOf(filters.name.toLowerCase()) > -1
                        || u.email.toLowerCase().indexOf(filters.name.toLowerCase()) > -1
                    )
                }
                if (filters.role) {
                    filterRole = u.role === filters.role
                }
                return (filterRole && filterName)
            })
        },
        createUser(state, user) {
            state.users.push(user)
        },
        updateUser(state, user) {
            state.user = user
            state.users = state.filteredUsers = state.users.map(u => {
                if (u.id === user.id) return user
                return u
            })
        },
        deleteUser(state, user) {
            state.users =  state.users.filter(u => u.id !== user.id)
            state.filteredUsers =  state.users.filter(u => u.id !== user.id)
        }
    },
    actions: {
        getNotifications({commit}) {
            return axios.get('notifications').then(res => {
                commit('setNotifications', res.data)
                return res.data
            })
        },
        getUsers({commit}) {
            return axios.get('users').then(res => {
                commit('setUsers', res.data)
                return res.data
            })
        },
        getFavorites({commit}) {
            axios.get('favorites').then(res => {
                commit('setFavorites', res.data)
                commit('setFavoritesFetched', true)
            })
        },
        getCurrentUser({commit}) {
            axios.get('users/current').then(res => {
                commit('setCurrentUser', res.data)
                if(res.data.logs) {
                    const runningLog = _.find(res.data.logs, {is_running: 1})
                    if(runningLog) {
                        commit('setCurrentLog', runningLog)
                    }
                    else {
                        commit('setCurrentLog', res.data.logs[0])
                    }
                }

            })
        },
        toggleFavorite({commit}, {type, id}) {
              axios.post(type + '/' + id + '/favorite').then(res => {
                  commit('setFavorite', {favorite: res.data, type: type, id: id})
              })
        },
        saveUser({commit}, user) {
            if (user.id) {
                return axios.put('users/' + user.id, user).then(res => {
                    commit('updateUser', res.data)
                    return res.data
                })
            } else {
                return axios.post('users', user).then(res => {
                    commit('createUser', res.data)
                    return res.data
                })
            }
        },
        deleteUser({commit}, user) {
            return axios.delete('users/' + user.id).then(res => {
                commit('deleteUser', user)
            })
        }
    }
}