<template>
    <div>
        <div class="form-group">
            <div class="project-columns">
                <div v-for="(column, index) in innerColumns" class="form-group">
                    <input type="hidden" @change="changeOrder(column, $event)" class="column-order">
                    <div class="input-group">
                        <div class="btn btn-default">
                            <i class="icon-arrow-heads"></i>
                        </div>
                        <input type="text" v-model="column.title" class="form-control">
                        <span class="btn btn-default" @click="column.is_archive = ! column.is_archive">
                            <i :class="column.is_archive  ? 'icon-checked' : 'icon-unchecked'"></i>
                            {{ $t('projects.archiveColumn') }}
                        </span>
                        <a @click="innerColumns.splice(index, 1)" class="btn btn-default">
                            <i class="icon-delete"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="input-group">
            <input type="text" v-model="newColumn.title" class="form-control"
                   :placeholder="$t('projects.form.columnTitle')">
            <a class="btn btn-default"
               @click="newColumn.title !== '' ? addColumn() : ''"
               :disabled="newColumn.title == ''">{{ $t('projects.form.addColumn') }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            columns: {}
        },
        data() {
            return {
                newColumn: {
                    title: '',
                    is_archive: false
                },
                innerColumns: [],
                columnsEl: null
            }
        },
        mounted() {
            this.columnsEl = $(this.$el).find('.project-columns');
            this.innerColumns = this.columns
            this.bindSortable()
        },
        beforeDestroy() {
            this.removeSortable()
        },
        methods: {
            removeSortable() {
                this.columnsEl.sortable('destroy')
            },
            bindSortable() {
                this.columnsEl.sortable({
                    update: (e, ui) => {
                        $('.column-order').each(function (ndx, el) {
                            $(el).val(ndx);
                            let evt = new Event('change');
                            el.dispatchEvent(evt);
                        })
                    }
                })
            },
            changeOrder(column, e) {
                column.order = e.target.value
            },
            addColumn() {
                this.innerColumns.push({
                    title: this.newColumn.title,
                    order: this.innerColumns.length,
                    is_archive: false,
                })
                this.newColumn.title = ''
                this.$emit('change', this.innerColumns)
            },
        }
    }
</script>