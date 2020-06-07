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
                sort-icon-size="is-small"
                hoverable
        >

            <template slot-scope="props">
                <b-table-column centered :visible="props.row.hasOwnProperty('work_status')" field="work_status"
                                label="Статус" width="40" sortable>
                    <span v-if="props.row.hasOwnProperty('statement') && !props.row.statement.status && props.row.work_status < 2" class="tag" :class="props.row.work_status ? 'is-warning' : 'is-danger'">{{  props.row.work_status ? 'В процессе' : 'Простаивает' }}</span>
                    <span v-else class="tag is-success">Завершён</span>
                </b-table-column>

                <b-table-column field="id" label="ID" width="40" sortable centered>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="client.address" label="Адрес" sortable width="200">
                    {{ props.row.client.address }}
                </b-table-column>

                <b-table-column field="client.private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client.private_face ? 'is-primary' : 'is-warning'">{{  props.row.client.private_face ? 'Да' : 'Нет'}}</span>
                </b-table-column>

                <b-table-column field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">{{ moment.utc(props.row.updated_at).local().format('DD.MM.YYYY HH:mm') }}</span>
                </b-table-column>

                <b-table-column :visible="showBtn" label="Действия" centered>
                    <div v-if="role === 'client_operator'">
                        <b-tooltip label="Назначить сотрудника" type="is-info">
                            <b-button type="is-info" icon-right="pen"
                                      @click="isAddServiceManagerModal = true, currentStatement = props.row.id"/>
                        </b-tooltip>
                        <b-tooltip label="Удалить" type="is-danger">
                            <b-button type="is-danger" icon-right="delete-outline"
                                      @click="confirmDelete(), currentStatement = props.row.id"/>
                        </b-tooltip>


                    </div>
                    <div v-else>
                        <b-button v-if="btnType === 'start'" rounded @click="startWork(props.row)">Начать</b-button>
                        <b-button v-else rounded @click="stopWork(props.row)">Завершить</b-button>
                    </div>
                </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                <user-list :data="props.row"/>
            </template>
            <template slot="empty">
                <empty-data/>
            </template>
        </b-table>
        <b-modal :active.sync="isAddServiceManagerModal"
                 has-modal-card full-screen :can-cancel="false">
            <statement-modal @success="showNotification" @close-modal="isAddServiceManagerModal = false"
                             :current-id="currentStatement"/>
        </b-modal>
    </section>
</template>

<script>
  import {debounce} from 'lodash';
  import moment from 'moment';

  import UserList from './UserList';
  import EmptyData from './EmptyData';

  import {search, fullName} from '../mixins';
  import StatementModal from './StatementModal';

  const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    console.log(userId);
  export default {
    components: {StatementModal, EmptyData, UserList},
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
    mounted() {
      Echo.private('App.Models.User.' + userId).notification((notification) => {
        this.$buefy.notification.open({
          duration: 3000,
          message: notification.message,
          position: 'is-bottom-right',
          type: 'is-info',
          hasIcon: true
        });
      });
    },
    data() {
      return {
        json: JSON.parse(this.data),
        isAddServiceManagerModal: false,
        searchWord: '',
        isNotification: false,
        docsUrl: [],
        moment: moment,
        currentStatement: undefined,
      };
    },
    methods: {
      showNotification: function(data) {
        this.isAddServiceManagerModal = false;

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
          this.currentStatement = undefined;
        }, 300, 'later');
      },
      startWork: function(arr) {
        axios.post(`/api/works/start`, {
          work_id: arr.id,
          statement_id: arr.statement_id,
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
          work_id: arr.id,
          statement_id: arr.statement_id,
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
        axios.delete(`/api/statements/${this.currentStatement}`).then(({data}) => {
          this.$buefy.toast.open({
            message: data.message,
            type: data.status ? 'is-success' : 'is-danger',
          });
          this.json = _.filter(this.json, item => {
            return item.id !== this.currentStatement;
          });
          this.currentStatement = null;
        });
      },
    },
  };
</script>

<style scoped>
    .control.is-expanded {
        width: 100%;
        margin-right: 2rem;
    }
    .table td {
        vertical-align: middle;
    }
</style>
