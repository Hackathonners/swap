<template>
    <div>
        <v-select :value.sync="value" :options="options" label="_toString" :on-change="onChange" placeholder="Select an enrollment"></v-select>
        <input :name="name" type="hidden" :value="id">
    </div>
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
        onChange(val) {
            console.log(val);
            this.id = val.id;
        },
    },
    components: { VSelect }
}
</script>
