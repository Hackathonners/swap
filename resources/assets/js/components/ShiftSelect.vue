<template>
  <div>
    <v-select
        :value.sync="value"
        :options="options"
        label="_toString"
        :reduce="shift => shift.tag"
        @input="onChange"
        placeholder="Select a shift"
    >
    </v-select>
    <input :name="name" type="hidden" :value="tag">
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
      tag: '',
    };
  },
  methods: {
    onChange(tag) {
      this.tag = tag;
    },
  },
  components: { VSelect }
}
</script>
