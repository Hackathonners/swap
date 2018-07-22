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
        <input name="groups_creation_start_at" type="hidden" :value="groupsStart">
        <input name="groups_creation_end_at" type="hidden" :value="groupsEnd">
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
            groupsStart: '',
            groupsEnd: '',
            minDate: '',
            placeholder: "Select date and time range for group creation period",
        }
    },
    mounted() {
        this.dates = [
            this.date[0] ? moment.utc(this.date[0]).format() : null,
            this.date[1] ? moment.utc(this.date[1]).format() : null
        ];
        this.formatDates();
        eventBus.$on('set-groups-start', (value) => {
            this.minDate = value ? moment.utc(value).add(1, 'days') : null;
        });
    },
    methods: {
        disabledDate (date) {
            return this.minDate ? date < this.minDate : false;
        },
        formatDates() {
            this.groupsStart = this.dates[0] ? moment.utc(this.dates[0]).format('YYYY-MM-DD HH:mm:ss') : null;
            this.groupsEnd = this.dates[1] ? moment.utc(this.dates[1]).format('YYYY-MM-DD HH:mm:ss') : null;
        }
    }
}
</script>
