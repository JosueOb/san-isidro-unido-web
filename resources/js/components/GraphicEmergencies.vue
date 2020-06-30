<template>
    <div
      id="calendar"
      style="overflow-x:scroll; overflow-y:hidden; width: 100%;">
      <GChart
        :settings="{packages: ['calendar']}"
        type="Calendar"
        :data="chartData"
        :options="chartOptions"
        :resizeDebounce="0"
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
      emergencies: [],
      // Array will be automatically processed with visualization.arrayToDataTable function
      chartDataHeader: ["Year", "Emergencies"],
      updatedChartData: [],
      chartOptions: {
        title: "Emergencias reportadas",
        subtitle: "Reporte de emergencias realizados por días",
        // calendar: { cellSize: 15 },
        height: 450
      }
    };
  },
  mounted() {
    axios.get("/api/emergencies").then(response => {
      //se convierte el objeto JSON a un array
      var obj = response.data;
      var emergencies_array = Object.keys(obj).map(function(key) {
        var date = new Date(key);
        date.setDate(date.getDate() + 1);
        return [date, obj[key]];
      });

      this.emergencies = emergencies_array;
    });
  },
  computed: {
    chartData() {
      return [this.chartDataHeader, ...this.emergencies];
    }
  }
};
</script>