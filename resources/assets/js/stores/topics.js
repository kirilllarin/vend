export default {
    state: {
        topics: [],
        message: {
            event: {},
            files: []
        },
        topic: {
            users: [],
        },
        messages: [],
        events: [],
        files: [],
        topicsFetched: false
    },
    getters: {
        topic: state => state.topic,
        message: state => state.message,
        messages: state => state.messages,
        files: state => state.files,
        events: state => state.events,
        topics: state => state.topics
    },
    mutations: {
        setTopics(state, topics) {
            state.topics = topics
        },
        setTopic(state, topic) {
            state.topic = topic
        },
        setMessage(state, message) {
            state.message = message
        },
        setChildren(state, {children, topicId, type}) {
            state[type] = children
        },
        setTopicsFetched(state, status) {
            state.topicsFetched = status
        },
        setTopicFromTopics(state, topicId) {
            state.topics.map(t => {
                if(t.id == topicId) state.topic = t
            })
        },
        createTopic(state, topic) {
            state.topics.push(topic)
        },
        updateTopic(state, topic) {
            state.topic = topic
            state.topics = state.topics.map(t => {
                if(t.id === topic.id) return topic
                return t
            })
        },
        createMessage(state, message) {
            state.messages.unshift(message)
            state.topics.map(topic => {
                if(message.topic_id === topic.id) {
                    topic.latest_message = message
                }
            })
        },
        updateMessage(state, message) {
            state.messages = state.messages.map(m => {
                if(m.id === message.id) return message
                return m
            })
        },
        deleteMessage(state, message) {
            state.messages = state.messages.filter(m => m.id !== message.id)
        },
        deleteTopic(state, topic) {
            state.topic = {
                users: []
            }
            state.topics = state.topics.filter(t => t.id !== topic.id)
        }
    },
    actions: {
        getTopics({state, commit}, topicId) {
            if(state.topicsFetched) {
                if(topicId) {
                    commit('setTopicFromTopics', topicId)
                    return Promise.resolve()
                }
                return Promise.resolve()
            }
            return axios.get('topics').then(res => {
                let topics = res.data
                commit('setTopics', topics)
                commit('setTopicsFetched', true)
                if(topicId) {
                    commit('setTopicFromTopics', topicId)
                }
                return topics
            })
        },
        getTopic({state, commit, dispatch}, {topicId, type}) {
            if(state.topic.id !== topicId) {
                return axios.get('topics/' + topicId).then(topic => {
                    commit('setTopic', Object.assign(topic.data, {files: [], events: [], messages: []}))
                    return dispatch('getTopicChildren', {topicId, type})
                })
            } else {
                commit('setTopic', Object.assign(state.topic, {files: [], events: [], messages: []}))
                return dispatch('getTopicChildren', {topicId, type})
            }
        },
        getTopicChildren({commit}, {topicId, type}) {
            commit('setChildren', {children: [], topicId, type})
            return axios.get('topics/' + topicId + '/' + type).then(children => {
                commit('setChildren', {children: children.data, topicId, type})
                return children.data
            })
        },
        saveTopic({commit}, topic) {
            if(topic.id) {
                return axios.put('topics/' + topic.id, topic).then(res => {
                    commit('updateTopic', res.data)
                    return res.data
                })
            } else {
                return axios.post('topics', topic).then(res => {
                    commit('createTopic', res.data)
                    return res.data
                })
            }
        },
        saveMessage({commit}, {topicId, message}) {
            if(message.id) {
                return axios.put('topics/' + topicId + '/messages/' + message.id, message).then(res => {
                    commit('updateMessage', res.data)
                })
            } else {
                return axios.post('topics/' + topicId + '/messages', message).then(res => {
                    commit('createMessage', res.data)
                    return res.data
                })
            }
        },
        deleteMessage({commit}, message) {
            return axios.delete('topics/' + message.topic_id + '/messages/' + message.id).then(() => {
                commit('deleteMessage', message)
            })
        },
        deleteTopic({commit}, topic) {
            return axios.delete('topics/' + topic.id).then(() => {
                commit('deleteTopic', topic)
                return;
            })
        }
    }
}
