<template>
    <section>
        <b-field label="Клиент">
            <b-autocomplete
                    :data="clients"
                    placeholder="ФИО клиента"
                    field="fullname"
                    :loading="isFetching"
                    @typing="getAsyncData"
                    expanded
                    @select="option => selectClient(option)"
            >
                <template slot-scope="props">
                    <div class="media">
                        <div class="media-content">
                            {{ props.option.fullname }}
                        </div>
                    </div>
                </template>
                <template slot="empty">Нет результатов</template>
            </b-autocomplete>
        </b-field>
        <b-field label="Выбранные услуги">
            <b-table
                    :data="clientServices"
                    ref="table"
                    detailed
                    hoverable
                    custom-detail-row
                    :show-detail-icon="false"
                    detail-key="title">
                <template slot-scope="props">
                    <b-table-column field="detail" width="50">
                        <a v-if="props.row.problems.length > 0" role="button" @click="detail(props.row)">
                            <i class="mdi mdi-chevron-right" :class="{'mdi-rotate-90': props.row.detailed}"/>
                        </a>
                    </b-table-column>
                    <b-table-column
                            field="title"
                            label="Название услуги"
                            sortable>
                        {{ props.row.title }}
                    </b-table-column>
                </template>

                <template slot="detail" slot-scope="props">
                    <tr class="detail">
                        <td colspan="2">
                            <ul class="list m-0">
                                <li class="list-item d-flex justify-content-between" v-for="item in props.row.problems"
                                    :key="item.name">
                                    <span> {{ item.name }}</span>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </template>
                <template slot="empty">
                    <empty-data/>
                </template>
            </b-table>
        </b-field>

        <b-field label="Проблема">
            <b-input type="textarea"
                     minlength="10"
                     maxlength="600"
                     v-model="problem"
                     placeholder="Опимшите проблему подробно">
            </b-input>
        </b-field>
        <div class="d-flex justify-content-end">
            <b-button type="is-primary" :disabled="selected.length === 0 || problem.length === 0" @click="save">
                Сохранить
            </b-button>
        </div>
    </section>
</template>

<script>
  import {fullName} from '../mixins';

  import {debounce} from 'lodash';
  import EmptyData from './EmptyData';

  export default {
    name: 'FormStatement',
    components: {EmptyData},
    mixins: [fullName],
    data() {
      return {
        isFetching: false,
        clients: [],
        problem: '',
        selected: '',
        clientServices: [],
      };
    },
    methods: {
      selectClient: function(arr) {
        this.selected = arr.id;
        this.clientServices = arr.services;
      },
      detail: function(row) {
        row.detailed = !row.detailed;
        this.$refs.table.toggleDetails(row);
      },
      getAsyncData: debounce(function(text) {
        if (text.length < 3) {
          this.clients = [];
          return;
        }
        this.isFetching = true;
        axios.get(`/api/users/search/client?text=${text}`).then(({data}) => {
          this.clients = [];
          data.forEach((res) => this.clients.push({
            id: res.id,
            fullname: this.getFullName(res),
            services: res.services
          }));
        }).catch((error) => {
          this.clients = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
      save: function() {
        axios.post(`/api/statements`, {
          client_id: this.selected,
          problem: this.problem,
        }).then(({data}) => {
          this.$buefy.toast.open({
            message: data.message,
            type: data.status ? 'is-success' : 'is-danger',
          });

          _.delay(() => {
            this.problem = '';
            this.selected = '';
            this.clients = [];
          }, 300, 'later');
        });
      },
    },

  };
</script>

<style scoped>

</style>
