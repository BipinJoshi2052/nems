import { ref, watch } from 'vue';

export function useChartData() {
  const statisticsMode = ref('Monthly'); // Monthly, Quarterly, Annually

  const generateData = (mode) => {
    if (mode === 'Quarterly') {
      return {
        series1: [180, 210, 190, 230],
        series2: [40, 50, 30, 45],
        labels: ['Q1', 'Q2', 'Q3', 'Q4']
      };
    } else if (mode === 'Annually') {
      return {
        series1: [250],
        series2: [60],
        labels: ['2024']
      };
    }
    // Monthly (Default)
    return {
      series1: [165, 175, 160, 190, 185, 210, 220, 230, 215, 240, 245, 250],
      series2: [20, 35, 15, 45, 30, 55, 40, 60, 45, 50, 55, 50],
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    };
  };

  const chartData = ref(generateData(statisticsMode.value));

  watch(statisticsMode, (newMode) => {
    chartData.value = generateData(newMode);
  });

  return {
    statisticsMode,
    chartData
  };
}
