<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Добавление неисправности</p>
        </header>
        <section class="modal-card-body">
            Выбранные неисрпавности:
            <b-taglist>
                <b-tag v-for="problem in selectedProblems" type="is-info" :key="problem.id">
                    {{ problem.name  }}
                </b-tag>
            </b-taglist>
            <b-field v-for="(row, index) in rows" :key="index" :label="index + 1 + '. Неисправность'">
                <b-field :type="{'is-danger': !row.selected}">
                    <b-autocomplete
                            :data="searchData"
                            placeholder="Неисправность"
                            field="name"
                            ref="autocomplete"
                            :loading="isFetching"
                            @typing="getAsyncData"
                            v-model="row.name"
                            expanded
                            @select="option => selectProblem(index, option)"
                    >
                        <template slot="header">
                            <a @click="showAddNewProblem(row.name, index)">
                                <span> Новая неисправность... </span>
                            </a>
                        </template>
                        <template slot-scope="props">
                            <div class="media">
                                <div class="media-content">
                                    {{ props.option.name }}
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
            <b-button type="is-primary" class="is-center" @click="addRow">Добавить неисправность</b-button>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success" :disabled="sendBtn" @click="$emit('send', selectedProblems)">Добавить</button>
            <button class="button" @click="$parent.close()">Закрыть</button>
        </footer>
    </div>

</template>

<script>
  import {debounce} from 'lodash';

  export default {
    name: 'ProblemModal',
    data() {
      return {
        isFetching: false,
        searchData: [],
        selectedProblems: [],
        newProblem: '',
        rows: [
          {
            id: undefined,
            name: '',
            selected: false
          },
        ],
      };
    },
    computed: {
      sendBtn: function() {
        let status = false;
        if (this.rows.length !== this.selectedProblems.length || this.selectedProblems.length === 0) {
          status = true;
        }
        return status;
      },
    },
    methods: {
      addRow: function() {
        this.rows.push({
          id: null,
          name: ''
        });
      },
      removeRow: function(index) {
        this.selectedProblems = this.selectedProblems.filter(item => {
             return item.id !== this.rows[index].id;
        });
        this.rows.splice(index, 1);
      },
      selectProblem(index, array) {
        if (array) {
          this.selectedProblems.push(array);
          this.rows[index].id = array.id;
          this.rows[index].selected = true;
          this.selectedProblems = _.uniqBy(this.selectedProblems, 'id');
        }
        if(this.sendBtn) {
          this.$buefy.notification.open({
            duration: 5000,
            message: `Количество полей больше выбранных неисправностей.
                      <br>Возможно имеются дубликаты или пустые поля`,
            position: 'is-top-left',
            type: 'is-warning',
            hasIcon: true
          });
        }
      },
      getAsyncData: debounce(function(text) {
        if (text.length < 3) {
          this.searchData = [];
          return;
        }
        this.isFetching = true;
        axios.get(`/api/problems/search?text=${text}`).then(({data}) => {
          this.searchData = data.data;
        }).catch((error) => {
          this.searchData = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
      showAddNewProblem: function(inputval, inputIndex) {
        this.$buefy.dialog.prompt({
          message: `Новая неисправность`,
          inputAttrs: {
            placeholder: 'Неисправность',
            value: inputval,
          },
          confirmText: 'Добавить',
          cancelText: 'Выход',
          onConfirm: (value) => {
            axios.post('api/problems', {
              name: value,
            }).then(({data}) => {
              this.$buefy.toast.open({
                message: data.message,
                type: data.status ? 'is-success' : 'is-danger',
              });
              this.searchData.push(data.data);
              this.$refs.autocomplete[inputIndex].setSelected(data.data);
              this.selectProblem(inputIndex, data.data);
            }).catch(({response}) => {
              this.$buefy.toast.open({
                message: response.data.message,
                type: 'is-danger',
              });
            });
          },
        });
      },
    },
  };
</script>

<style scoped>

</style>
