<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="editorContent" />
        <trix-editor
            ref="trix"
            input="trix"
            :placeholder="placeholder"
            @trix-change="handleContentChange"
        ></trix-editor>
    </div>
</template>

<script>
import Trix from 'trix';
export default {
    props: ['name', 'value', 'placeholder'],

    data() {
        return {
            editorContent: this.value
        };
    },
    watch: {
        value: {
            handler: 'handleValueChange'
        }
    },

    methods: {
        handleContentChange(e) {
            this.editorContent = e.target.value;
            this.$emit('input', this.editorContent);
        },
        handleValueChange(e) {
            this.editorContent = e;
            this.$refs.trix.editor.loadHTML(this.editorContent);
            this.$refs.trix.editor.setSelectedRange(
                this.$refs.trix.editor.getDocument().toString().length - 1
            );
        }
    }
};
</script>
