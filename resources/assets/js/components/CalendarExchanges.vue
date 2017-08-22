<template>
    <div class="form-calendar">
        <div class="block datepicker">
            <el-date-picker
            v-model="dates"
            type="datetimerange"
            range-separator=" to "
            @change="formatDates"
            :placeholder="placeholder"
            :editable="false"
            :clearable='true'
            :picker-options="{disabledDate}"
            >
        </el-date-picker>
    </div>
    <input name="exchanges_start_at" type="hidden" :value="exchangesStart">
    <input name="exchanges_end_at" type="hidden" :value="exchangesEnd">
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
            exchangesStart: '',
            exchangesEnd: '',
            minDate: '',
            placeholder: "Select date and time range for exchanges period",
        }
    },
    created() {
        this.dates = this.date;
        this.formatDates();
        eventBus.$on('set-exchanges-end', (value) => {
            this.minDate = value ? moment.utc(value).add(1, 'days') : '';
        });
    },
    methods: {
        disabledDate(date) {
            return date < this.minDate;
        },
        formatDates() {
            this.exchangesStart = this.dates[0] ? moment.utc(this.dates[0]).format('YYYY-MM-DD HH:mm:ss') : null;
            this.exchangesEnd = this.dates[1] ? moment.utc(this.dates[1]).format('YYYY-MM-DD HH:mm:ss') : null;
        }
    }
}
</script>
