<template>
    <section>
        <b-field position="is-right">
            <b-input placeholder="Поиск.."
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
                    {{ props.row.client_address }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client_private_face ? 'is-primary' : 'is-warning'">
                        {{  props.row.client_private_face ? 'Да' : 'Нет'}}
                    </span>

                </b-table-column>

                <b-table-column class="is-middle" field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">
                        {{ moment.utc(props.row.created_at).format('DD.MM.YYYY HH:mm') }}
                    </span>
                </b-table-column>
                <b-table-column class="is-middle" label="Действия" centered>
                        <b-button rounded @click="startWork(props.row)">Начать</b-button>
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
                                <h4>{{props.row.client_lastname}} {{props.row.client_firstname}} {{props.row.client_patronymic}}</h4>
                                <h6 class="has-text-weight-normal">Почта: {{ props.row.client_email }}</h6>
                            </header>
                            <div class="content-body">
                                <ul class="list m-0">
                                    <li class="list-item">
                                        Адрес: <strong>{{props.row.client_address}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Паспорт: <strong>{{props.row.client_passport_number}} {{props.row.client_passport_series}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Телефон: <strong>{{props.row.client_phone}}</strong>
                                    </li>
                                    <li class="list-item">
                                        Что случилось: <p class="has-text-weight-bold">{{props.row.problem}}</p>
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
                moment: window.moment
            }
        },
        methods: {
            removeRow: function (index) {
                this.selectedUsers.splice(index, 1);
                this.rows.splice(index, 1);
            },
            startWork: function(arr)
            {
                axios.post(`/api/works/start`, {
                    work_id: arr.work_id,
                    statement_id: arr.id
                }).then(({data}) => {
                    this.$buefy.toast.open({
                        message: data.status ? 'Выполнение заявления начато!' : 'Произошла ошибка',
                        type: data.status ? 'is-success' : 'is-danger'
                    });
                    this.json = _.filter(this.json, item => {
                        return item.id !== arr.id;
                    });
                });
            }
        },
        computed: {
            filtered: function () {
                let search = this.searchWord && this.searchWord.toLowerCase();
                let data = this.json;
                if (search) {
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

<style scoped>
    .control.is-expanded {
        width: 100%;
        margin-right: 2rem;
    }
</style>
