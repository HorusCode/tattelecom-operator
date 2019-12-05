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
                        {{ new Date(props.row.created_at).toLocaleDateString() }}
                    </span>
                </b-table-column>
                <b-table-column class="is-middle" label="Действия" sortable centered>
                    <b-button type="is-info" icon-right="pen"
                              @click="isAddServiceManagerModal = true, currentStatement = props.row.id"/>
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
        <b-modal :active.sync="isAddServiceManagerModal"
                 has-modal-card full-screen :can-cancel="false">
            <div class="modal-card" style="width: auto">
                <header class="modal-card-head">
                    <p class="modal-card-title">Назначение сотрудника</p>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-half is-offset-one-quarter">
                            <b-field v-for="(row, index) in rows" :key="index" :label="index + 1 + '. ФИО сотрудника'">
                                <div class="d-flex justify-content-between align-items-center">
                                    <b-autocomplete
                                            :data="serviceUsers"
                                            placeholder="ФИО сотрудника"
                                            field="firstname"
                                            :loading="isFetching"
                                            @typing="getAsyncData"
                                            expanded
                                            @select="option => selectUser(index, option)"
                                    >

                                        <template slot-scope="props">
                                            <div class="media">
                                                <div class="media-content">
                                                    {{ props.option.firstname }}
                                                </div>
                                            </div>
                                        </template>
                                    </b-autocomplete>
                                    <p class="control">
                                        <b-button type="is-danger"
                                                  outlined
                                                  size="is-small"
                                                  icon-right="close"
                                                  @click="removeRow(index)"
                                        />
                                    </p>
                                </div>
                            </b-field>
                            <b-button type="is-primary" class="is-center" @click="addRow">Добавить сотрудника</b-button>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="isAddServiceManagerModal = false">Закрыть</button>
                    <button class="button is-primary" :disabled="createWorkBtn" @click="sendData">Назначить</button>
                </footer>
            </div>
        </b-modal>
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
                isAddServiceManagerModal: false,
                isFetching: false,
                searchWord: '',
                selectedUsers: [],
                serviceUsers: [],
                currentStatement: null,
                rows: [{
                    value: null
                }]
            }
        },
        methods: {
            addRow: function () {
                this.rows.push({
                    value: null
                });
            },
            removeRow: function (index) {
                this.selectedUsers.splice(index, 1);
                this.rows.splice(index, 1);
            },
            getAsyncData: debounce(function (text) {
                if (text.length < 3) {
                    this.serviceUsers = [];
                    return;
                }
                this.isFetching = true;
                axios.get(`/api/users/search/service?text=${text}`)
                    .then(({data}) => {
                        this.serviceUsers = [];
                        data.forEach((res) => this.serviceUsers.push(res))
                    })
                    .catch((error) => {
                        this.serviceUsers = [];
                        throw error;
                    })
                    .finally(() => {
                        this.isFetching = false
                    })
            }, 500),
            selectUser(index, array) {
                if (array) {
                    this.selectedUsers.push(array.id);
                    this.rows[index].value = array.id;
                    this.selectedUsers = this.unique(this.selectedUsers);
                }
            },
            unique: function (arr) {
                return Array.from(new Set(arr));
            },
            sendData: function () {
                axios.post(`/api/works`, {
                    ids: this.userServiceIds(),
                    statement: this.currentStatement
                }).then(({data}) => {
                    this.$buefy.toast.open({
                        message: data ? 'Заявление на обслуживание оформлено!' : 'Произошла ошибка',
                        type: data ? 'is-success' : 'is-danger'
                    });
                    this.json = _.filter(this.json, item => {
                        return item.id !== this.currentStatement;
                    });
                    _.delay(() => {
                        this.isAddServiceManagerModal = false;
                        this.selectedUsers = [];
                        this.serviceUsers = [];
                        this.currentStatement = null;
                        this.rows = [{
                            value: null
                        }];
                    }, 300, 'later');
                });
            },
            userServiceIds: function () {
                let ids = this.rows.map(obj => {
                    return obj.value;
                });
                return this.unique(ids);
            }
        },
        computed: {
            createWorkBtn: function () {
                let status = false;
                if (this.rows.length === 0 || this.selectedUsers.length === 0) {
                    status = true;
                }
                this.rows.forEach(obj => {
                    if (obj.value === null) status = true;
                });
                return status;
            },
            filtered: function () {
                let search = this.searchWord && this.searchWord.toLowerCase();
                let data = this.json;
                if(search) {
                    data = data.filter(row => {
                        return Object.keys(row).some(key => {
                            return String(row[key]).toLowerCase().indexOf(search) > -1
                        })
                    })
                }
                console.log(data);
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