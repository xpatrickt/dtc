<template>
  <div>
    <canvas :id="chartID" :key="keyChart"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js'


export default {
  name: 'PlanetChart',
  props:{
    dataSet:{
      type: Object,
      require: true,
    },
    keyChart: {
      type: Number,
      require: true,
    }
  },
  data() {
    return {
      data: {

      },
      chartID: null,
    }
  },
  beforeMount(){
    this.data =  this.dataSet;
    this.chartID = 'chart' + this.keyChart;
  },
  mounted() {
    const ctx = document.getElementById(this.chartID);
    new Chart(ctx, 
      this.data
    );
  }
}
</script>