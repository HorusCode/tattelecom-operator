<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Добавление услуг</p>
        </header>
        <section class="modal-card-body">
            Выбранные услуги:
            <b-taglist>
                <b-tag v-for="service in selectedServices" type="is-info" :key="service.id">
                    {{ service.title  }}
                </b-tag>
            </b-taglist>
            <b-field v-for="(row, index) in rows" :key="index" :label="index + 1 + '. Услуга'">
                <b-field :type="{'is-danger': !row.selected}">
                    <b-autocomplete
                            :data="searchData"
                            placeholder="Услуга"
                            field="title"
                            ref="autocomplete"
                            :loading="isFetching"
                            @typing="getAsyncData"
                            v-model="row.title"
                            expanded
                            @select="option => selectService(index, option)"
                    >
                        <template slot-scope="props">
                            <div class="media">
                                <div class="media-content">
                                    {{ props.option.title }}
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
            <b-button type="is-primary" class="is-center" @click="addRow">Добавить услугу</b-button>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success" :disabled="sendBtn" @click="$emit('send', selectedServices)">Добавить</button>
            <button class="button" @click="$parent.close()">Закрыть</button>
        </footer>
    </div>

</template>

<script>
  import {debounce} from 'lodash';

  export default {
    name: 'ServiceModal',
    data() {
      return {
        isFetching: false,
        searchData: [],
        selectedServices: [],
        newService: '',
        rows: [
          {
            id: undefined,
            title: '',
            selected: false
          },
        ],
      };
    },
    computed: {
      sendBtn: function() {
        let status = false;
        if (this.rows.length !== this.selectedServices.length || this.selectedServices.length === 0) {
          status = true;
        }
        return status;
      },
    },
    methods: {
      addRow: function() {
        this.rows.push({
          id: null,
          title: ''
        });
      },
      removeRow: function(index) {
        this.selectedServices = this.selectedServices.filter(item => {
             return item.id !== this.rows[index].id;
        });
        this.rows.splice(index, 1);
      },
      selectService(index, array) {
        if (array) {
          this.selectedServices.push(array);
          this.rows[index].id = array.id;
          this.rows[index].selected = true;
          this.selectedServices = _.uniqBy(this.selectedServices, 'id');
        }
        if(this.sendBtn) {
          this.$buefy.notification.open({
            duration: 5000,
            message: `Количество полей больше выбранных услуг.
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
        axios.get(`/api/services/search?text=${text}`).then(({data}) => {
          this.searchData = data.data;
        }).catch((error) => {
          this.searchData = [];
          throw error;
        }).finally(() => {
          this.isFetching = false;
        });
      }, 500),
    },
  };
</script>

<style scoped>

</style>
