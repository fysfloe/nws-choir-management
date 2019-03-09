<template>
    <div>
        <div class="row comment-stream">
            <div class="col">
                <div v-if="items.length > 0">
                    <div class="comment row" v-for="comment in items" :key="comment.id">
                        <div class="col-md-3 flex comment-info">
                            <button type="submit" class="btn" v-if="currentUser.id === comment.user.id" @click="remove(comment.id)">
                                <span class="oi oi-x" data-toggle="tooltip" :title="$t('Remove comment')"></span>
                            </button>
                            <div class="avatar">
                                <img v-if="comment.user.avatar" :src="`/storage/avatars/${comment.user.avatar}`" :alt="`${comment.user.firstname} ${comment.user.surname}`">
                                <img v-else src="/img/default_avatar.svg">
                            </div>
                            <div>
                                <strong>
                                    {{ comment.user.firstname }} {{ comment.user.surname }}
                                    &nbsp;<span :class="{'accept-decline oi oi-media-record': true, 'text-success': comment.accepted, 'text-danger': comment.declined, 'text-muted': !comment.accepted && !comment.declined}"></span>
                                </strong><br>
                                <small class="text-muted">
                                    {{ comment.created_at }}
                                    <span v-if="comment.private" class="oi oi-lock-locked" data-toggle="tooltip" :title="$t('This comment can be seen only by you and admins.')"></span>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-9 comment-content">
                            {{ comment.comment }}
                        </div>
                    </div>
                </div>

                <span v-else>
                {{ $t('No comments yet.') }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <comment-form
                    :type="type"
                ></comment-form>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentForm from '../Comment/CommentForm';

    export default {
        name: 'comments',
        components: {CommentForm},
        props: {
            type: {
                type: String,
                default: 'project'
            },
            actionParameters: {
                type: Object,
                default: {}
            }
        },
        data () {
            return {
                loading: true
            }
        },
        computed: {
            items () {
                return this.$store.state.comments.items;
            },
            currentUser() {
                return this.$store.state.users.current;
            }
        },
        mounted () {
            this.$store.dispatch('comments/fetch', this.actionParameters).then(() => {
                this.loading = false;
            });
        },
        methods: {
            remove (id) {
                this.$store.dispatch('comments/delete', id);
            }
        }
    }
</script>