<template>
    <section>
        <b-field position="is-right">
            <b-input placeholder="Поиск..."
                     type="search"
                     icon="magnify"
                     v-model="searchWord"
            >
            </b-input>
        </b-field>
        <b-table
                :data="filtered"
                paginated
                per-page="10"
                detailed
                detail-key="id"
                default-sort-direction="desc"
                :sort-icon="sortIcon"
                show-detail-icon
                :sort-icon-size="sortIconSize"
                default-sort="client.private_face"
                aria-next-label="Next page"
                aria-previous-label="Previous page"
                aria-page-label="Page"
                hoverable
                aria-current-label="Current page"
        >

            <template slot-scope="props">
                <b-table-column class="is-middle" field="work_status" label="Статус" width="40" sortable numeric>
                    <span class="tag" :class="props.row.work_status ? 'is-success' : 'is-danger'">
                        {{  props.row.work_status ? 'В процессе' : 'Простаивает'}}
                    </span>
                </b-table-column>

                <b-table-column class="is-middle" field="id" label="#" width="40" sortable numeric>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_address" label="Адрес" sortable width="200">
                    {{ props.row.client.address }}
                </b-table-column>

                <b-table-column class="is-middle" field="client_private_face" label="Юр. лицо" sortable centered>
                    <span class="tag" :class="props.row.client.private_face ? 'is-primary' : 'is-warning'">
                        {{  props.row.client.private_face ? 'Да' : 'Нет'}}
                    </span>

                </b-table-column>

                <b-table-column class="is-middle" field="created_at" label="Дата составления" sortable centered>
                    <span class="tag is-success">
                        {{ moment(props.row.updated_at).format('DD.MM.YYYY HH:mm') }}
                    </span>
                </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                <user-list :data="props"/>
            </template>
            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon
                                    icon="emoticon-sad"
                                    size="is-large">
                            </b-icon>
                        </p>
                        <p>Данных нет.</p>
                    </div>
                </section>
            </template>
        </b-table>
    </section>
</template>

<script>
  import moment from 'moment';
  import UserList from './UserList';
  import {search} from '../mixins';
  export default {
    components: {UserList},
    props: ['data'],
    mixins: [search],
    data() {
      return {
        json: JSON.parse(this.data),
        defaultSortDirection: 'asc',
        sortIcon: 'arrow-up',
        sortIconSize: 'is-small',
        searchWord: '',
        moment: moment,
      };
    },

  };
</script>
