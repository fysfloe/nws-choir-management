<template>
    <div>
        <b-tooltip v-if="this.deadline && !isActive" :target="`accept-decline-${this._uid}`" placement="top">
            {{ $t('The deadline is over. Please contact christoph@neuewienerstimmen.at if you forgot to answer.') }}
        </b-tooltip>
        <div v-if="!showDot || !this.deadline || isActive" class="btn-group accept-decline"
             :id="`accept-decline-${this._uid}`" role="group" :aria-label="$t('Accept or decline')">
            <button @click="accept" :disabled="this.deadline && !isActive"
                    :class="{'btn-success': hasAccepted, 'btn-default': !hasAccepted, 'btn': true }"
                    data-toggle="tooltip" :title="hasAccepted ? $t('Attending') : $t('Accept')">
                <span :class="{ 'oi': true, 'oi-check': true }"></span>
            </button>
            <button @click="decline" :disabled="this.deadline && !isActive"
                    :class="{ 'btn-danger': hasDeclined, 'btn-default': !hasDeclined, 'btn': true }"
                    data-toggle="tooltip" :title="hasDeclined ? $t('Not attending') : $t('Decline')">
                <span :class="{ 'oi': true, 'oi-x': true }"></span>
            </button>
        </div>
        <span :id="`accept-decline-${this._uid}`" v-else-if="showDot"
              :class="{'accept-decline oi oi-media-record': true, 'text-success': hasAccepted, 'text-danger': hasDeclined, 'text-muted': !hasAccepted && !hasDeclined}"></span>
    </div>
</template>

<script>
    export default {
        name: 'accept-decline',
        props: {
            namespace: {
                type: String,
                required: true
            },
            id: {
                type: Number,
                required: true
            },
            accepted: {
                type: Boolean
            },
            declined: {
                type: Boolean
            },
            deadline: {
                type: String
            },
            showDot: {
                type: Boolean
            }
        },
        data() {
            return {
                hasAccepted: this.accepted,
                hasDeclined: this.declined,
                loading: {
                    accept: false,
                    decline: false
                }
            }
        },
        watch: {
            accepted () {
                this.hasAccepted = this.accepted;
            },
            declined () {
                this.hasDeclined = this.declined;
            }
        },
        computed: {
            isActive: {
                get() {
                    return this.deadline && moment(this.deadline).isAfter(moment());
                }
            }
        },
        methods: {
            accept: function (event) {
                event.preventDefault();

                if (!this.hasAccepted) {
                    this.showCommentField = true;
                    this.loading.accept = true;

                    this.$store.dispatch(`${this.namespace}/accept`, {id: this.id, userId: null})
                        .then(() => {
                            this.loading.accept = false;
                            this.hasAccepted = true;
                            this.hasDeclined = false;
                        });
                }
            },
            decline: function (event) {
                event.preventDefault();

                if (!this.hasDeclined) {
                    this.showCommentField = true;
                    this.loading.decline = true;

                    this.$store.dispatch(`${this.namespace}/decline`, {id: this.id, userId: null})
                        .then(() => {
                            this.loading.decline = false;
                            this.hasAccepted = false;
                            this.hasDeclined = true;
                        });
                }
            }
        }
    }
</script>
