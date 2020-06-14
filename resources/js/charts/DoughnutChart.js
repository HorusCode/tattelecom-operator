import {Doughnut, mixins} from 'vue-chartjs';

export default {
  extends: Doughnut,
  mixins: [mixins.reactiveProp],
  props: ['options'],
  mounted() {
    this.textCenter();
    this.renderChart(this.chartData, this.options);
  },
  methods: {
    textCenter: function() {
      this.addPlugin({
        id: 'text-center',
        afterDatasetDraw: function(chart) {
          let width = chart.chart.width,
              height = chart.chart.height,
              ctx = chart.chart.ctx;

          ctx.restore();
          let fontSize = (height / 114).toFixed(2);
          ctx.font = fontSize + 'em sans-serif';
          ctx.textBaseline = 'middle';
          let data = chart.config.data.datasets[0].data.reduce((a,b) => a + b);
          let text = data,
              textX = Math.round((width - ctx.measureText(text).width) / 2),
              textY = height / 1.9;

          ctx.fillText(text, textX, textY);
          ctx.save();
        },
      });

    },
  },
};
