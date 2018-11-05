<template>
    <div>
        <topic-header :topic="topic"></topic-header>
        <div class="container">
            <div class="wbox">
                <loader ref="loader"></loader>
                <file-list :files="files"></file-list>
                <div class="no-record" v-if="noRecord">
                    {{ $t('files.empty') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FileList from '../files/FileList.vue'
    import TopicHeader from './TopicHeader.vue'
    import Loader from '../common/Loader.vue'
    import {mapGetters} from 'vuex'
    import mixins from '../../mixins'

    export default {
        mixins: [mixins],
        data() {
            return {
                noRecord: false
            }
        },
        components: {
            FileList,
            Loader,
            TopicHeader,
        },
        computed: {
            ...mapGetters({
                topic: 'topic',
                files: 'files'
            })
        },
        mounted() {
            this.$refs.loader.start()
            this.$store.dispatch('getTopics', this.$route.params.id).then(() => {
                this.$store.dispatch('getTopicChildren', {topicId: this.$route.params.id, type: 'files'}).then(() => {
                    this.$refs.loader.stop()
                    if(this.files.length === 0) this.noRecord = true
                })
            })
        }
    }
</script>
