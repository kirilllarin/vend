<template>
    <div v-if="currentLog" class="timer" :class="{'active' : currentLog && currentLog.is_running }">
        <div class="timer-left cursor-pointer" @click="toggleTimer(currentLog.card)">
            <div>{{ currentLog.card.title }}</div>
            <b>{{ elapsedFormat(timer) }}</b>
        </div>
        <div class="timer-right">
            <dropdown :top="true" class="timer-dropdown">
                <div slot="dropdownToggle"><i class="icon-chevron-up"></i></div>
                <div slot="dropdownContent">
                    <div class="dropdown-links">
                        <a @click="toggleTimer(log.card)" v-for="log in currentUser.logs">
                            <span class="label label-primary label-sm pull-right">
                                {{ elapsedFormat(log.length) }}
                            </span>
                            {{ log.card.title }}
                        </a>
                    </div>
                </div>
            </dropdown>
        </div>

    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import mixins from '../../mixins'
    import Dropdown from '../common/Dropdown.vue'

    export default {
        mixins: [mixins],
        components: {
            Dropdown
        },
        computed: {
            ...mapGetters({
                currentLog: 'currentLog',
                currentUser: 'currentUser',
                timer: 'timer',
                logs: 'logs'
            })
        },
        methods: {
            toggleTimer(card) {
                this.$store.dispatch('toggleTimer', card)
            }
        }
    }
</script>