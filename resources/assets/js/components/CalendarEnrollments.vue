<template>
    <div class="form-calendar">
        <div class="block datepicker">
            <el-date-picker
            v-model="dates"
            type="datetimerange"
            range-separator=" to "
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
import moment from 'moment';

export default {
    props: {
        date: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            dates: [],
            enrollmentsStart: '',
            enrollmentsEnd: '',
            minDate: '',
            placeholder: "Select date and time range for enrollments period"
        }
    },
    mounted() {
        this.dates = [
            moment.utc(this.date[0]).format(),
            moment.utc(this.date[1]).format()
        ];
        this.formatDates();
    },
    methods: {
        disabledDate (date) {
            return date < this.minDate;
        },
        emit(dates) {
            this.formatDates();
        },
        formatDates() {
            this.enrollmentsStart = this.dates[0] ? moment.utc(this.dates[0]).format('YYYY-MM-DD HH:mm:ss') : null;
            this.enrollmentsEnd = this.dates[1] ? moment.utc(this.dates[1]).format('YYYY-MM-DD HH:mm:ss') : null;
            eventBus.$emit('set-exchanges-end', this.dates[1]);
        }
    }
}
</script>
