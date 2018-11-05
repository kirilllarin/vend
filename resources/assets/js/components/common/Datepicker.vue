<template>
    <div>
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   :value="date"
                   :placeholder="placeholder"
                   @focus="showDatepicker = true">
            <input type="text"
                   class="form-control"
                   :value="time"
                   placeholder="HH:MM"
                   @change="setTime"
                   v-if="hasTime">
            <a class="btn btn-default" @click="clear()"><i class="icon-delete"></i></a>
        </div>
        <div class="dropdown" :class="{'open': showDatepicker}">
            <div class="dropdown-menu">
                <div class="datepicker"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import datepicker from 'jquery-ui/ui/widgets/datepicker'

    export default {
        props: {
            value: {},
            placeholder: {},
            hasTime: {}
        },
        data() {
            return {
                innerDate: null,
                innerTime: null,
                showDatepicker: false
            }
        },
        computed: {
            date() {
                return this.innerDate ? moment(this.innerDate).format('MMM D, YYYY') : ''
            },
            time() {
                return this.innerTime ? this.innerTime : ''
            }
        },
        mounted() {
            if(this.value) {
                let date = this.value.split(' ');
                this.innerDate = date[0]
                this.innerTime = this.hasTime ? date[1] : null
            }

            $(this.$el).find('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                onSelect: dateText => {
                    this.innerDate = dateText
                    this.change()
                }
            })
        },
        methods: {
            setTime(e) {
                if (e.target.value !== ''
                    && e.target.value.length === 5
                    && e.target.value.indexOf(':')
                ) {
                    this.innerTime = e.target.value
                    this.change()
                }
            },
            change() {
                this.$emit('input', this.innerDate + (this.hasTime ? ' ' + this.innerTime : ''))
                this.showDatepicker = false
            },
            clear() {
                this.innerTime = this.innerDate = null
                this.$emit('input', null)
                this.showDatepicker = false
            }
        }
    }
</script>