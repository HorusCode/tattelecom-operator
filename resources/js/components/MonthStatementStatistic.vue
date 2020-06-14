<template>
    <section>
        <b-field>
            <p class="control">
                <button class="button" @click="type = 'day', loadData()">
                    День
                </button>
            </p>
            <p class="control">
                <button class="button" @click="type = 'month', loadData()">
                   Месяц
                </button>
            </p>
            <p class="control">
                <button class="button" @click="type = 'year', loadData()">
                    Год
                </button>
            </p>
        </b-field>
        <bar-chart :chart-data="data" :options="{responsive: true, maintainAspectRatio: true}"/>
    </section>
</template>

<script>


  import BarChart from '../charts/BarChart';

  export default {
    name: 'MonthStatementStatistic',
    components: {BarChart},
    data() {
      return {
        data: {},
        type: 'month'
      };
    },
    mounted() {
      this.loadData();
    },
    methods: {
      loadData: function() {
        axios.get('/api/statistics', {
          params: {
            data: 'statements',
            type: this.type,
          },
        }).then(({data}) => {
          this.data = data;
        }).catch(({response}) => {
          console.log(response);
        });
      },
    },
  };
</script>

<style scoped>

</style>
