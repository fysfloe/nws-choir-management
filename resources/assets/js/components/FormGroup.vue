<template>
    <div v-if="type === 'checkbox'" class="form-check">
        <input v-validate="validate" class="form-check-input" type="checkbox" v-model="val" :id="name" :name="name">
        <label :for="name" class="form-check-label">
            {{ label }}
            <span v-if="info" class="oi oi-info" v-b-tooltip.hover :title="info"></span>
        </label>
    </div>
    <div v-else class="form-group">
        <label :for="name" class="control-label">
            {{ label }}
            <span v-if="info" class="oi oi-info" v-b-tooltip.hover :title="info"></span>
        </label>
        <textarea v-on="inputListeners" v-if="type === 'textarea'" v-validate="validate" :name="name" :id="name" v-model="val" class="form-control"></textarea>
        <ckeditor v-if="type === 'ckeditor'" v-validate="validate" :editor="editor" v-model="val" :config="editorConfig"></ckeditor>
        <select v-else-if="type === 'select'" v-validate="validate" :name="name" :id="name" v-model="val"
                class="form-control">
            <option v-for="(option, key) in options" :value="key" :key="key" :selected="val === key">{{ option }}
            </option>
        </select>
        <multiselect v-else-if="type === 'multiselect'" v-validate="validate" selectLabel="" deselectLabel=""
                     :options="options" :name="`${name}[]`" :id="name" label="label" track-by="value"
                     multiple="multiple" v-model="val"></multiselect>
        <datetime v-else-if="isDatetime" :value-zone="type !== 'date' ? 'UTC+1' : 'UTC'" :type="type" input-class="form-control" v-model="val" :name="name" :id="name"></datetime>
        <input v-on="inputListeners" v-else v-validate="validate" :type="type" :name="name" :id="name" v-model="val" class="form-control">

        <span class="help-block text-danger">
             <strong>{{ errors.first(name) }}</strong>
        </span>
    </div>
</template>

<script>
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'form-group',
        components: {
            ckeditor: CKEditor.component
        },
        props: {
            value: {
                type: [String, Boolean, Number, Object, Array]
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
                type: [Object, Array],
                default() {
                    return {};
                }
            },
            info: {
                type: String
            },
            validate: {
                type: String
            }
        },
        data () {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    height: '500px'
                }
            }
        },
        inject: ['$validator'],
        computed: {
            inputListeners () {
                let vm = this;
                // `Object.assign` merges objects together to form a new object
                return Object.assign({},
                    // We add all the listeners from the parent
                    this.$listeners,
                    // Then we can add custom listeners or override the
                    // behavior of some listeners.
                    {
                        // This ensures that the component works with v-model
                        input: function (event) {
                            vm.$emit('input', event.target.value)
                        }
                    }
                )
            },
            isDatetime () {
                return this.type === 'datetime' || this.type === 'date' || this.type === 'time';
            },
            val: {
                get() {
                    return this.type === 'datetime' || this.type === 'date' ?
                        moment(this.value).toISOString()
                        : this.value
                },
                set(val) {
                    this.$emit('input', val);
                }
            }
        }
    }
</script>

<style scoped>

</style>
