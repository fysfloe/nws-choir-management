<template>
    <form>
        <div class="form-group">
            <label for="comment" class="control-label">
                {{ $t('Comment') }}
            </label>
            <textarea v-model="comment.comment" class="form-control" name="comment" id="comment"></textarea>
        </div>

        <div class="form-group">
            <label class="control-label">
                <input v-model="comment.private" type="checkbox" name="private" id="private">
                {{ $t('Private (only admins will see your comment)') }}
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" @click.prevent="submit">
                {{ $t('Post Comment') }}
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'comment-form',
        props: {
            type: {
                type: String
            }
        },
        data () {
            return {
                comment: {
                    comment: '',
                    private: true,
                    commentable_id: null
                }
            }
        },
        methods: {
            submit () {
                this.comment.commentable_id = this.$route.params.id;
                this.comment.type = this.type;
                this.$store.dispatch('comments/add', this.comment).then(() => {
                    this.comment = {
                        comment: '',
                        private: true,
                        commentable_id: null
                    }
                });
            }
        }
    }
</script>