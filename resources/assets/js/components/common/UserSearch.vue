<template>
    <dropdown ref="dropdown">
        <div slot="dropdownContent">
            <div class="dropdown-title">
                {{ $t('users.index') }} <a @click="hide()" class="pull-right"><i class="icon-delete"></i></a>
            </div>
            <div class="dropdown-body">
                <input type="text"
                       @keyup="filterUsers"
                       v-model="filter.name"
                       :placeholder="$t('common.search')"
                       class="form-control form-control-sm">
                <div v-for="user in filteredUsers" @click="select(user)" class="media media-sm small">
                    <div class="media-left">
                        <img :src="user.image" alt="" class="avatar avatar-sm">
                    </div>
                    <div class="media-body">
                        <b>{{ user.name }}</b>
                        <span class="text-muted">{{ user.email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </dropdown>
</template>

<script>
    import Dropdown from './Dropdown.vue'

    export default {
        props: ['users', 'selected'],
        components: {
            Dropdown
        },
        data() {
            return {
                visible: false,
                filteredUsers: [],
                filter: {}
            }
        },
        mounted() {
            this.filterUsers()
        },
        methods: {
            show(e) {
                this.$refs.dropdown.show = true
                this.filterUsers()
                e.stopPropagation()
            },
            hide() {
                this.$refs.dropdown.show = false
                this.filter = {}
            },
            filterUsers() {
                let filterName, filterEmail
                this.filteredUsers = this.users.filter(u => {
                    filterName = filterEmail = true
                    if (this.filter.name && u.name.toLowerCase().indexOf(this.filter.name.toLowerCase()) === -1) {
                        filterName = false
                    }
                    if (this.filter.name && u.email.toLowerCase().indexOf(this.filter.name.toLowerCase()) === -1) {
                        filterEmail = false
                    }
                    return filterName || filterEmail
                })
            },
            select(item) {
                this.selected(item)
                this.filter = {}
                this.$refs.dropdown.show = false
            }
        }

    }
</script>