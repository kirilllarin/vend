<template>
    <header class="header">
        <a href="#" class="header-title"></a>
        <div class="tabs-nav tabs-nav-dark tabs-nav-justified">
            <a href="#" :class="{'active' : activeTab == 'navigation'}" @click.prevent="switchTab('navigation')">
                <i class="icon-bars"></i>
            </a>
            <a href="#" :class="{'active' : activeTab == 'notifications'}" @click.prevent="switchTab('notifications')">
                <span class="counter" v-if="unReadNotifications.length > 0">{{ unReadNotifications.length }}</span>
                <i class="icon-bell"></i>
            </a>
            <a href="#" :class="{'active' : activeTab == 'user'}" @click.prevent="switchTab('user')">
                <i class="icon-user"></i>
            </a>
        </div>
        <div class="header-blocks">
            <div v-if="activeTab == 'navigation'">
                <nav class="header-nav">
                    <router-link :to="{name: 'projects'}" exact><i class="icon-stack"></i> {{ $t('projects.index') }}</router-link>
                    <router-link :to="{name: 'users'}" v-if="isEditor"><i class="icon-users"></i> {{ $t('users.index') }}</router-link>
                    <router-link :to="{name: 'calendar'}" v-if="isEditor"><i class="icon-calendar"></i> {{ $t('events.index') }}</router-link>
                    <router-link :to="{name: 'logs'}" v-if="isEditor"><i class="icon-chart"></i> {{ $t('logs.index') }}</router-link>
                    <router-link :to="{name: 'topics'}"><i class="icon-bubbles"></i> {{ $t('topics.index') }}</router-link>
                </nav>
                <favorites></favorites>
            </div>
            <div v-if="activeTab == 'notifications'">
                <notifications></notifications>
            </div>
            <div v-if="activeTab == 'user'">
                <div class="text-center">
                    <div class="mb-md">
                        <div class="form-group">
                            <img :src="currentUser.image" alt="" class="avatar">
                        </div>
                        <b>{{ currentUser.name }}</b>
                        <div class="text-muted">{{ $t('users.roles.' + currentUser.role) }}</div>
                    </div>
                    <div class="form-group">
                        <a href="/logout" class="btn btn-dark btn-block">{{ $t('auth.logout') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom-nav">
            <timer></timer>
        </div>
    </header>
</template>

<script>
    import {mapGetters} from 'vuex'

    import Timer from '../logs/Timer'
    import Notifications from './Notifications.vue'
    import Favorites from './Favorites.vue'

    export default {
        data() {
            return {
                activeTab: 'navigation'
            }
        },
        components: {
            Timer,
            Favorites,
            Notifications
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser',
                isEditor: 'isEditor',
                unReadNotifications: 'unReadNotifications'
            })
        },
        methods: {
            switchTab(tab) {
                this.activeTab = tab
            },
            logout() {
                axios.get('logout').then(() => {
                    window.location.href = '/login'
                })
            }
        }

    }
</script>
