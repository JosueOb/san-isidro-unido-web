<template>
  <div id="piechart">
    <GChart
      :settings="{packages: ['corechart']}"
      type="PieChart"
      :data="chartData"
      :options="chartOptions"
      :resizeDebounce="500"
    />
  </div>
</template>
<script>
import { GChart } from "vue-google-charts";
export default {
  components: {
    GChart
  },
  data() {
    return {
      social_problems: [],
      // Array will be automatically processed with visualization.arrayToDataTable function
      chartDataHeader: ["Problemas sociales", "Social Problems per Category"],
      updatedChartData: [],
      chartOptions: {
        title: "Problemas sociales reportados por categorías",
        subtitle: "Problemas sociales reportados por los moradores del barrio",
        height: 450
      }
    };
  },
  mounted() {
    axios.get("/api/socialProblems").then(response => {
      //se convierte el objeto JSON a un array
      var obj = response.data;
      var social_problems_array = Object.keys(obj).map(function(key) {
        return [key, obj[key]];
      });

      this.social_problems = social_problems_array;
      // console.log(social_problems_array);
    });
  },
  computed: {
    chartData() {
      return [this.chartDataHeader, ...this.social_problems];
    }
  }
};
</script>