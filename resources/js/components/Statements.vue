<template>
    <section>
        <b-notification
                type="is-success"
                has-icon
                aria-close-label="Close notification"
                :active.sync="isNotification"
                role="alert"
        >
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="url in docsUrl">
                    <div class="list-group-content p-0">
                        <div class="list-group-content-left">
                            <span class="mdi mdi-file-word-outline item-icon "></span>
                        </div>
                        <div class="list-group-content-left">
                            <div class="list-heading">{{ url.name }}</div>
                        </div>
                        <div class="list-group-content-right">
                            <b-button tag="a" :href="url.url" type="is-success" outlined inverted>Скачать</b-button>
                        </div>
                    </div>
                </li>
            </ul>
        </b-notification>
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
                sort-icon="arrow-up"
                show-detail-icon
                sort-icon-size="is-small"
                hoverable
        >

            <template slot-scope="props">
                <b-table-column centered :visible="props.row.hasOwnProperty('work_status')" field="work_status"
                                label="Статус" width="40" sortable>
                    <span class="tag" :class="props.row.work_status ? 'is-success' : 'is-danger'">{{  props.row.work_status ? 'В процессе' : 'Простаивает'}}</span>
                </b-table-column>

                <b-table-column field="id" label="ID" width="40" sortable centered>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="client.address" label="Адрес" sortable width="200">{{ props.row.client.address
                    }}
                </b-table-column>

                <b-table-column field="client.private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client.private_face ? 'is-primary' : 'is-warning'">{{  props.row.client.private_face ? 'Да' : 'Нет'}}</span>
                </b-table-column>

                <b-table-column field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">{{ moment(props.row.created_at).format('DD.MM.YYYY HH:mm') }}</span>
                </b-table-column>

                <b-table-column :visible="showBtn" label="Действия" centered>
                    <div v-if="role === 'client_operator'">
                        <b-button type="is-info" icon-right="pen"
                                  @click="isAddServiceManagerModal = true, currentStatement = props.row.id"/>
                        <b-button type="is-danger" icon-right="delete-outline" @click="confirmDelete(), currentStatement = props.row.id"/>
                    </div>
                    <div v-else>
                        <b-button v-if="btnType === 'start'" rounded @click="startWork(props.row)">Начать</b-button>
                        <b-button v-else rounded @click="stopWork(props.row)">Завершить</b-button>
                    </div>

                </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                <user-list :data="props"/>
            </template>
            <template slot="empty">
                <empty-data/>
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
                                                    {{ getFullName(props.option) }}
                                                </div>
                                            </div>
                                        </template>
                                        <template slot="empty">No results found</template>
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
  import {debounce} from 'lodash';
  import moment from 'moment';

  import UserList from './UserList';
  import EmptyData from './EmptyData';

  import {search, fullName} from '../mixins';

  export default {
    components: {EmptyData, UserList},
    mixins: [search, fullName],
    props: {
      data: {
        type: String,
        default: '',
      },
      showBtn: {
        type: [String, Boolean],
        default: false,
      },
      role: {
        type: String,
        default: 'service',
      },
      btnType: {
        type: String,
      },
    },
    data() {
      return {
        json: JSON.parse(this.data),
        isAddServiceManagerModal: false,
        isFetching: false,
        searchWord: '',
        selectedUsers: [],
        serviceUsers: [],
        isNotification: false,
        docsUrl: [],
        moment: moment,
        currentStatement: null,
        rows: [
          {
            value: null,
          }],
      };
    },
    methods: {
      addRow: function() {
        this.rows.push({
          value: null,
        });
      },
      removeRow: function(index) {
        this.selectedUsers.splice(index, 1);
        this.rows.splice(index, 1);
      },
      getAsyncData: debounce(function(text) {
        if (text.length < 3) {
          this.serviceUsers = [];
          return;
        }
        this.isFetching = true;
        axios.get(`/api/users/search/service?text=${text}`).then(({data}) => {
          this.serviceUsers = [];
          data.forEach((res) => this.serviceUsers.push(res));
        }).catch((error) => {
          this.serviceUsers = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
      selectUser(index, array) {
        if (array) {
          this.selectedUsers.push(array.id);
          this.rows[index].value = array.id;
          this.selectedUsers = this.unique(this.selectedUsers);
        }
      },
      unique: function(arr) {
        return Array.from(new Set(arr));
      },
      sendData: function() {
        axios.post(`/api/works`, {
          ids: this.userServiceIds(),
          statement: this.currentStatement,
        }).then(({data}) => {
          this.$buefy.toast.open({
            message: data.status ? 'Заявление на обслуживание оформлено!' : 'Произошла ошибка',
            type: data.status ? 'is-success' : 'is-danger',
          });
          this.isNotification = data.status;
          this.docsUrl = data.files;

          this.json = _.filter(this.json, item => {
            return item.id !== this.currentStatement;
          });
          _.delay(() => {
            this.isAddServiceManagerModal = false;
            this.selectedUsers = [];
            this.serviceUsers = [];
            this.currentStatement = null;
            this.rows = [
              {
                value: null,
              }];
          }, 300, 'later');
        });
      },
      userServiceIds: function() {
        let ids = this.rows.map(obj => {
          return obj.value;
        });
        return this.unique(ids);
      },
      startWork: function(arr) {
        axios.post(`/api/works/start`, {
          work_id: arr.work_id,
          statement_id: arr.id,
        }).then(({data}) => {
          this.$buefy.toast.open({
            message: data.status ? 'Выполнение заявления начато!' : 'Произошла ошибка',
            type: data.status ? 'is-success' : 'is-danger',
          });
          this.json = _.filter(this.json, item => {
            return item.id !== arr.id;
          });
        });
      },
      stopWork: function(arr) {
        axios.post(`/api/works/stop`, {
          work_id: arr.work_id,
          statement_id: arr.id,
        }).then(({data}) => {
          this.$buefy.toast.open({
            message: data.status ? 'Выполнение заявления завершено!' : 'Произошла ошибка',
            type: data.status ? 'is-success' : 'is-danger',
          });
          this.json = _.filter(this.json, item => {
            return item.id !== arr.id;
          });
        });
      },
      confirmDelete() {
        this.$buefy.dialog.confirm({
          title: 'Удалить заявление',
          message: 'Вы действительно хотите <b>удалить</b> это заявление?',
          confirmText: 'Удалить',
          cancelText: 'Нет',
          type: 'is-danger',
          hasIcon: true,
          onConfirm: () => this.deleteStatement(),
        });
      },
      deleteStatement: function() {
        axios.delete(`/api/statement/${this.currentStatement}`).then(({data}) => {
          this.$buefy.toast.open({
            message: data.message,
            type: data.status ? 'is-success' : 'is-danger',
          });
          this.json = _.filter(this.json, item => {
            return item.id !== this.currentStatement;
          });
          this.currentStatement = null;
        });
      }
    },
    computed: {
      createWorkBtn: function() {
        let status = false;
        if (this.rows.length === 0 || this.selectedUsers.length === 0) {
          status = true;
        }
        this.rows.forEach(obj => {
          if (obj.value === null) {
            status = true;
          }
        });
        return status;
      },
    },
  };
</script>

<style scoped>
    .control.is-expanded {
        width: 100%;
        margin-right: 2rem;
    }
</style>
