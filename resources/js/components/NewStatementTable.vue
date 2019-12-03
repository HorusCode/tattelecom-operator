<template>
    <section>
        <b-table
                :data="json"
                :paginated="isPaginated"
                :per-page="perPage"
                :current-page.sync="currentPage"
                :pagination-simple="isPaginationSimple"
                :pagination-position="paginationPosition"
                :default-sort-direction="defaultSortDirection"
                :sort-icon="sortIcon"
                :sort-icon-size="sortIconSize"
                default-sort="created_at"
                aria-next-label="Next page"
                aria-previous-label="Previous page"
                aria-page-label="Page"
                aria-current-label="Current page">

            <template slot-scope="props">
                <b-table-column field="id" label="#" width="40" sortable numeric>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="problem" label="Проблема" sortable>
                    {{ props.row.problem }}
                </b-table-column>

                <b-table-column field="user_id" label="Клиент" sortable>
                    {{ props.row.user_id }}
                </b-table-column>

                <b-table-column field="created_at" label="Lfnf" sortable centered>
                    <span class="tag is-success">
                        {{ new Date(props.row.created_at).toLocaleDateString() }}
                    </span>
                </b-table-column>
            </template>
        </b-table>
    </section>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                json: JSON.parse(this.data),
                isPaginated: true,
                isPaginationSimple: false,
                paginationPosition: 'bottom',
                defaultSortDirection: 'asc',
                sortIcon: 'arrow-up',
                sortIconSize: 'is-small',
                currentPage: 1,
                perPage: 5
            }
        },

    }
</script>