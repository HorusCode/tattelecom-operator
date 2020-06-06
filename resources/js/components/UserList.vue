<template>
    <article class="media">
        <figure class="media-left">
            <span class="avatar">
                <span class="mdi mdi-account-circle-outline"></span>
            </span>
        </figure>
        <div class="media-content">
            <div class="content">
                <header class="heading">
                    <h4>{{ getFullName(data.client) }}</h4>
                    <h5 class="has-text-weight-normal">Почта: {{ data.client.email }}</h5>
                </header>
                <div class="content-body">
                    <div class="mb-3">
                        <h6>Персональные данные:</h6>
                        <ul class="list m-0">
                            <li class="list-item">
                                Адрес: <strong>{{data.client.address}}</strong>
                            </li>
                            <li class="list-item">
                                Паспорт: <strong>{{data.client.passport_number}}
                                {{data.client.passport_series}}</strong>
                            </li>
                            <li class="list-item">
                                Телефон: <strong>{{data.client.phone}}</strong>
                            </li>
                            <li class="list-item">
                                Что случилось:
                                <p class="has-text-weight-bold" v-if="data.hasOwnProperty('statement')">{{data.statement.problem}}</p>
                               <p class="has-text-weight-bold" v-else>{{data.problem}}</p>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6>Услуги:</h6>
                        <ul class="list m-0" v-if="data.client.services.length > 0">
                            <li class="list-item" v-for="service in data.client.services" :key="'service-'+service.id">
                                {{ service.title }}
                            </li>
                        </ul>
                        <div v-else class="box">
                            Нет услуг
                        </div>
                    </div>

                    <div v-show="data.hasOwnProperty('service_user')" class="mb-3">
                        <h6>Назначены на работу:</h6>
                        <ul class="list m-0">
                            <li class="list-item d-flex justify-content-between align-items-center" v-for="service in data.service_user">
                                <div>
                                    <span class="is-block">{{getFullName(service)}}</span>
                                    <span class="is-block">Телефон: <strong>{{service.phone}}</strong></span>
                                </div>
                                <div>
                                    <span v-if="service.status < 2" class="tag" :class="service.status ? 'is-warning' : 'is-danger'">{{  service.status ? 'В процессе' : 'Простаивает' }}</span>
                                    <span v-else class="tag is-success">Завершён</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </article>
</template>

<script>
  import {fullName} from '../mixins';
  import EmptyData from './EmptyData';
  export default {
    name: 'UserList',
    components: {EmptyData},
    props: ['data'],
    mixins: [fullName],
  };
</script>

<style scoped>
    .heading {
        text-transform: none;
    }
</style>
