<template>
  <div>
    <canvas :id="'char'" ></canvas>
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
    key:{
      type: Number,
      require: true
    }
  },
  data() {
    return {
      data: {

      }
    }
  },
  beforeMount(){
    this.data =  this.dataSet
  },
  mounted() {
    const ctx = document.getElementById('chart'+key);
    new Chart(ctx, 
      this.data
    );
  }
}
</script>