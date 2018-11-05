import moment from 'moment'

export default {
    methods: {
        fromNow(date) {
            return moment(date, "YYYY-MM-DD HH:mm").fromNow();
        },
        fileSize(byte) {
            return Math.round(byte / 1024) + ' Kb'
        },
        eventFormat(date) {
            let _date = moment(date, 'YYYY-MM-DD HH:mm')
            return {
                day: _date.format('D'),
                month: _date.format('MMMM')
            }
        },
        emit(event, data) {
            this.$root.$emit(event, data)
        },
        on(event, cb) {
            this.$root.$on(event, cb)
        },
        setErrors(err) {
            this.$refs.errorMessage
                ? this.$refs.errorMessage.set(err)
                : console.error('Reference error component not found')
        },
        clearErrors() {
            this.$refs.errorMessage.clear();
        },
        timeFormat(date) {
            return date
                ? moment(date, 'YYYY-MM-DD HH:mm').format('HH:mm')
                : null
        },
        elapsedFormat(time) {
            let sec_num = parseInt(time, 10);
            let hours = Math.floor(sec_num / 3600);
            let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            let seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return hours + ':' + minutes + ':' + seconds;
        },
        dateFormat(date, full) {
            if (!date) {
                return null
            }

            const format = full ? 'HH:mm on MMM DD, YYYY' : 'MMM DD, YYYY'

            if (date && date.isMoment) {
                return date.format(format)
            }
            return moment(date, 'YYYY-MM-DD HH:mm').format(format);
        },
        monthDateFormat(date) {
            return moment(date, 'YYYY-MM-DD').format('MMMM DD.');
        },
        showFavorite(favorite) {
            if (favorite.type === 'project') {
                this.$route.go('projects/' + favorite.related.id)
            }
            if (favorite.type === 'topic') {
                this.$route.go('topic/' + favorite.related.id)
            }
        },
        strToInterval(date) {
            let start, end
            switch (date) {
                case 'thisWeek':
                    start = moment().startOf('isoWeek')
                    end = moment().endOf('isoWeek')
                    break
                case 'nextWeek':
                    start = moment().add(1, 'weeks').startOf('isoWeek')
                    end = moment().add(1, 'weeks').endOf('isoWeek')
                    break
                case 'thisMonth':
                    start = moment().startOf('month')
                    end = moment().endOf('month')
                    break
                case 'nextMonth':
                    start = moment().add(1, 'months').startOf('month')
                    end = moment().add(1, 'months').endOf('month')
                    break
            }
            return [start, end]
        },
        setTab(target, e) {
            let tabButton = $(e.currentTarget);
            let parent = tabButton.closest('.tabs')
            parent.find('.tabs-links a').removeClass('active')
            tabButton.addClass('active');
            parent.find('.tabs-panel').removeClass('active')
            parent.find('.tabs-wrapper #' + target).addClass('active')
            e.preventDefault()
        },
        collectionRemove(collection, item) {
            let index = _.findIndex(collection, {id: item.id})
            collection.splice(index, 1)
        },
        collectionUpdate(collection, item) {
            let index = _.findIndex(collection, {id: item.id})
            collection.splice(index, 1, item)
        }
    }
}
