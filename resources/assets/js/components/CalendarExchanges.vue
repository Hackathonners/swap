<template>
    <div class="form-calendar">
        <div class="block datepicker">
            <el-date-picker
            v-model="date"
            type="datetimerange"
            @change="formatDates"
            :disabled="disabled"
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
import dateFormat from 'dateformat';

export default {
    created() {
        eventBus.$on('set-exchanges-end', value => {
            if(value === undefined) {
                this.disabled = true;
                this.minDate = '';
            } else {
                this.disabled = false;
                this.minDate = new Date(value)
                this.minDate.setTime(this.minDate.getTime() + 3600 * 1000 * 24);
            }
        });
    },
    data() {
        return {
            date: [],
            error: false,
            disabled: true,
            exchangesStart: '',
            exchangesEnd: '',
            minDate: '',
            placeholder: "Select date and time range for exchanges period",
        }
    },
    methods: {
        disabledDate(date) {
            return date < this.minDate;
        },
        formatDates() {
            if(this.date !== undefined) {
                this.exchangesStart = dateFormat(this.date[0], "yyyy-mm-dd hh:MM:ss");
                this.exchangesEnd = dateFormat(this.date[1], "yyyy-mm-dd hh:MM:ss");
            } else {
                this.exchangesStart = '';
                this.exchangesEnd = '';
            }
        }
    }
}
</script>
