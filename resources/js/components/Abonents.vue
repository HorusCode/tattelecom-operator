<template>
    <section>
        <b-modal has-modal-card :can-cancel="false" :active.sync="showModal">
            <service-modal @send="sendData"/>
        </b-modal>
        <b-field position="is-right">
            <b-input placeholder="Поиск.."
                     type="search"
                     icon="magnify"
                     v-model="searchWord"
            >
            </b-input>
        </b-field>
        <div class="columns is-multiline">
            <div class="column is-4" v-for="(client, index) in paginated">
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            {{ getFullName(client) }}
                        </p>
                        <span class="card-header-icon">
                            <b-tooltip label="Добавить услугу"
                                       type="is-dark"
                                       position="is-top">
                                <i class="mdi mdi-plus" @click="showModal = true, selectAbonent(index, client.id)"/>

                            </b-tooltip>
                        </span>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <dl>
                                <dt>Адрес:</dt>
                                <dd>{{client.address}}</dd>
                                <dt>Почта:</dt>
                                <dd>{{client.email}}</dd>
                                <dt>Телефон:</dt>
                                <dd>{{client.phone}}</dd>
                                <dt>Серия-номер паспорта:</dt>
                                <dd>{{client.passport_series}}-{{client.passport_number}}</dd>
                                <dt>Частное лицо:</dt>
                                <dd>{{client.private_face ? 'Да' : 'Нет'}}</dd>
                                <dt>Зарегистрирован:</dt>
                                <dd>{{ moment.utc(client.created_at).local().format('DD.MM.YYYY HH:mm')}}</dd>
                            </dl>
                            <div class="mb-3">
                                <h6>Услуги:</h6>
                                <ul class="list m-0" v-if="client.services.length > 0">
                                    <li class="list-item d-flex justify-content-between align-items-center"
                                        v-for="(service, i) in client.services"
                                        :key="'service-'+service.id">
                                        <span>{{ service.title }}</span>
                                        <b-tooltip label="Удалить"
                                                   position="is-top"
                                                   type="is-danger"
                                        >
                                            <a role="button"
                                               @click="confirmDelete(), selectAbonent(index, client.id), selectService(i, service.id)"
                                               class="button btn-square is-small is-danger">
                                                <i class="mdi mdi-delete"/>
                                            </a>
                                        </b-tooltip>
                                    </li>
                                </ul>
                                <div v-else>
                                    Нет услуг
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-pagination
                :total="filtered.length"
                order="is-centered"
                :current.sync="current"
                :per-page="perPage"
                :rounded="true">
        </b-pagination>
    </section>
</template>

<script>
  import {fullName, search} from '../mixins';
  import moment from 'moment';
  import ServiceModal from './ServiceModal';

  export default {
    name: 'Abonents',
    mixins: [fullName, search],
    components: {ServiceModal},
    data() {
      return {
        json: [],
        moment: moment,
        searchWord: '',
        current: 1,
        perPage: 9,
        selectedAbonent: {
          index: undefined,
          id: undefined,
        },
        selectedService: {
          index: undefined,
          id: undefined,
        },
        showModal: false,
      };
    },
    computed: {
      paginated: function() {
        let page_number = this.current - 1;
        return this.filtered.slice(page_number * this.perPage, (page_number + 1) * this.perPage);
      },
    },
    mounted() {
      this.loadData();
    },
    methods: {
      loadData: function() {
        axios.get('/api/abonents').then(({data}) => {
          this.json = data.data;
        });
      },
      confirmDelete: function() {
        this.$buefy.dialog.confirm({
          title: 'Удалить неисправность',
          message: 'Вы действительно хотите <b>удалить</b> эту услугу у данного абонента?',
          confirmText: 'Удалить',
          cancelText: 'Нет',
          type: 'is-warning',
          hasIcon: true,
          onConfirm: () => this.deleteService(),
        });
      },
      selectAbonent(index, id) {
        this.selectedAbonent = {
          id: id,
          index: index,
        };
      },
      selectService(index, id) {
        this.selectedService = {
          id: id,
          index: index,
        };
      },
      deleteService: function() {
        axios.delete(`/api/client-service/${this.selectedAbonent.id}?service_id=${this.selectedService.id}`).
            then(({data}) => {
              this.$buefy.toast.open({
                type: 'is-success',
                message: data.message,
              });
              this.json[this.selectedAbonent.index].services.splice(this.selectedService.index, 1);
            }).
            catch(({response}) => {
              console.log(response);
            });
      },
      sendData: function(arr) {
        const ids = _.map(arr, 'id');
        axios.post('/api/client-service', {
          ids: ids,
          current_id: this.selectedAbonent.id,
        }).then(({data}) => {
          this.$buefy.toast.open({
            type: 'is-success',
            message: data.message,
          });
          this.unionServices(arr);
          this.showModal = false;
        }).catch(({response}) => {
          console.log(response.data);
        });
      },
      unionServices: function(arr) {
        let services = this.json[this.selectedAbonent.index].services;
        services = services.concat(arr);
        this.json[this.selectedAbonent.index].services = _.unionBy(services, 'id');
      },
    },
  };
</script>

<style scoped>

</style>
