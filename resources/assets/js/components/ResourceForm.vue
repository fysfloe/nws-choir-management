<template>
    <div>
        <div v-if="loading" class="loader"></div>
        <slot v-else>
            <header class="page-header">
                <div v-if="isEdit">
                    <h2>
                        <slot name="editTitle"></slot>
                    </h2>
                </div>
                <div v-else>
                    <h2>
                        <slot name="createTitle"></slot>
                    </h2>
                </div>
            </header>
            <form>
                <slot name="form"></slot>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" @click.prevent="submit">
                        <slot name="submitButton"></slot>
                    </button>
                </div>
            </form>
        </slot>
    </div>
</template>

<script>
    export default {
        name: 'resource-form',
        props: {
            namespace: {
                type: String,
                required: true
            },
            resourceKey: {
                type: String,
                required: true
            },
            resource: {
                type: Object,
                default: {}
            },
            edit: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                loading: true
            }
        },
        computed: {
            isEdit() {
                return this.edit || !!this.$route.params.id;
            }
        },
        mounted () {
            if (this.isEdit) {
                this.$store.dispatch(`${this.namespace}/show`, this.$route.params.id).then(() => {
                    this.loading = false;
                });
            } else {
                this.loading = false;
            }
        },
        methods: {
            submit () {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        if (this.isEdit) {
                            this.$store.dispatch(`${this.namespace}/edit`, this.resource)
                                .then(() => {
                                    this.$router.push(`/${this.namespace}/${this.$route.params.id}`);
                                });
                        } else {
                            this.$store.dispatch(`${this.namespace}/add`, this.resource)
                                .then(() => {
                                    this.$router.push(`/${this.namespace}/${this.resource.id}`);
                                });
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>