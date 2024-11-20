<template>
    <div class="schedule-calendar">
      <div class="grid grid-cols-8 gap-1">
        <!-- Headers -->
        <div class="text-center font-semibold p-2 bg-gray-100">Time</div>
        <div v-for="day in days" :key="day" class="text-center font-semibold p-2 bg-gray-100">
          {{ day }}
        </div>
  
        <!-- Time slots -->
        <template v-for="hour in 24" :key="hour">
          <div class="text-center p-2 border">
            {{ formatHour(hour - 1) }}
          </div>
          <div
            v-for="(day, index) in days"
            :key="`${hour}-${day}`"
            class="text-center p-2 border cursor-pointer hover:bg-gray-50"
            :class="{ 'bg-blue-50': hasSchedule(index, hour - 1) }"
            @click="toggleTimeSlot(index, hour - 1)"
          >
            <template v-if="hasSchedule(index, hour - 1)">
              <svg class="w-5 h-5 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </template>
          </div>
        </template>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        schedules: [],
        loading: false
      }
    },
    methods: {
      formatHour(hour) {
        return `${String(hour).padStart(2, '0')}:00`;
      },
      hasSchedule(dayIndex, hour) {
        return this.schedules.some(
          schedule => schedule.day_of_week === dayIndex && 
                     parseInt(schedule.time.split(':')[0]) === hour
        );
      },
      async toggleTimeSlot(dayIndex, hour) {
        try {
          const existingSchedule = this.schedules.find(
            s => s.day_of_week === dayIndex && 
                parseInt(s.time.split(':')[0]) === hour
          );
  
          if (existingSchedule) {
            await axios.delete(`/schedules/${existingSchedule.id}`);
            this.schedules = this.schedules.filter(s => s.id !== existingSchedule.id);
          } else {
            const response = await axios.post('/schedules', {
              day_of_week: dayIndex,
              time: `${String(hour).padStart(2, '0')}:00`
            });
            this.schedules.push(response.data);
          }
        } catch (error) {
          console.error('Error updating schedule:', error);
        }
      },
      async loadSchedules() {
        this.loading = true;
        try {
          const response = await axios.get('/schedules');
          this.schedules = response.data;
        } catch (error) {
          console.error('Error loading schedules:', error);
        } finally {
          this.loading = false;
        }
      }
    },
    mounted() {
      this.loadSchedules();
    }
  }
  </script>