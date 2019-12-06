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
                per-page="10"
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

                <b-table-column class="is-middle" field="created_at" label="Дата завершения" sortable centered>
                    <span class="tag is-success">
                        {{ moment(props.row.updated_at).format('DD.MM.YYYY HH:mm') }}
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
                            <header class="heading">
                                <h4>{{props.row.client.lastname}} {{props.row.client.firstname}} {{props.row.client.patronymic}}</h4>
                                <h6 class="has-text-weight-normal">Логин: {{ props.row.client.net_login }}</h6>
                            </header>
                            <div class="content-body">
                                <ul class="list m-0">
                                    <li class="list-item">
                                        Адрес: <strong>{{props.row.client.address}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Паспорт: <strong>{{props.row.client.passport_number}} {{props.row.client.passport_series}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Телефон: <strong>{{props.row.client.phone}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Что случилось: <p class="has-text-weight-bold">{{props.row.problem}}</p>
                                    </li>
                                </ul>
                                <h6>Назначены на работу:</h6>
                                <ul class="list m-0">
                                    <li class="list-item" v-for="service in props.row.service">
                                        <span class="is-block">ФИО: <strong>{{ getFullName(service) }}</strong></span>
                                        <span class="is-block">Телефон: <strong>{{ service.phone }}</strong></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>
            </template>
            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon
                                    icon="emoticon-sad"
                                    size="is-large">
                            </b-icon>
                        </p>
                        <p>Данных нет.</p>
                    </div>
                </section>
            </template>
        </b-table>
    </section>
</template>

<script>
    import moment from 'moment'
    export default {
        props: ['data'],
        data() {
            console.log(JSON.parse(this.data));
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
