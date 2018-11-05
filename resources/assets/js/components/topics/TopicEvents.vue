<template>
    <div>
        <topic-header :topic="topic"></topic-header>
        <div class="container">
            <div class="wbox">
                <loader ref="loader"></loader>
                <div class="no-record" v-if="noRecord">
                    {{ $t('events.empty') }}
                </div>
                <event-attachment v-for="event in events" v-bind:key="event" :event="event"></event-attachment>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
import TopicHeader from '../topics/TopicHeader.vue'
import Loader from '../common/Loader.vue'
import EventAttachment from '../events/EventAttachment.vue'

export default {
    components: {
        TopicHeader,
        EventAttachment,
        Loader,
    },
    data() {
        return {
            noRecord: false
        }
    },
    computed: {
        ...mapGetters({
            topic: 'topic',
            events: 'events'
        })
    },
    mounted() {
        this.$refs.loader.start()
        this.$store.dispatch('getTopics', this.$route.params.id).then(() => {
            this.$store.dispatch('getTopicChildren', {topicId: this.$route.params.id, type: 'events'}).then(() => {
                this.$refs.loader.stop()
                if(this.events.length === 0) this.noRecord = true
            })
        })

    }
}
</script>
