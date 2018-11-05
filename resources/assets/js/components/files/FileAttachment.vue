<template>
    <div class="media">
        <div class="media-left">
            <img :src="file.thumbnail" alt="" class="media-image" v-if="file.is_image">
            <div class="media-stamp" v-if=" ! file.is_image">
                <i class="icon-file"></i>
            </div>
        </div>
        <div class="media-body">
            <div class="media-header">
                <a :href="file.path" target="_blank"><b>{{ file.name }}</b></a>
            </div>
            <span class="text-muted">{{ fileSize(file.size) }}</span>
        </div>
    </div>
</template>

<script>
    import mixins from '../../mixins'
    export default {
        props: ['file'],
        mixins: [mixins]
    }
</script>