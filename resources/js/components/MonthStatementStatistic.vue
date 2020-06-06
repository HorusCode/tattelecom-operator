<template>
    <bar-chart :chart-data="data" :options="{responsive: true}"/>
</template>

<script>


  import BarChart from '../charts/BarChart';
  export default {
    name: 'MonthStatementStatistic',
    components: {BarChart},
    data() {
      return {
        data: []
      };
    },
    mounted() {
        this.listenData();
    },
    methods: {
      listenData: function() {

      }
    },
  };
</script>

<style scoped>

</style>
