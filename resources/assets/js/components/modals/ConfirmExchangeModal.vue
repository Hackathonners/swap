<template>
    <b-modal ref="modal" :title="`Confirm exchange on ${ this.course }`" size="lg">
        Confirming the exchange proposed by <strong>{{ this.student.name }} ({{ this.student.number }})</strong>, your enrollment in the course <strong>{{ this.course }}</strong> will be updated from shift <strong>{{ this.fromShift }}</strong> to <strong>{{ this.toShift }}</strong>.
        <form slot="modal-footer" :action="`/exchanges/${ this.id }/confirm`" method="post">
            <csrf-field></csrf-field>
            <button type="button" name="button" class="btn btn-outline-secondary" @click="close">Close</button>
            <button type="submit" name="button" class="btn btn-primary">Exchange to {{ this.toShift }}</button>
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
        eventBus.$on('app:exchange::confirm', (data) => {
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
