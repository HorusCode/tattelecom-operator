<template>
    <div class="modal-card" style="width: auto">
        <header class="modal-card-head">
            <p class="modal-card-title">Назначение сотрудника</p>
        </header>
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-half is-offset-one-quarter">
                    <b-field v-for="(row, index) in rows" :key="index"
                             :label="index + 1 + '. ФИО сотрудника'">
                        <b-field>
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
                                    <div class="media">
                                        <div class="media-content">
                                            {{ props.option.fullname }}
                                        </div>
                                    </div>
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
        selectedUsers: [],
        serviceUsers: [],
        isFetching: false,
        rows: [
          {
            value: null,
          },
        ],
      };
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
          data.forEach((res) => this.serviceUsers.push({
            id: res.id,
            fullname: this.getFullName(res)
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
          this.selectedUsers.push(array.id);
          this.rows[index].value = array.id;
          this.selectedUsers = this.unique(this.selectedUsers);
        }
      },
      unique: function(arr) {
        return Array.from(new Set(arr));
      },
      userServiceIds: function() {
        let ids = this.rows.map(obj => {
          return obj.value;
        });
        return this.unique(ids);
      },
      sendData: function() {
        axios.post(`/api/works`, {
          ids: this.userServiceIds(),
          statement: this.currentId,
        }).then(({data}) => {
          this.$emit('success', data);
        });
      },
    },
  };
</script>

<style scoped>

</style>
