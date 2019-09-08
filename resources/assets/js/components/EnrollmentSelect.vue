<template>
    <div>
        <v-select
            :value.sync="value"
            :options="options"
            label="_toString"
            :reduce="enrollment => enrollment.id"
            @input="onChange"
            placeholder="Select an enrollment"
        >
        </v-select>
        <input :name="name" type="hidden" :value="id">
    </div>
</template>

<script>
import VSelect from "vue-select";

export default {
    props: {
        name: {
            type: String,
            required: true,
        },
        value: {
            default: null,
        },
        options: {
            type: Array,
            default() { return [] },
        },
    },
    data() {
        return {
            id: '',
        };
    },
    methods: {
        onChange(id) {
            this.id = id;
        },
    },
    components: { VSelect }
}
</script>
