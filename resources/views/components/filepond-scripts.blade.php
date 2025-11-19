<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script>
// Register FilePond plugins globally
FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginImageEdit
);

/**
 * Initialize FilePond with standard configuration
 * @param {string} selector - CSS selector for the input element
 * @param {object} customOptions - Custom options to override defaults
 * @returns {object} FilePond instance
 */
function initFilePond(selector, customOptions = {}) {
    const inputElement = document.querySelector(selector);
    if (!inputElement) {
        console.error('FilePond input element not found:', selector);
        return null;
    }

    const defaultOptions = {
        labelIdle: 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 170,
        imageCropAspectRatio: '1:1',
        imageResizeTargetWidth: 200,
        imageResizeTargetHeight: 200,
        imageResizeMode: 'cover',
        imageResizeUpscale: true,
        stylePanelLayout: 'compact',
        styleButtonRemoveItemPosition: 'right',
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
        maxFileSize: '2MB',
        credits: false,
        allowMultiple: false,
        required: true,
        instantUpload: false,
        allowRevert: false,
        allowRemove: true,
        imageTransformOutputQuality: 85,
        imageTransformOutputMimeType: 'image/jpeg',
        server: null,
        chunkUploads: false
    };

    const options = { ...defaultOptions, ...customOptions };
    return FilePond.create(inputElement, options);
}
</script>
