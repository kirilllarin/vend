<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">{{ $t('projects.index') }}</div>
            <div class="title-bar-right">
                <nav class="title-bar-tabs">
                    <router-link :to="{name: 'projects'}" exact>{{ $t('projects.active') }}</router-link>
                    <router-link :to="{name: 'projects-archive'}">{{ $t('projects.archive') }}</router-link>
                </nav>
                <a @click="createProject()" class="btn btn-primary title-bar-btn">{{ $t('projects.create') }}</a>
            </div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>
            <div class="card-list" v-if="projects.length">
                <a @click="showProject(project)" class="card card-lg" v-for="project in projects">
                    <div class="card-heading">
                        <h3>{{ project.title }}</h3>
                    </div>
                    <div class="card-body">
                        <div v-if="project.due_date">
                            <div class="text-muted">{{ $t('projects.dueDate') }}</div>
                            <b>{{ dateFormat(project.due_date) }}</b>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="progress" v-if="progress(project) !== false">
                            <div class="text-muted small">{{ progress(project) + '%' }}</div>
                            <div class="progress-wrapper">
                                <div class="progress-bar" :style="'width:' + progress(project) + '%'"></div>
                            </div>
                        </div>
                        <div class="card-users">
                            <img :src="user.image" class="avatar" v-for="(user, index) in project.users" v-if="index <= displayUser - 1">
                            <span class="counter" v-if="project.users.length > displayUser">+{{ project.users.length - displayUser }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div v-if="empty && $route.name !== 'projects-archive'" class="text-center">
                <div class="no-record mb-md">
                    {{ $t('projects.empty') }}
                </div>
                <a @click="createProject()" class="btn btn-default">{{ $t('projects.create') }}</a>
            </div>
            <div v-if="empty && $route.name == 'projects-archive'" class="text-center">
                <div class="no-record mb-md">
                    {{ $t('projects.emptyArchive') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Loader from '../common/Loader.vue'

    export default {
        components: {
            Loader
        },
        computed: {
            ...mapGetters({
                projects: 'projects'
            })
        },
        data() {
            return {
                loaded: false,
                empty: false,
                displayUser: 10
            }
        },
        mounted() {
            this.fetch()
        },
        methods: {
            fetch() {
                this.$refs.loader.start()
                this.$store.commit('setProjects', [])
                this.empty = false
                this.$store.dispatch('getProjects', {archive: this.$route.name === 'projects-archive' ? 1 : 0}).then(() => {
                    if (this.projects.length === 0) {
                        this.empty = true
                    }
                    this.$refs.loader.stop()
                })
            },
            progress(project) {
                if (project.cards_count === 0)
                    return false

                return Math.round((project.completed_cards_count / project.cards_count) * 100)
            },
            createProject() {
                this.$store.commit('setProject', {columns: [], users: []})
                this.$root.$emit('showModal', 'edit-project');
            },
            showProject(project) {
                this.$router.push({name: 'project', params: {id: project.id}})
            },
        },
        watch: {
            '$route': 'fetch'
        }
    }
</script>
