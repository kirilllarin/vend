<template>
    <div>
        <div class="title-bar">
            <div class="title-bar-title">
                <router-link to="/">{{ $t('projects.index') }}</router-link>
                <i class="icon-arrow-right title-bar-arrow"></i>
                {{ project.title }}
            </div>
            <div class="title-bar-info">
                <span class="label label-warn title-bar-label" v-if="project.due_date">
                    {{ dateFormat(project.due_date) }}
                </span>
            </div>
            <div class="title-bar-right">
                <nav class="title-bar-nav">
                    <a @click="editProject(project)"><i class="icon-pencil"></i></a>
                    <a @click="toggleInfo()" :class="{'active' : infoVisible}"><i class="icon-search"></i></a>
                    <a @click="toggleFavorite()"><i class="icon-star"></i></a>
                </nav>
            </div>
        </div>

        <div class="container-fluid">

            <search-bar :project="project" ref="searchBar"></search-bar>

            <loader ref="loader"></loader>
            <div class="column-list" v-if="loaded">
                <div class="column"
                     :class="{'column-archive': column.is_archive}"
                     :data-id="column.id"
                     v-for="column in project.columns"
                     v-show="hasToShowColumn(column)">
                    <div class="column-header">
                        <a @click="addCard(column)" class="column-add-card" v-if="currentUser.role !== 'client'">
                            <i class="icon icon-plus"></i>
                        </a>
                        <div class="column-title">
                            <i class="icon icon-checked" v-if="markToComplete(column)"></i>
                            {{ column.title }} <span v-if="markToComplete(column)" class="text-muted small">{{ $t('projects.completedColumn') }}</span>
                        </div>
                    </div>
                    <div class="column-cards">
                        <card v-for="card in columnCards(column)" :card="card" v-bind:key="card.id"></card>
                    </div>
                </div>
            </div>
            <div class="column-archive-toggle"
                 @click="showArchiveColumns = !showArchiveColumns"
                 v-show="archiveColumns.length > 0">
                <span>{{ $t('projects.toggleArchive') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchBar from './SearchBar.vue'
    import {mapGetters} from 'vuex'
    import Loader from '../common/Loader.vue'
    import Card from '../cards/Card.vue'
    import sortable from 'jquery-ui/ui/widgets/sortable'

    export default {
        components: {
            SearchBar,
            Card,
            Loader
        },
        computed: {
            ...mapGetters({
                project: 'project',
                cards: 'cards',
                filters: 'filters',
                filteredCards: 'filteredCards',
                currentUser: 'currentUser'
            }),
            archiveColumns() {
                return _.filter(this.project.columns, {is_archive: true})
            }
        },
        data() {
            return {
                columns: null,
                sortable: null,
                loaded: false,
                infoVisible: false,
                lastColumn: null,
                showArchiveColumns: false
            }
        },
        mounted() {
            this.navigateTo()

            this.$refs.searchBar.$on('changed', status => {
                this.infoVisible = status
            })
        },
        methods: {
            navigateTo() {
                let projectId = this.$route.params.id;

                this.$refs.loader.start()
                this.$store.commit('setCards', [])
                this.$store.commit('setProject', {})
                this.$store.dispatch('getProject', projectId).then(() => {

                    axios.get('projects/' + projectId + '/cards').then(cards => {
                        this.$store.commit('setCards', cards.data)
                        this.$refs.loader.stop()
                        this.loaded = true

                        // Bless you
                        setTimeout(() => {
                            this.bindSortable()
                        }, 100)

                    });
                })
            },
            removeSortable() {
                if (this.columns) {
                    this.columns.sortable('destroy')
                }
            },
            bindSortable() {
                this.columns = $(this.$el).find('.column-cards')
                if (this.columns.length === 0) {
                    return
                }
                this.sortable = this.columns.sortable({
                    connectWith: '.column-cards',
                    receive: (e, ui) => {
                        const columnId = $(ui.item).closest('.column').data('id');
                        const column = _.find(this.project.columns, {id: columnId})
                        const cardId = $(ui.item).data('id');
                        const card = _.find(this.cards, {id: cardId});
                        axios.put('projects/' + this.project.id + '/cards/' + cardId + '/column', {
                            column_id: column.id,
                            is_completed: this.markToComplete(column)
                        })
                            .then(res => {
                                _.map(this.cards, card => {
                                    if (card.id === cardId) {
                                        card.is_completed = res.data.is_completed
                                    }
                                })
                            })
                    }
                })
            },
            toggleInfo() {
                this.$refs.searchBar.toggle()
            },
            columnCards(column) {
                return this.filteredCards.filter(card => card.column_id === column.id)
            },
            hasToShowColumn(column) {
                return !column.is_archive || (column.is_archive && this.showArchiveColumns)
            },
            addCard(column) {
                this.$store.commit('setCard', {
                    column_id: column.id,
                    project_id: this.project.id,
                    is_completed: this.markToComplete(column)
                })
                this.$root.$emit('showModal', 'edit-card')
            },
            markToComplete(column) {
                return column.is_archive || (this.lastColumn && this.lastColumn.id === column.id)
            },
            editProject(project) {
                this.$store.commit('setProject', project)
                this.$root.$emit('showModal', 'edit-project')
            },
            toggleFavorite() {
                this.$store.dispatch('toggleFavorite', {
                    type: 'projects',
                    id: this.project.id
                })
            },
            updateProject(project) {
                this.lastColumn = _.filter(project.columns, {is_archive: false}).reverse()[0]
                this.removeSortable()
                this.bindSortable()
            }
        },
        watch: {
            '$route': 'navigateTo',
            'project': {
                handler: 'updateProject',
                deep: true
            }
        }
    }
</script>
