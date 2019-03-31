<template>
    <b-modal @ok="submit" id="multiSetVoiceModal" :title="$t('Set voice for {count} users', {count: users.length})">
        <form-group
                v-model="voice_id"
                name="voice_id"
                type="select"
                :label="$t('Voice')"
                :options="voices"
        ></form-group>
    </b-modal>
</template>

<script>
    import FormGroup from "../FormGroup";
    import {mapState} from 'vuex';

    export default {
        name: 'multi-set-voice-modal',
        components: {FormGroup},
        props: {
            type: {
                type: String,
                default: ''
            }
        },
        computed: {
            ...mapState({
                users: state => state.users.selected,
                voices: state => state.voices.options
            }),
            resource() {
                return this.type ? this.$store.state[`${this.type}s`][this.type] : null;
            }
        },
        data () {
            return {
                voice_id: null
            };
        },
        mounted () {
            this.$store.dispatch('voices/options');
        },
        methods: {
            submit () {
                if (this.type) {
                    this.$store.dispatch(`${this.type}s/setVoice`, {
                        id: this.resource.id,
                        userIds: this.users,
                        voiceId: this.voice_id
                    })
                } else {
                    this.$store.dispatch('users/setVoice', {users: this.users, voice_id: this.voice_id});
                }

                this.$store.commit('users/DESELECT');
            }
        }
    }
</script>

<style scoped>

</style>