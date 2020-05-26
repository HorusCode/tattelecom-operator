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
        <b-modal has-modal-card :active.sync="showModal">
            <problem-modal></problem-modal>
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
                            <b-tooltip position="is-left" label="Добавить неисправность к услуге" multilined type="is-primary">
                                <a role="button" @click="showModal = true" class="button btn-square is-small text-primary">
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
                            <li class="list-item d-flex justify-content-between" v-for="item in props.row.problems" :key="item.name">
                                <span> {{ item.name }}</span>
                                <div>
                                    <b-tooltip label="Удалить" type="is-danger">
                                        <a role="button" @click="confirmDelete" class="button btn-square is-small is-danger">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </a>
                                    </b-tooltip>
                                    <b-tooltip label="Переименовать" type="is-info">
                                        <a role="button" class="button btn-square is-small is-info">
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
      };
    },
    mounted() {
      this.getData();
    },
    methods: {
      getData: function() {
        this.loading = true;
        axios.get('/api/problems').then(({data}) => {
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
      confirmDelete() {
        this.$buefy.dialog.confirm({
          title: 'Удалить неисправность',
          message: 'Вы действительно хотите <b>удалить</b> эту неисправность у данной услуги?',
          confirmText: 'Удалить',
          cancelText: 'Нет',
          type: 'is-danger',
          hasIcon: true
        });
      },
    },

  };
</script>

<style scoped>

</style>
