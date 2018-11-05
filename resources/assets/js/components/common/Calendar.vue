<template>
    <div>
        <span>{{ date.format('MMM') }}</span>
        <a @click="switchMonth('backward')"><<<</a>
        <a @click="switchMonth('forward')">>>></a>
        <div v-for="day in date.daysInMonth()">{{ day }}</div>
    </div>
</template>
<script>
    import moment from 'moment'

    export default {
        data() {
            return {
                date: moment(new Date()),
                month: moment().month()
            }
        },
        mounted() {
//            console.log(this.date.day(0));
            console.log(this.date.startOf('month'));
            console.log(this.date.endOf('month'));
        },
        methods: {
            switchMonth(dir) {
                if(dir === 'forward') {
                    this.month++
                } else {
                    this.month--
                }
                this.date = this.date.month(this.month)
                this.$forceUpdate()
                console.log(this.date)
            }
        }
    }
</script>