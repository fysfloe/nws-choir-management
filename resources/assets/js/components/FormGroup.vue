<template>
    <div class="form-group">
        <label :for="name" class="control-label">{{ label }}</label>
        <textarea v-if="type === 'textarea'" :name="name" :id="name" v-model="val" class="form-control"></textarea>
        <select v-else-if="type === 'select'" :name="name" :id="name" v-model="val" class="form-control">
            <option v-for="(option, key) in options" :value="key" :key="key">{{ option }}</option>
        </select>
        <input v-else :type="type" :name="name" :id="name" v-model="val" class="form-control">
        <span v-if="info" class="oi oi-info" data-toggle="tooltip" :title="info"></span>
    </div>
</template>

<script>
    export default {
        name: 'form-group',
        props: {
            value: {
                type: [String, Boolean, Number]
            },
            name: {
                type: String,
                required: true
            },
            type: {
                type: String,
                required: true
            },
            label: {
                type: String,
                required: true
            },
            required: {
                type: Boolean,
                default: false
            },
            options: {
                type: Object,
                default() {
                    return {};
                }
            },
            info: {
                type: String
            }
        },
        data () {
            return {
                val: this.value
            }
        },
        watch: {
            val(val) {
                this.$emit('input', val);
            }
        }
    }
</script>