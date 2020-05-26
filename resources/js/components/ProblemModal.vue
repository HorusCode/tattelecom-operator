<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Добавление неисправности</p>
        </header>
        <section class="modal-card-body" style="overflow: visible">
            <b-field label="Клиент">
                <b-autocomplete
                        :data="searchData"
                        placeholder="Неисправность"
                        field="name"
                        :loading="isFetching"
                        @typing="getAsyncData"
                        v-model="newProblem"
                        expanded
                        @select="option => selected = option.id"
                >
                    <template slot="header">
                        <a @click="showAddNewProblem">
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
            </b-field>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success">Save changes</button>
            <button class="button">Cancel</button>
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
        selected: undefined,
        newProblem: ''
      };
    },
    methods: {
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
      showAddNewProblem: function() {
        this.$buefy.dialog.prompt({
          message: `Новая неисправность`,
          inputAttrs: {
            placeholder: 'Неисправность',
            value: this.newProblem
          },
          confirmText: 'Добавлено',
          onConfirm: (value) => {
            console.log(value);
            /*this.$refs.autocomplete.setSelected(value)*/
          }
        })
      }
    },
  };
</script>

<style scoped>

</style>
