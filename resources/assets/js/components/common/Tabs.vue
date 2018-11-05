<template>
    <div class="tabs">
        <div class="tabs-nav">
            <slot name="tabs"></slot>
        </div>
        <div class="tabs-content">
            <slot name="tabsContent"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            $(this.$el).find('.tabs-nav a').on('click', this.switchTab)
        },
        beforeDestroy() {
            $(this.$el).find('.tabs-nav a').off('click', this.switchTab)
        },
        methods: {
            switchTab(e) {
                let target = $(e.currentTarget).data('target')
                $(this.$el).find('.tabs-nav a').removeClass('active')
                $(e.currentTarget).addClass('active')
                $(this.$el).find('.tabs-content .tabs-panel').removeClass('active')
                $(this.$el).find('.tabs-content ' + target).addClass('active')
            }
        }
    }
</script>