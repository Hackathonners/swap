<template>
    <b-modal ref="modal" :title="`Delete exchange request on ${this.course}`" size="lg">
        Are you sure to delete the exchange request to <strong>{{ this.toShift }}</strong> on <strong>{{ this.course }}</strong>?
        <!-- <br>
        This exchange was proposed to <strong>{{ this.student.name }} ({{ this.student.number }})</strong>. -->
        <form slot="modal-footer" :action="`/exchanges/${ this.id }`" method="post">
            <csrf-field></csrf-field>
            <input type="hidden" name="_method" value="DELETE">
            <button type="button" name="button" class="btn btn-outline-secondary" @click="close">Close</button>
            <button type="submit" name="button" class="btn btn-danger">Delete requested exchange</button>
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
        eventBus.$on('app:exchange::delete', (data) => {
            this.id = data.id;
            this.student.name = data.to_enrollment.student.user.name;
            this.student.number = data.to_enrollment.student.student_number;
            this.fromShift = data.from_enrollment.shift.tag;
            this.toShift = data.to_enrollment.shift.tag;
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
