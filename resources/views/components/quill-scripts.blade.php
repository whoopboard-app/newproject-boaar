<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
/**
 * Initialize Quill editor with standard configuration
 * @param {string} selector - CSS selector for the editor container
 * @param {object} customOptions - Custom options to override defaults
 * @returns {object} Quill instance
 */
function initQuill(selector, customOptions = {}) {
    const editorContainer = document.querySelector(selector);
    if (!editorContainer) {
        console.error('Quill editor container not found:', selector);
        return null;
    }

    const defaultOptions = {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        },
        placeholder: 'Enter detailed description...',
    };

    const options = { ...defaultOptions, ...customOptions };
    const quill = new Quill(editorContainer, options);

    // Set initial content if provided
    if (customOptions.initialContent) {
        quill.root.innerHTML = customOptions.initialContent;
    }

    return quill;
}
</script>
