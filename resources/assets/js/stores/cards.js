import moment from 'moment'

export default {
    state: {
        cards: [],
        filteredCards: [],
        card: {},
        filters: {}
    },
    getters: {
        card: state => state.card,
        cards: state => state.cards,
        filters: state => state.filters,
        filteredCards: state => state.filteredCards
    },
    mutations: {
        setCardsFilter(state, filter) {
            state.filters = filter

            if (!state.filters) {
                state.filteredCards = state.cards;
            }
            let filterUser, filterDate, filterTitle
            state.filteredCards = state.cards.filter(c => {
                filterUser = filterDate = filterTitle = true
                if (state.filters.user) {
                    filterUser = c.assigned_to === state.filters.user.id
                }
                if (state.filters.title) {
                    filterTitle = c.title.toLowerCase().indexOf(state.filters.title.toLowerCase()) !== -1
                }
                if (state.filters.dueDate && state.filters.dueDate.length > 0) {
                    let dueDate = moment(c.due_date, 'YYYY-MM-DD')
                    filterDate = dueDate > state.filters.dueDate[0]
                        && dueDate <= state.filters.dueDate[1]
                }
                return (filterUser && filterDate && filterTitle)
            })
        },
        setCards(state, cards) {
            state.cards = state.filteredCards = cards
        },
        setCard(state, card) {
            state.card = card
        },
        updateCard(state, card) {
            state.card = Object.assign(state.card, card)
            state.cards = state.cards.map(c => {
                if (c.id === card.id) return state.card
                return c
            })
            state.filteredCards = state.filteredCards.map(c => {
                if (c.id === card.id) return state.card
                return c
            })
        },
        createCard(state, card) {
            state.card = card
            state.cards.push(card)
        },
        deleteCard(state, card) {
            state.cards = state.cards.filter(c => c.id !== card.id)
            state.filteredCards = state.filteredCards.filter(c => c.id !== card.id)
        }
    },
    actions: {
        getCard({commit}, card) {
            return axios.get('projects/' + card.project_id + '/cards/' + card.id).then(res => {
                commit('setCard', res.data)
                return res.data
            })
        },
        updateCard({commit}, card) {
            axios.get('cards/' + card.id).then(res => {
                commit('updateCard', res.data)
            })
        },
        saveCard({commit}, card) {
            if (card.id) {
                return axios.put('projects/' + card.project_id + '/cards/' + card.id, card).then(res => {
                    commit('updateCard', res.data)
                    return res.data
                })
            } else {
                return axios.post('projects/' + card.project_id + '/cards', card).then(res => {
                    commit('createCard', res.data)
                    return res.data
                })
            }
        },
        deleteCard({commit}, card) {
            axios.delete('projects/' + card.project_id + '/cards/' + card.id).then(() => {
                commit('deleteCard', card)
            })
        }
    }
}
