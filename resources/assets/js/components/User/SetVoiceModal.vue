<template>
    <b-modal @ok="submit" id="setVoiceModal" :title="$t('Set voice')">
        <form-group
                v-model="voiceId"
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
    import {mapState} from 'vuex';

    export default {
        name: 'set-voice-modal',
        components: {FormGroup},
        props: {
            type: {
                type: String,
                default: ''
            }
        },
        computed: {
            ...mapState({
                user: state => state.users.user,
                voices: state => state.voices.options
            }),
            resource() {
                return this.type ? this.$store.state[`${this.type}s`][this.type] : null;
            },
            voiceId: {
                get() {
                    return this.type && this.user.voice ? this.user.voice.id : this.user.voice_id;
                },
                set(voiceId) {
                    let path = this.type && this.user.voice ? 'user.voice.id' : 'user.voice_id';

                    this.$store.commit('users/updateField', {path: path, value: voiceId});
                }
            }
        },
        mounted () {
            this.$store.dispatch('voices/options');
        },
        methods: {
            submit () {
                if (this.type) {
                    this.$store.dispatch(`${this.type}s/setVoice`, {
                        id: this.resource.id,
                        userIds: [this.user.id],
                        voiceId: this.voiceId
                    })
                } else {
                    this.$store.dispatch('users/edit', this.user);
                }

                this.$store.commit('users/DESELECT');
            }
        }
    }
</script>

<style scoped>

</style>