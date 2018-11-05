export default {
    state: {
        logs: [],
        log: {},
        currentLog: null,
        timer: 0,
        interval: null
    },
    getters: {
        logs: state => state.logs,
        log: state => state.log,
        currentLog: state => state.currentLog,
        timer: state => state.timer
    },
    mutations: {
        setLogs(state, logs) {
            state.logs = logs
        },
        setCurrentLog(state, currentLog) {
            if(currentLog) {
                state.currentLog = currentLog
                state.timer = currentLog.length
            }
            if (currentLog && currentLog.is_running) {
                state.interval = setInterval(() => {
                    state.timer++
                }, 1000)
            } else {
                clearInterval(state.interval)
            }
        }
    },
    actions: {
        getLogs({commit}, query) {
            return axios.get('logs', {
                params: query
            }).then(res => {
                commit('setLogs', res.data)
                return res.data
            })
        },
        toggleTimer({commit}, card) {
            return axios.get('logs/toggle/' + card.id).then(res => {
                commit('setCurrentLog', res.data)
                return res.data
            })
        }
    }
}