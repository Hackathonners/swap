<template>
    <b-modal ref="modal" :title="`Decline exchange on ${ this.course }`" size="lg">
        Are you sure to decline the exchange proposed by <strong>{{ this.student.name }} ({{ this.student.number }})</strong>?
        <br>
        You will keep enrolled in <strong>{{ this.fromShift }}</strong> on <strong>{{ this.course }}</strong>.

        <form slot="modal-footer" :action="`/exchanges/${ this.id }/decline`" method="post">
            <csrf-field></csrf-field>
            <button type="button" name="button" class="btn btn-outline-secondary" @click="close">Close</button>
            <button type="submit" name="button" class="btn btn-danger">Decline exchange</button>
        </form>
    </b-modal>
</template>

<script>
import { eventBus } from '../../app.js';

export default {
    data() {
        return {
            id: null,
            student: {
                name: null,
                number: null,
            },
            fromShift: null,
            toShift: null,
            course: null,
        }
    },

    mounted() {
        eventBus.$on('app:exchange::decline', (data) => {
            this.id = data.id;
            this.student.name = data.from_enrollment.student.user.name;
            this.student.number = data.from_enrollment.student.student_number;
            this.fromShift = data.to_enrollment.shift.tag;
            this.toShift = data.from_enrollment.shift.tag;
            this.course = data.from_enrollment.course.name;
            this.$refs.modal.show();
        })
    },

    methods: {
        close() {
            this.$refs.modal.hide();
        }
    }
}
</script>
