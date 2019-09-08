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
    mounted() {
        this.dates = [
            this.date[0] ? moment.utc(this.date[0]).format() : undefined,
            this.date[1] ? moment.utc(this.date[1]).format() : undefined
        ];
        this.formatDates();
        eventBus.$on('set-enrollments-start', (value) => {
            this.minDate = value ? moment.utc(value).add(1, 'days') : null;
        });
    },
    methods: {
        disabledDate (date) {
            return this.minDate ? date < this.minDate : false;
        },
        formatDates() {
            this.exchangesStart = this.dates[0] ? moment.utc(this.dates[0]).format('YYYY-MM-DD HH:mm:ss') : undefined;
            this.exchangesEnd = this.dates[1] ? moment.utc(this.dates[1]).format('YYYY-MM-DD HH:mm:ss') : undefined;
        }
    }
}
</script>
