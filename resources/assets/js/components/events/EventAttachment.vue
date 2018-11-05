<template>
    <div class="media">
        <div class="media-left">
            <div class="media-stamp">
                <b>{{ eventFormat(event.start).day }}</b>
                {{ eventFormat(event.start).month }}
            </div>
        </div>
        <div class="media-body">
            <div><b>{{ event.title }}</b></div>
            <div class="text-muted small">{{ dateFormat(event.start, true) }}</div>
            <div class="text-muted small" v-if="event.end">{{ $t('events.end') }}: {{ dateFormat(event.end) }}</div>
            <div class="text-muted small" v-if="event.location">{{ $t('events.location') }}: {{ event.location }}</div>
        </div>
    </div>
</template>


<script>
    import mixins from '../../mixins'
    export default {
        props: ['event'],
        mixins: [mixins]
    }
</script>