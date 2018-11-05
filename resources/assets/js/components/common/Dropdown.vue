<template>
    <div class="dropdown" :class="[{'open': show, 'dropdown-right': right, 'dropdown-inline': inline, 'dropdown-top': top}, classes]">
        <a href="#" @click.prevent="toggle()" class="dropdown-toggle">
            <slot name="dropdownToggle"></slot>
        </a>
        <div class="dropdown-menu">
            <slot name="dropdownContent"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['right', 'inline', 'top', 'classes'],
        data() {
            return {
                show: false
            }
        },
        mounted() {
            document.addEventListener('click', this.handleClick)
        },
        beforeDestroy() {
            document.removeEventListener('click', this.handleClick)
        },
        methods: {
            toggle() {
                this.show = !this.show
            },
            hide() {
                this.show = false
            },
            handleClick(e) {
                let target = $(e.target)
                if(
                    target.closest('.dropdown-toggle').length !== 0
                    || target.hasClass('.dropdown-toggle')
                    || target.closest('.dropdown-menu').length !== 0
                ) {
                    return null
                }

                this.show = false
            }
        }
    }
</script>
