<template>
    <section>
        <b-field label="Клиент">
            <b-autocomplete
                    :data="clients"
                    placeholder="ФИО клиента"
                    field="firstname"
                    :loading="isFetching"
                    @typing="getAsyncData"
                    expanded
                    @select="option => selected = option.id"
            >
                <template slot-scope="props">
                    <div class="media">
                        <div class="media-content">
                            {{ getFullName(props.option) }}
                        </div>
                    </div>
                </template>
                <template slot="empty">Нет результатов</template>
            </b-autocomplete>
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

  export default {
    name: 'FormStatement',
    mixins: [fullName],
    data() {
      return {
        isFetching: false,
        clients: [],
        problem: '',
        selected: '',
      };
    },
    methods: {
      getAsyncData: debounce(function(text) {
        if (text.length < 3) {
          this.clients = [];
          return;
        }
        this.isFetching = true;
        axios.get(`/api/users/search/client?text=${text}`).then(({data}) => {
          this.clients = [];
          data.forEach((res) => this.clients.push(res));
        }).catch((error) => {
          this.clients = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
      save: function() {
        axios.post(`/api/statement`, {
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
          }, 300, 'later');
        });
      },
    },

  };
</script>

<style scoped>

</style>
