@props([
    'name' => 'image',
    'mode' => 'single',          // single | multiple | avatar
    'accept' => 'image/jpeg,image/png,image/webp,image/jpg',
    'maxSizeMb' => 5,
    'endpoint' => route('upload.image'), // default single image endpoint
    'previewUrl' => null,
    'label' => 'Upload Image',
    'helpText' => 'JPG, PNG, WEBP up to ' . ($maxSizeMb ?? 5) . 'MB',
])

@php
$fileInputName = $name . '_file' . ($mode === 'multiple' ? '[]' : '');
$isMultiple = $mode === 'multiple';
$wrapperId = 'uploader-' . preg_replace('/[^a-z0-9]/i', '-', $name) . '-' . uniqid();
@endphp

<div class="image-uploader" id="{{ $wrapperId }}" data-mode="{{ $mode }}" data-endpoint="{{ $endpoint }}" data-max-size="{{ $maxSizeMb * 1024 * 1024 }}">
    @if ($label)
        <label class="form-label fw-semibold">{{ $label }}</label>
    @endif

    <div class="uploader-dropzone border rounded-3 p-3 text-center bg-light position-relative cursor-pointer" style="border-style: dashed;">
        <input type="file" name="{{ $fileInputName }}" id="{{ $wrapperId }}-input"
               accept="{{ $accept }}" @if($isMultiple) multiple @endif
               class="uploader-input position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
               style="z-index: 2;">

        <div class="uploader-prompt">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-cloud-arrow-up text-muted mb-2" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.146a.5.5 0 1 1-.708-.708l2-2z"/>
                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.473 1.072-2.84 2.68-3.042 1.07-.114 2.077-.515 2.926-1.07.435-.279.91-.495 1.406-.62A5.55 5.55 0 0 1 4.406 3.342zM8 3.145c-1.1 0-2.078.57-2.645 1.442-.352.564-.842.99-1.413 1.222-.57.232-1.194.37-1.82.41-.84.05-1.52.68-1.52 1.39 0 .765.686 1.383 1.531 1.383h8.906c1.234 0 2.234-.93 2.234-2.08 0-1.1-.9-2.027-2.03-2.03-.68-.003-1.34-.15-1.93-.42-.59-.27-1.09-.68-1.45-1.17-.36-.49-.58-1.05-.64-1.64a4.53 4.53 0 0 0-.64-1.64z"/>
            </svg>
            <p class="mb-1 text-muted small">Drag & drop {{ $isMultiple ? 'images' : 'an image' }} here or click to browse</p>
            @if ($helpText)
                <small class="text-muted">{{ $helpText }}</small>
            @endif
        </div>

        <div class="uploader-preview mt-3 d-none">
            @if ($previewUrl && ($mode === 'single' || $mode === 'avatar'))
                <img src="{{ $previewUrl }}" alt="Preview" class="img-thumbnail rounded preview-image" style="max-height: 160px;">
            @endif
        </div>
    </div>

    <div class="uploader-feedback mt-2 small" aria-live="polite"></div>

    <input type="hidden" name="{{ $name }}" class="uploader-url" value="{{ $previewUrl ?? '' }}">
</div>

@pushOnce('scripts')
<script>
    (function () {
        const wrappers = document.querySelectorAll('.image-uploader');

        wrappers.forEach(wrapper => {
            const input = wrapper.querySelector('.uploader-input');
            const preview = wrapper.querySelector('.uploader-preview');
            const prompt = wrapper.querySelector('.uploader-prompt');
            const feedback = wrapper.querySelector('.uploader-feedback');
            const urlInput = wrapper.querySelector('.uploader-url');
            const mode = wrapper.dataset.mode;
            const endpoint = wrapper.dataset.endpoint;
            const maxSize = parseInt(wrapper.dataset.maxSize, 10);

            function showError(message) {
                feedback.innerHTML = `<span class="text-danger">${message}</span>`;
            }

            function showSuccess(message) {
                feedback.innerHTML = `<span class="text-success">${message}</span>`;
            }

            function clearFeedback() {
                feedback.innerHTML = '';
            }

            function previewFile(file) {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail rounded preview-image" style="max-height: 160px;">`;
                    preview.classList.remove('d-none');
                    prompt.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }

            async function uploadFiles(filesToUpload) {
                const formData = new FormData();
                if (mode === 'multiple') {
                    filesToUpload.forEach(f => formData.append('files', f));
                } else {
                    formData.append('file', filesToUpload[0]);
                }

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        },
                        body: formData,
                    });

                    const result = await response.json();

                    if (!response.ok || (result.success === false)) {
                        throw new Error(result.message || `Upload failed (${response.status})`);
                    }

                    const images = result.images || (result.image_url ? [{ image_url: result.image_url }] : []);
                    if (images.length > 0 && urlInput) {
                        urlInput.value = images[0].image_url || '';
                    }

                    showSuccess('Uploaded successfully.');
                    return images;
                } catch (err) {
                    showError(err.message || 'Upload failed. Please try again.');
                    return null;
                }
            }

            input.addEventListener('change', async () => {
                const files = Array.from(input.files);
                if (files.length === 0) return;

                clearFeedback();

                const oversized = files.find(f => f.size > maxSize);
                if (oversized) {
                    showError(`File too large. Max size is ${(maxSize / 1024 / 1024).toFixed(1)}MB.`);
                    return;
                }

                const invalidType = files.find(f => !f.type.startsWith('image/'));
                if (invalidType) {
                    showError('Only image files (JPG, PNG, WEBP) are allowed.');
                    return;
                }

                if (mode === 'multiple') {
                    preview.innerHTML = '';
                    for (const file of files) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            preview.innerHTML += `<img src="${e.target.result}" class="img-thumbnail rounded me-2 mb-2" style="max-height: 80px;">`;
                        };
                        reader.readAsDataURL(file);
                    }
                    preview.classList.remove('d-none');
                    prompt.classList.add('d-none');
                    await uploadFiles(files);
                } else {
                    previewFile(files[0]);
                    await uploadFiles(files);
                }
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                wrapper.addEventListener(eventName, e => {
                    e.preventDefault();
                    wrapper.querySelector('.uploader-dropzone').classList.add('border-primary', 'bg-white');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                wrapper.addEventListener(eventName, e => {
                    e.preventDefault();
                    wrapper.querySelector('.uploader-dropzone').classList.remove('border-primary', 'bg-white');
                });
            });
        });
    })();
</script>
@endPushOnce
