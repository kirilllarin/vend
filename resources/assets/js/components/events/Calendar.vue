<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">
                {{ $t('events.index') }}
            </div>
            <div class="title-bar-right">
                <nav class="title-bar-nav">
                    <a :href="'/calendar/' + currentUser.event_url + '/calendar.ics'" target="_blank">{{ $t('events.calendar') }}</a>
                </nav>
            </div>
        </div>
        <div class="container">
            <loader ref="loader"></loader>
            <div v-for="(events, index) in groups">
                <h4>{{ getDate(index) }}</h4>
                <div class="wbox">
                    <event-attachment v-for="event in events" v-bind:key="event" :event="event"></event-attachment>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../common/Loader.vue'
    import EventAttachment from './EventAttachment.vue'
    import {mapGetters} from 'vuex'
    import moment from 'moment'

    export default {
        components: {
            Loader,
            EventAttachment
        },
        computed: {
            ...mapGetters({
                currentUser: 'currentUser'
            })
        },
        mounted() {
            this.fetchEvents()
        },
        data() {
            return {
                groups: {},
                loaded: false
            }
        },
        methods: {
            getDate(date) {
                return moment(date, 'YYYY-MM').format('MMMM, YYYY')
            },
            fetchEvents() {
                this.$refs.loader.start()
                axios.get('events').then(res => {

                    this.events = res.data

                    this.events.forEach(e => {
                        let date = new Date(e.start)
                        if (!this.groups[date.getFullYear() + '-' + (date.getMonth() + 1)]) {
                            this.groups[date.getFullYear() + '-' + (date.getMonth() + 1)] = []
                        }
                        this.groups[date.getFullYear() + '-' + (date.getMonth() + 1)].push(e)
                    })

                    let a = _.groupBy(this.events, (e) => {
                        let date = new Date(e.start)
                        return date.getFullYear()
                    })

                    this.$forceUpdate()
                    this.$refs.loader.stop()
                    this.loaded = true
                })
            }
        }
    }
</script>