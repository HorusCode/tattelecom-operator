<template>
    <div class="modal-card" style="width: auto">
        <b-loading :is-full-page="true" :active.sync="isLoading" :can-cancel="true"/>
        <header class="modal-card-head">
            <p class="modal-card-title">Назначение сотрудника</p>
        </header>
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half is-offset-one-quarter">
                    <b-field v-for="(row, index) in rows" :key="index"
                             :label="index + 1 + '. ФИО сотрудника'">
                        <b-field :type="{'is-warning': !row.isUniq}">
                            <b-autocomplete
                                    :data="serviceUsers"
                                    placeholder="ФИО сотрудника"
                                    field="fullname"
                                    :loading="isFetching"
                                    @typing="getAsyncData"
                                    expanded
                                    @select="option => selectUser(index, option)"
                            >
                                <template slot-scope="props">
                                    {{ props.option.fullname }}
                                    <b-tag :type="props.option.work_count <= 2 ? 'is-success' : 3 >= props.option.work_count && props.option.work_count <= 4 ? 'is-warning' : 'is-danger'">
                                        {{ props.option.work_count }}
                                    </b-tag>
                                </template>
                                <template slot="empty">Нет результатов</template>
                            </b-autocomplete>
                            <p class="control">
                                <b-button type="is-danger"
                                          icon-right="close"
                                          @click="removeRow(index)"
                                />
                            </p>
                        </b-field>
                    </b-field>
                    <b-button type="is-primary" class="is-center" @click="addRow">Добавить сотрудника</b-button>
                </div>
            </div>
        </section>

        <footer class="modal-card-foot">
            <button class="button" type="button" @click="$emit('close-modal')">Закрыть</button>
            <button class="button is-primary" :disabled="createWorkBtn" @click="sendData">Назначить</button>
        </footer>
    </div>
</template>

<script>
  import {debounce} from 'lodash';
  import {fullName} from '../mixins';

  export default {
    name: 'StatementModal',
    props: {
      currentId: {
        required: true,
      },
    },
    mixins: [fullName],
    data() {
      return {
        serviceUsers: [],
        isFetching: false,
        isLoading: false,
        rows: [
          {
            id: undefined,
            isUniq: true,
          },
        ],
      };
    },
    computed: {
      createWorkBtn: function() {
        let status = false;
        if (this.rows.length === 0) {
          status = true;
        }
        this.rows.forEach(obj => {
          if (obj.id === undefined) {
            status = true;
          }
        });
        return status;
      },
    },
    methods: {
      addRow: function() {
        this.rows.push({
          id: undefined,
          isUniq: true,
        });
      },
      removeRow: function(index) {
        this.isUniqRows();
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
          data.forEach((res) => this.serviceUsers.push({
            id: res.id,
            fullname: this.getFullName(res),
            work_count: res.work_count,
          }));
        }).catch((error) => {
          this.serviceUsers = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
      selectUser(index, array) {
        if (array) {
          this.rows[index].id = array.id;
          this.rows[index].isUniq = true;

          this.isUniqRows();
        }
      },
      isUniqRows: function() {
        if (this.rows.length > 1) {
          const lookup = this.rows.reduce((a, e) => {
            a[e.id] = ++a[e.id] || 0;
            return a;
          }, {});
          this.rows.forEach((row) => {
            row.isUniq = lookup[row.id] === 0;
          });
        }
      },
      unique: function(arr) {
        return Array.from(new Set(arr));
      },
      userServiceIds: function() {
        let ids = this.rows.map(obj => {
          return obj.id;
        });
        return this.unique(ids);
      },
      sendData: function() {
        this.isLoading = true;
        axios.post(`/api/works`, {
          ids: this.userServiceIds(),
          current_id: this.currentId,
        }).then(({data}) => {
          this.$emit('success', data);
        }).catch(({response}) => {
          this.$buefy.toast.open({
            type: 'is-danger',
            message: response.data.message,
          });
        }).finally(() => this.isLoading = false);
      },
    },
  };
</script>

<style scoped>

</style>
