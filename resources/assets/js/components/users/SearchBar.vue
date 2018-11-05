<template>
    <div class="info-bar" :class="{'open': show }">

        <div class="form-group text-right">
            <a @click="clearFilter()" class="text-muted"><i class="icon-delete"></i> {{ $t('filter.clear') }}</a>
        </div>

        <div class="mb-lg">
            <input type="text" class="form-control" @keyup="setQuery" v-model="query">
        </div>

        <h4 class="sidebar-subtitle">{{ $t('users.role') }}</h4>
        <div class="info-bar-links">
            <a @click="setRole(role)"
               :class="{'active' : selectedRole == role}"
               v-for="role in roles">{{ $t('users.roles.' + role) }}</a>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import mixins from '../../mixins'
    import Datepicker from '../common/Datepicker.vue'

    export default {
        mixins: [mixins],
        data() {
            return {
                filter: {},
                selectedRole: null,
                query: '',
                show: false,
                roles: ['admin', 'manager', 'editor', 'client']
            }
        },
        components: {
            Datepicker
        },
        methods: {
            setRole(role) {
                this.filter = Object.assign({}, this.filter, {role: role})
                this.selectedRole = role
                this.filterCard()
            },
            setQuery() {
                this.filter = Object.assign({}, this.filter, {
                    name: this.query.trim() !== '' ? this.query : null
                })
                this.filterCard()
            },
            clearFilter() {
                this.filter = {}
                this.query = ''
                this.selectedRole = null
                this.filterCard()
            },
            filterCard() {
                this.$store.commit('setUsersFilter', this.filter)
            },
        }
    }
</script>