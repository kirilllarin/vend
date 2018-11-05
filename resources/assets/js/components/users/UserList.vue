<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">{{ $t('users.index') }}</div>
            <div class="title-bar-right">
                <div class="title-bar-nav">
                    <a @click="showSearch()"><i class="icon-search"></i></a>
                </div>
                <a @click="create()" class="btn btn-primary title-bar-btn">{{ $t('users.create') }}</a>
            </div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>
            <search-bar ref="searchBar"></search-bar>
            <div class="card-list">
                <a @click="show(user)" class="card project-card card-center" v-for="user in filteredUsers">
                    <div class="card-body">
                        <span class="card-title">
                            <img :src="user.image" alt="" class="avatar avatar-large">
                        </span>
                        <div><b>{{ user.name }}</b></div>
                        {{ user.email }}
                    </div>
                    <div class="card-footer">
                        <span>{{ $t('users.roles.' + user.role.toString()) }}</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import EditUser from './EditUser.vue'
    import ShowUser from './ShowUser.vue'
    import Loader from '../common/Loader.vue'
    import SearchBar from './SearchBar.vue'

    export default {
        name: 'UserList',
        components: {
            EditUser,
            ShowUser,
            Loader,
            SearchBar
        },
        data() {
            return {
                filter: {}
            }
        },
        computed: {
            ...mapGetters({
                users: 'users',
                filteredUsers: 'filteredUsers'
            })
        },
        mounted() {
            this.$refs.loader.start()
            this.$store.commit('setUsers', [])
            this.$store.dispatch('getUsers').then(() => {
                this.$refs.loader.stop()
            })
        },
        methods: {
            create() {
                this.$store.commit('setUser', {})
                this.$root.$emit('showModal', 'edit-user')
            },
            show(user) {
                this.$store.commit('setUser', user)
                this.$root.$emit('showModal', 'show-user')
            },
            showSearch() {
                this.$refs.searchBar.show = ! this.$refs.searchBar.show
            }
        }
    }
</script>
