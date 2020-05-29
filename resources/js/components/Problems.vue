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
        <b-modal has-modal-card :can-cancel="false" :active.sync="showModal">
            <problem-modal @send="sendData"/>
        </b-modal>
        <b-table
                :data="filtered"
                ref="table"
                detailed
                hoverable
                :loading="loading"
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
                    <div class="d-flex justify-content-between">
                        <span>{{ props.row.title }}</span>
                        <div>
                            <b-tooltip position="is-left" label="Добавить неисправность к услуге" multilined
                                       type="is-primary">
                                <a role="button" @click="showModal = true, selectService(props.index, props.row.id)"
                                   class="button btn-square is-small text-primary">
                                    <i class="mdi mdi-plus"></i>
                                </a>
                            </b-tooltip>
                        </div>
                    </div>
                </b-table-column>
            </template>

            <template slot="detail" slot-scope="props">
                <tr class="detail">
                    <td colspan="2">
                        <ul class="list m-0">
                            <li class="list-item d-flex justify-content-between" v-for="(item,i) in props.row.problems"
                                :key="item.name">
                                <span> {{ item.name }}</span>
                                <div>
                                    <b-tooltip label="Удалить" type="is-warning">
                                        <a role="button"
                                           @click="confirmDelete(), selectProblem(i, item.id), selectService(props.index, props.row.id)"
                                           class="button btn-square is-small is-warning">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </a>
                                    </b-tooltip>
                                    <b-tooltip label="Полное удаление" type="is-danger">
                                        <a role="button"
                                           @click="confirmTotalDelete(), selectProblem(i, item.id), selectService(props.index, props.row.id)"
                                           class="button btn-square is-small is-danger">
                                            <i class="mdi mdi-delete-alert-outline"></i>
                                        </a>
                                    </b-tooltip>
                                    <b-tooltip label="Переименовать" type="is-info">
                                        <a role="button" class="button btn-square is-small is-info"
                                           @click="editProblem(item.name), selectProblem(i, item.id)">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                    </b-tooltip>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr>
            </template>
        </b-table>
    </section>
</template>

<script>
  import {search} from '../mixins';
  import ProblemModal from './ProblemModal';

  export default {
    name: 'Problems',
    components: {ProblemModal},
    mixins: [search],
    data() {
      return {
        json: [],
        searchWord: '',
        loading: true,
        showModal: false,
        selectedService: {
          index: undefined,
          id: undefined,
        },
        selectedProblem: {
          index: undefined,
          id: undefined,
        },
      };
    },
    mounted() {
      this.getData();
    },
    methods: {
      sendData: function(arr) {
        const ids = _.map(arr, 'id');
        axios.post('/api/service-problems', {
          ids: ids,
          current_id: this.selectedService.id,
        }).then(({data}) => {
          this.$buefy.toast.open({
            type: 'is-success',
            message: data.message,
          });
          this.unionProblems(arr);
          this.showModal = false;
        }).catch(({response}) => {
          console.log(response.data);
        });
      },
      selectService(index, id) {
        this.selectedService = {
          id: id,
          index: index,
        };
      },
      selectProblem(index, id) {
        this.selectedProblem = {
          index: index,
          id: id,
        };
      },
      getData: function() {
        this.loading = true;
        axios.get('/api/service-problems').then(({data}) => {
          this.json = data.data;
        }).catch(error => {
          console.log(error);
        }).finally(() => {
          this.loading = false;
        });
      },
      detail: function(row) {
        row.detailed = !row.detailed;
        this.$refs.table.toggleDetails(row);
      },
      confirmDelete: function() {
        this.$buefy.dialog.confirm({
          title: 'Удалить неисправность',
          message: 'Вы действительно хотите <b>удалить</b> эту неисправность у данной услуги?',
          confirmText: 'Удалить',
          cancelText: 'Нет',
          type: 'is-warning',
          hasIcon: true,
          onConfirm: () => this.deleteProblem(),
        });
      },
      confirmTotalDelete: function() {
        this.$buefy.dialog.confirm({
          title: 'Полное удаление неисправности',
          message: 'Вы действительно хотите <b>полностью удалить</b> эту неисправность из базы данных?',
          confirmText: 'Удалить',
          cancelText: 'Нет',
          type: 'is-danger',
          hasIcon: true,
          onConfirm: () => this.deleteProblemTotal(),
        });
        this.$buefy.notification.open({
          duration: 5000,
          message: `Внимание! Неисправность удалится у всех услуг`,
          position: 'is-bottom-left',
          type: 'is-danger',
          hasIcon: true,
        });
      },
      deleteProblem: function() {
        axios.delete(`/api/service-problems/${this.selectedService.id}?problem_id=${this.selectedProblem.id}`).
            then(({data}) => {
              this.$buefy.toast.open({
                type: 'is-success',
                message: data.message,
              });
              this.json[this.selectedService.index].problems.splice(this.selectedProblem.index, 1);
              if (this.json[this.selectedService.index].problems.length === 0) {
                this.detail(this.json[this.selectedService.index]);
              }
            }).
            catch(({response}) => {
              console.log(response);
            });
      },
      deleteProblemTotal: function() {
        axios.delete(`/api/problems/${this.selectedProblem.id}`).
            then(({data}) => {
              this.$buefy.toast.open({
                type: 'is-success',
                message: data.message,
              });
              this.json.forEach(row => {
                row.problems = row.problems.filter(item => item.id !== this.selectedProblem.id);
                if (row.problems.length === 0) {
                  this.detail(row);
                }
              });
            }).
            catch(({response}) => {
              console.log(response);
            });
      },
      editProblem: function(text) {
        this.$buefy.dialog.prompt({
          message: `Изменить неисправность: <b>${text}</b>`,
          inputAttrs: {
            type: 'text',
            placeholder: 'Новое значание...',
            value: text,
          },
          confirmText: 'Send',
          trapFocus: true,
          closeOnConfirm: false,
          onConfirm: (value) => {
            this.updateProblem(value);
          },
        });
      },
      unionProblems: function(arr) {
        let problems = this.json[this.selectedService.index].problems;
        problems = problems.concat(arr);
        this.json[this.selectedService.index].problems = _.unionBy(problems, 'id');
      },
      updateProblem: function(text) {
        axios.post(`/api/problems/${this.selectedProblem.id}`, {
          name: text,
          _method: 'PUT'
        }).then(({data}) => {
          this.$buefy.toast.open({
            type: 'is-success',
            message: data.message,
          });

          this.json.forEach(row => {
            let index = row.problems.findIndex(item => item.id === data.data.id);
            if(row.problems[index]) row.problems[index].name = data.data.name;
          });
          this.$nextTick();
        }).catch(({response}) => {
          this.$buefy.toast.open({
            type: 'is-danger',
            message: response.data.message,
          });
        })
      }
    },

  };
</script>

<style scoped>

</style>
