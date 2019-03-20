<template>
    <input type="hidden" name="_token" :value="csrf">
</template>

<script>
import token from '../bootstrap';

export default {
    data() {
        return {
            csrf: token,
        }
    },
}
</script>
