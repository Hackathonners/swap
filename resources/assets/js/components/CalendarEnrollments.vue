<template>
    <div class="form-calendar">
        <div class="block datepicker">
            <el-date-picker
                v-model="dates"
                type="datetimerange"
                range-separator=" to "
                start-placeholder="Start date"
                end-placeholder="End date"
                @change="formatDates"
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
            maxDate: '',
            placeholder: "Select date and time range for enrollments period"
        }
    },
    mounted() {
        this.dates = [
            this.date[0] ? moment.utc(this.date[0]).format() : undefined,
            this.date[1] ? moment.utc(this.date[1]).format() : undefined
        ];
        this.formatDates();
    },
    methods: {
        disabledDate (date) {
            return this.maxDate ? date > this.maxDate : false;
        },
        formatDates() {
            this.enrollmentsStart = this.dates[0] ? moment.utc(this.dates[0]).format('YYYY-MM-DD HH:mm:ss') : undefined;
            this.enrollmentsEnd = this.dates[1] ? moment.utc(this.dates[1]).format('YYYY-MM-DD HH:mm:ss') : undefined;
            eventBus.$emit('set-enrollments-start', this.dates[0]);
        }
    }
}
</script>
