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
                    <h4>{{data.row.client.lastname}} {{data.row.client.firstname}}
                        {{data.row.client.patronymic}}</h4>
                    <h5 class="has-text-weight-normal">Почта: {{ data.row.client.email }}</h5>
                </header>
                <div class="content-body">
                    <div class="mb-3">
                        <h6>Персональные данные:</h6>
                        <ul class="list m-0">
                            <li class="list-item">
                                Адрес: <strong>{{data.row.client.address}}</strong>
                            </li>
                            <li class="list-item">
                                Паспорт: <strong>{{data.row.client.passport_number}}
                                {{data.row.client.passport_series}}</strong>
                            </li>
                            <li class="list-item">
                                Телефон: <strong>{{data.row.client.phone}}</strong>
                            </li>
                            <li class="list-item">
                                Что случилось: <p class="has-text-weight-bold">{{data.row.problem}}</p>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6>Услуги:</h6>
                        <ul class="list m-0" v-if="data.row.client.services.length > 0">
                            <li class="list-item" v-for="service in data.row.client.services" :key="'service-'+service.id">
                                {{ service.name }}
                            </li>
                        </ul>
                        <div v-else class="box">
                            Нет услуг
                        </div>
                    </div>

                    <div v-show="data.row.hasOwnProperty('service')" class="mb-3">
                        <h6>Назначены на работу:</h6>
                        <ul class="list m-0">
                            <li class="list-item" v-for="service in data.row.service">
                                <span class="is-block">ФИО: <strong>{{ getFullName(service) }}</strong></span>
                                <span class="is-block">Телефон: <strong>{{ service.phone }}</strong></span>
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
