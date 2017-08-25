<template>
    <div>
        <b-form-file
        v-model="file"
        name="enrollments"
        :required="true"
        :state="state"
        :accept="fileTypes"
        choose-label="Browse files..."
        ></b-form-file>
    </div>
</template>

<script>
export default {
    props: {
        fileTypes: {
            type: String,
            required: true
        },
        state: {
            type: String
        }
    },

    data() {
        return {
            file: null
        }
    }
}
</script>

<style>
.custom-file-control:lang(en):empty::after {
    content: attr(data-selected);
}
</style>
