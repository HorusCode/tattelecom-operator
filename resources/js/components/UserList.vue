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
                    <h6 class="has-text-weight-normal">Почта: {{ data.row.client.email }}</h6>
                </header>
                <div class="content-body">
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
                    <div v-show="data.row.hasOwnProperty('service')" class="my-2">
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
  export default {
    name: 'UserList',
    props: ['data'],
    mixins: [fullName],
  };
</script>

<style scoped>

</style>
