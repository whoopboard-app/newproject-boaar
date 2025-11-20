<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor-wrapper {
        font-family: inherit;
    }

    .ql-container {
        font-family: inherit;
        font-size: 1rem;
    }

    .ql-editor {
        min-height: 200px;
        max-height: 500px;
        overflow-y: auto;
    }

    .ql-snow .ql-editor h1 {
        font-size: 2em;
    }

    .ql-snow .ql-editor h2 {
        font-size: 1.5em;
    }

    .ql-snow .ql-editor h3 {
        font-size: 1.17em;
    }

    .ql-toolbar.ql-snow {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem 0.25rem 0 0;
        background-color: #f8f9fa;
    }

    .ql-container.ql-snow {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 0.25rem 0.25rem;
    }

    .ql-editor.ql-blank::before {
        color: #6c757d;
        font-style: normal;
    }

    /* Error state styling */
    .quill-editor-wrapper.is-invalid .ql-toolbar.ql-snow,
    .quill-editor-wrapper.is-invalid .ql-container.ql-snow {
        border-color: #dc3545;
    }
</style>
