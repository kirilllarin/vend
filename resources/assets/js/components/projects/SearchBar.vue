<template>
    <div class="info-bar" :class="{'open': visible}">

        <div class="form-group text-right">
            <a @click="clearFilter()" class="text-muted"><i class="icon-delete"></i> {{ $t('filter.clear') }}</a>
        </div>

        <div class="form-group">
            <input type="text" class="form-control" @keyup="setQuery" v-model="query">
        </div>

        <h4 class="sidebar-subtitle">{{ $t('filter.dueDate') }}</h4>
        <div class="info-bar-links">
            <a @click="setDueDate(key)"
               :class="{'active' : selectedDate == key}"
               v-for="(filter, key) in dateFilters">{{ $t(filter) }}</a>
        </div>

        <h4 class="sidebar-subtitle">{{ $t('filter.assignedTo') }}</h4>

        <div class="media media-sm small"
             v-for="user in project.users"
             @click="setUser(user)"
             :class="{'active' : selectedUser == user.id}">
            <div class="media-left">
                <img :src="user.image" alt="" class="avatar avatar-sm">
            </div>
            <div class="media-body">
                {{ user.name }}
                <div class="text-muted">{{ user.email }}</div>
            </div>
        </div>

    </div>
</template>

<script>
    import moment from 'moment'
    import mixins from '../../mixins'
    import Datepicker from '../common/Datepicker.vue'

    export default {
        props: ['project'],
        mixins: [mixins],
        data() {
            return {
                filter: {},
                selectedUser: null,
                selectedDate: null,
                query: '',
                visible: false,
                dateFilters: {
                    thisWeek: 'filter.thisWeek',
                    nextWeek: 'filter.nextWeek',
                    thisMonth: 'filter.thisMonth',
                    nextMonth: 'filter.nextMonth'
                }
            }
        },
        components: {
            Datepicker
        },
        methods: {
            show() {
                this.visible = true
                this.$emit('changed', this.visible)
            },
            hide() {
                this.visible = false
                this.$emit('changed', this.visible)
            },
            toggle() {
                this.visible = ! this.visible
                this.$emit('changed', this.visible)
            },
            setUser(user) {
                this.filter = Object.assign({}, this.filter, {user: user})
                this.selectedUser = user.id
                this.filterCard()
            },
            setDueDate(date) {
                this.selectedDate = date
                this.filter = Object.assign({}, this.filter, {
                    dueDate: this.strToInterval(date)
                })
                this.filterCard()
            },
            setQuery() {
                this.filter = Object.assign({}, this.filter, {
                    title: this.query.trim() !== '' ? this.query : null
                })
                this.filterCard()
            },
            clearFilter() {
                this.filter = {}
                this.query = ''
                this.selectedDate = null
                this.selectedUser = null
                this.filterCard()
            },
            filterCard() {
                this.$store.commit('setCardsFilter', this.filter)
            },
        }
    }
</script>