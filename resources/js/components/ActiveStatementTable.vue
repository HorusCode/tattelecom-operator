<template>
    <section>
        <b-field position="is-right">
            <b-input placeholder="Поиск..."
                     type="search"
                     icon="magnify"
                     v-model="searchWord"
            >
            </b-input>
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
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_address" label="Адрес" sortable width="200">
                    {{ props.row.client.address }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client.private_face ? 'is-primary' : 'is-warning'">
                        {{  props.row.client.private_face ? 'Да' : 'Нет'}}
                    </span>

                </b-table-column>

                <b-table-column class="is-middle" field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">
                        {{ moment.utc(props.row.created_at).format('DD.MM.YYYY HH:mm') }}
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
                                <strong>{{ props.row.client.lastname }} {{ props.row.client.firstname }} {{
                                    props.row.client.patronymic }}</strong>
                                <small>Логин: {{ props.row.client.net_login }}</small>
                            </p>
                            <h2>Что случилось:</h2> {{ props.row.problem }}
                            Отправлены на обслуживание:
                            <ul>
                                <li v-for="service in props.row.service">{{ getFullName(service) }}</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </template>
        </b-table>
    </section>
</template>

<script>
    import moment from 'moment'
    export default {
        props: ['data'],
        data() {
            return {
                json: JSON.parse(this.data),
                defaultSortDirection: 'asc',
                sortIcon: 'arrow-up',
                sortIconSize: 'is-small',
                searchWord: '',
                moment: moment
            }
        },
        methods: {
            getFullName: function (arr) {
                return arr.lastname + ' ' + arr.firstname + ' ' + arr.patronymic;
            },
            inArr: function (val, arr) {
                if(!(arr instanceof Object)) return String(arr).toLowerCase().indexOf(val) > -1;
                return Object.keys(arr).some(key => this.inArr(val, arr[key]));
            }
        },
        computed: {
            filtered: function () {
                let data = this.json,
                    search = this.searchWord && this.searchWord.toLowerCase();
                let filter = (val, arr) => {
                    return arr.filter(row => {
                        return Object.keys(row).some(key => {
                            return this.inArr(search, row[key]);
                        });
                    });
                };

                if (search) {
                    data = filter(search, data);
                }
                return data;
            }
        }
    }
</script>
