import { ref } from 'vue';

export function useCalendar() {
  const isAD = ref(true);

  const toggleCalendar = () => {
    isAD.value = !isAD.value;
  };

  const toBS = (date) => {
    // Placeholder for actual BS conversion logic
    return date; 
  };

  const toAD = (bsDate) => {
    // Placeholder for actual AD conversion logic
    return bsDate;
  };

  return {
    isAD,
    toggleCalendar,
    toBS,
    toAD
  };
}
