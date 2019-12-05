<template>
    <section>
        <b-field position="is-right">
            <b-input placeholder="Search..."
                     type="search"
                     icon="magnify"
                     v-model="searchWord"
            >
            </b-input>
            <p class="control">
                <button class="button is-primary">Search</button>
            </p>
        </b-field>
        <b-table
                :data="filtered"
                paginated
                per-page="15"
                detailed
                detail-key="id"
                default-sort-direction="desc"
                :sort-icon="sortIcon"
                show-detail-icon
                :sort-icon-size="sortIconSize"
                default-sort="client.private_face"
                aria-next-label="Next page"
                aria-previous-label="Previous page"
                aria-page-label="Page"
                hoverable
                aria-current-label="Current page"
        >

            <template slot-scope="props">
                <b-table-column class="is-middle" field="id" label="#" width="40" sortable numeric>
                    {{ props.row.stmt_id }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_address" label="Адрес" sortable width="200">
                    {{ props.row.client_address }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client_private_face ? 'is-primary' : 'is-warning'">
                        {{  props.row.client_private_face ? 'Да' : 'Нет'}}
                    </span>

                </b-table-column>

                <b-table-column class="is-middle" field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">
                        {{ new Date(props.row.created_at).toLocaleDateString() }}
                    </span>
                </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                <article class="media">
                    <figure class="media-left">
                        <span class="avatar">
                            <span class="mdi mdi-account-circle-outline"></span>
                        </span>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <strong>{{ props.row.client_lastname }} {{ props.row.client_firstname }} {{
                                    props.row.client_patronymic }}</strong>
                                <small>Логин: {{ props.row.client_net_login }}</small>
                                <br>
                                {{ props.row.problem }}
                            </p>
                            <ul>
                                <li>Coffee</li>
                                <li>Tea</li>
                                <li>Milk</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </template>
        </b-table>
    </section>
</template>

<script>
    import {debounce} from "lodash";

    export default {
        props: ['data'],
        data() {
            return {
                json: JSON.parse(this.data),
                defaultSortDirection: 'asc',
                sortIcon: 'arrow-up',
                sortIconSize: 'is-small',
                searchWord: ''
            }
        },
        computed: {
            filtered: function () {
                console.log(this.json);
                let search = this.searchWord && this.searchWord.toLowerCase();
                let data = this.json;
                if(search) {
                    data = data.filter(row => {
                        return Object.keys(row).some(key => {
                            return String(row[key]).toLowerCase().indexOf(search) > -1
                        })
                    })
                }
                return data;
            }
        }
    }
</script>
