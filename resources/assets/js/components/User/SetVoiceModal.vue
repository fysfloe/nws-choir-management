<template>
    <b-modal @ok="submit" id="setVoiceModal" :title="$t('Set voice')">
        <form-group
                v-model="voice_id"
                name="voice_id"
                type="select"
                :label="$t('Voice')"
                :options="voices"
                validate="required"
        ></form-group>
    </b-modal>
</template>

<script>
    import FormGroup from "../FormGroup";
    import { mapState } from 'vuex';
    import { mapFields } from 'vuex-map-fields';

    export default {
        name: 'set-voice-modal',
        components: {FormGroup},
        computed: {
            ...mapState({
                user: state => state.users.user,
                voices: state => state.voices.options
            }),
            ...mapFields('users', [
                'user.voice_id'
            ])
        },
        mounted () {
            this.$store.dispatch('voices/options');
        },
        methods: {
            submit () {
                this.$store.dispatch('users/edit', this.user);
            }
        }
    }
</script>

<style scoped>

</style>