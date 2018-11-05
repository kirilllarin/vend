<template>
    <div class="alert alert-danger" v-if="errors">
        <div v-for="error in errors" v-html="error"></div>
    </div>
</template>

<script>
    export default {
        name: 'ErrorMessage',
        data() {
            return {
                errors: null
            }
        },
        methods: {
            set(err) {
                this.errors = _.map(err.response.data, e => {
                    return e.join("<br>")
                })
            },
            clear() {
                this.error = null
            }
        }
    }
</script>