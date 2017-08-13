<template>
    <div class="form-calendar">
        <div class="block datepicker">
            <el-date-picker
              v-model="date"
              type="datetimerange"
              @change="emit"
              :placeholder="placeholder"
              :editable="false"
              :clearable='true'
              :picker-options="{disabledDate}"
              >
            </el-date-picker>
        </div>
        <input name="enrollments_start_at" type="hidden" :value="enrollmentsStart">
        <input name="enrollments_end_at" type="hidden" :value="enrollmentsEnd">
    </div>
</template>

<script>
import { eventBus } from '../app.js';
import dateFormat from 'dateformat';

    export default {
        data() {
          return {
            date: [],
            enrollmentsStart: '',
            enrollmentsEnd: '',
            minDate: new Date() - 3600 * 1000 * 24, // yesterday
            placeholder: "Select date and time range for enrollments period"
          }
        },
        methods: {
            disabledDate (date) {
                return date < this.minDate;
            },
            emit(dates) {
                eventBus.$emit('set-exchanges-end',dates.split(' - ')[1]);
                this.formatDates();
            },
            formatDates() {
                if(this.date !== undefined) {
                    this.enrollmentsStart = dateFormat(this.date[0], "yyyy-mm-dd hh:MM:ss");
                    this.enrollmentsEnd = dateFormat(this.date[1], "yyyy-mm-dd hh:MM:ss");
                }  else {
                    this.enrollmentsStart = '';
                    this.enrollmentsEnd = '';
                }
            }
        }
    }
</script>
