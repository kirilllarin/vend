export default {
    state: {
        project: {
            is_archive: 0,
            columns: [],
            users: [],
        },
        projects: []
    },
    getters: {
        project: state => state.project,
        projects: state => state.projects,
    },
    mutations: {
        setProjects(state, projects) {
            state.projects = projects
        },
        setProject(state, project) {
            state.project = project
        },
        createProject(state, project) {
            state.projects.push(project)
        },
        deleteProject(state, project) {
            state.projects = state.projects.filter(p => p.id !== project.id)
        }
    },
    actions: {
        getProjects({commit}, query) {
            return axios.get('projects', {params: query}).then(res => {
                commit('setProjects', res.data)
                return res.data;
            })
        },
        getProject({commit}, id) {
            return axios.get('projects/' + id).then(res => {
                commit('setProject', res.data)
                return res.data;
            })
        },
        saveProject({commit}, project) {
            if (project.id) {
                return axios.put('projects/' + project.id, project).then(res => {
                    commit('setProject', res.data)
                    return res.data
                })
            } else {
                return axios.post('projects', project).then(res => {
                    commit('setProject', res.data)
                    commit('createProject', res.data)
                    return res.data
                })
            }
        },
        deleteProject({commit}, project) {
            return axios.delete('projects/' + project.id).then(() => {
                commit('deleteProject', project)
            })
        }
    }
}
