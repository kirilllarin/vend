require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'
import VueI18n from 'vue-i18n'
import moment from 'moment'

import {mapGetters} from 'vuex'
import store from './stores/store'
import ShowProject from './components/projects/ShowProject.vue'
import MainNav from './components/common/MainNav.vue'
import ProjectList from './components/projects/ProjectList.vue'
import UserList from './components/users/UserList.vue'
import Files from './components/files/Files.vue'
import Modal from './components/common/Modal.vue'
import Logs from './components/logs/Logs.vue'
import Calendar from './components/events/Calendar.vue'
import TopicList from './components/topics/TopicList.vue'
import TopicMessages from './components/topics/TopicMessages.vue'
import TopicFiles from './components/topics/TopicFiles.vue'
import TopicEvents from './components/topics/TopicEvents.vue'
import Account from './components/account/Account.vue'
import mixins from './mixins'

Vue.use(VueI18n)
Vue.use(VueRouter);

Vue.mixin(mixins)

const routes = [
    {path: '/', name: 'projects', component: ProjectList},
    {path: '/projects/archive', name: 'projects-archive', component: ProjectList},
    {path: '/files', name: 'files', component: Files},
    {path: '/projects/:id', name: 'project', component: ShowProject},
    {path: '/users', name: 'users', component: UserList},
    {path: '/logs', name: 'logs', component: Logs},
    {path: '/calendar', name: 'calendar', component: Calendar},
    {path: '/account', name: 'account', component: Account},
    {path: '/topics', name: 'topics', component: TopicList},
    {path: '/topics/:id', name: 'topic', component: TopicMessages},
    {path: '/topics/:id/files', name: 'topic-files', component: TopicFiles},
    {path: '/topics/:id/events', name: 'topic-events', component: TopicEvents},
];

const router = new VueRouter({
    linkActiveClass: 'active',
    routes
});

import enTranslation from './translations/en'

const messages = {
    en: enTranslation
}

const i18n = new VueI18n({
    locale: 'en',
    messages,
})

const app = new Vue({
    router,
    store,
    i18n,
    el: '#app',
    data() {
        return {
            modalVisible: false
        }
    },
    computed: {
        ...mapGetters({
            currentUser: 'currentUser'
        })
    },
    methods: {
        getUser(user) {
            if (user && user.role !== 'client') {
                this.$store.dispatch('getUsers')
            }
        }
    },
    components: {
        MainNav,
        Modal,
    },
    mounted() {
        moment.locale('en');

        this.$store.dispatch('getNotifications').then(() => {
            this.$root.$emit('notifications:fetched')
        })

        this.$store.dispatch('getCurrentUser')
    },
    watch: {
        'currentUser': 'getUser'
    }
});
