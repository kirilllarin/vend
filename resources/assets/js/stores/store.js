import Vue from 'vue'
import Vuex from 'vuex'
import cards from './cards'
import topics from './topics'
import projects from './projects'
import users from './users'
import logs from './logs'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        cards,
        topics,
        users,
        logs,
        projects,
    }
})