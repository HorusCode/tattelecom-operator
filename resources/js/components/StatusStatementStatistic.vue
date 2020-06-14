<template>
    <doughnut-chart :chart-data="data" :options="{responsive: true, maintainAspectRatio: true}"/>
</template>

<script>
  import DoughnutChart from '../charts/DoughnutChart';

  export default {
    name: 'StatusStatementStatistic',
    components: {DoughnutChart},
    data() {
      return {
        data: {},
      };
    },
    mounted() {
      this.loadData();
    },
    methods: {
      loadData: function() {
        axios.get('/api/statistics', {
          params: {
            data: 'statements_status',
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
