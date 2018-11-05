<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">Logs</div>
        </div>
        <div class="container mb-lg">
            <div class="wbox" v-show="loaded">
                <canvas id="chart"></canvas>
                <div class="chart-filter form-inline">
                    <div class="form-group">
                        <a @click="setView('weeks')" class="btn btn-default" :class="{'active': this.view === 'weeks'}">Week</a>
                        <a @click="setView('months')" class="btn btn-default" :class="{'active': this.view === 'months'}">Month</a>
                    </div>
                    <div class="form-group">
                        <select v-model="user" class="form-control" @change="fetch()">
                            <option :value="null">All user</option>
                            <option :value="user.id" v-for="user in users">{{ user.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select v-model="project" class="form-control" @change="fetch()">
                            <option :value="null">All project</option>
                            <option :value="project.id" v-for="project in projects">{{ project.title }}</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <a @click="step(-1)" class="btn btn-default">Prev</a>
                        <a @click="step(1)" class="btn btn-default">Next</a>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <span class="label label-warn label-sm" v-if="tracked">
                    {{ $t('logs.tracked') }}: <b>{{ elapsedFormat(tracked) }}</b>
                </span>
            </div>
            <h4>{{ $t('logs.entries') }}</h4>

            <div class="wbox">
                <loader ref="loader"></loader>
                <div class="no-record" v-if="empty">{{ $t('logs.empty') }}</div>
                <logs-list :logs="logs" v-if="loaded"></logs-list>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import moment from 'moment'
    import Chart from 'chart.js/dist/Chart'
    import LogsList from './LogsList.vue'
    import Loader from '../common/Loader.vue'
    import mixins from '../../mixins'

    export default {
        components: {
            LogsList,
            Loader
        },
        mixins: [mixins],
        data() {
            return {
                view: 'weeks',
                chartBuild: null,
                project: null,
                tracked: null,
                user: null,
                labels: [],
                loaded: false,
                empty: false,
                start: moment().startOf('isoWeek'),
                end: moment().endOf('isoWeek'),
                chartData: []
            }
        },
        computed: {
            ...mapGetters({
                logs: 'logs',
                projects: 'projects',
                users: 'users'
            })
        },
        mounted() {
            Chart.defaults.global.defaultFontColor = "#fff";

            this.$store.dispatch('getProjects')
            this.fetch()
        },
        methods: {
            fetch() {
                let query = {
                    start: this.start.format('YYYY-MM-DD'),
                    user_id: this.user,
                    project_id: this.project,
                    end: this.start.clone().endOf(this.view === 'weeks' ? 'isoWeek' : 'month').format('YYYY-MM-DD')
                }

                this.$refs.loader.start()
                this.$store.commit('setLogs', [])

                this.$store.dispatch('getLogs', query).then(() => {
                    this.loaded = true
                    this.empty = this.logs.length === 0
                    this.$refs.loader.stop()
                    this.populateData()
                })
            },
            setView(view) {
                this.view = view
                if (view === 'months') {
                    this.start.startOf('month')
                } else {
                    this.start.startOf('isoWeek')
                }
                this.fetch()
            },
            buildChart() {
                const chartEl = document.getElementById('chart')
                const ctx = chartEl.getContext('2d');

                if (this.chartBuild) {
                    window.chart.data.datasets[0].data = this.chartData
                    window.chart.data.labels = this.labels
                    window.chart.update()
                    return
                }

                window.chart = new Chart(ctx, {
                    type: 'line',

                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: "Logs",
                            backgroundColor: 'rgba(19, 213, 208, .5)',
                            borderColor: 'rgb(19, 213, 208)',
                            data: this.chartData,
                        }]
                    },

                    options: {
                        scaleFontColor: 'rgb(255, 255, 255)',
                        defaultFontColor: 'rgb(255, 255, 255)',
                        defaultColor: 'rgb(255, 255, 255)',
                        responsive: true,
                        maintainAspectRatio: true,
                        layout: {
                            padding: {
                                left: 30,
                                right: 30,
                                top: 10,
                                bottom: 10
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: (value, index, values) => {
                                        return this.elapsedFormat(value)
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: (tooltipItem, data) => {
                                    return this.elapsedFormat(tooltipItem.yLabel)
                                }
                            }
                        }
                    }
                });

                this.chartBuild = true
            },
            populateData() {
                let length = this.view === 'weeks' ? 7 : this.start.daysInMonth();
                let dates = _.groupBy(this.logs, log => {
                    return log.created_at.split(' ')[0]
                })

                let newDates = []
                _.map(dates, (date, key) => {
                    newDates[key] = _.sumBy(date, 'length')
                })

                let curDate = this.start.clone()
                let curDateFormat
                this.labels = []
                this.chartData = []

                this.tracked = _.sumBy(this.logs, 'length')

                for (let i = 0; i < length; i++) {
                    curDateFormat = curDate.format('YYYY-MM-DD')
                    this.chartData[i] = 0

                    if (newDates[curDateFormat]) {
                        this.chartData[i] = newDates[curDateFormat]
                    }

                    curDate.add(1, 'days')
                    this.labels.push(curDateFormat)
                }

                this.buildChart()
            },
            step(dir) {
                dir === -1
                    ? this.start.subtract(1, this.view)
                    : this.start.add(1, this.view)

                this.fetch()
            }
        }
    }
</script>