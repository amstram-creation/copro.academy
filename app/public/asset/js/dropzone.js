/**
 * dropZoneUploader.js
 *
 * Usage:
 *   import { initDropZones } from './dropZoneUploader.js';
 *   document.addEventListener('DOMContentLoaded', () => {
 *     initDropZones();
 *   });
 */

/**
 * Finds all drop‐zone elements (by CSS selector), and wires up
 * drag-n-drop + file-input + AJAX upload behavior.
 *
 * @param {string} selector — CSS selector for your drop zones (default: '.drop-zone')
 */
function initDropZones(selector = '.drop-zone') {
  document.querySelectorAll(selector).forEach((zone) => {
    const input = zone.querySelector('input[type="file"]');
    const labelSpan = zone.querySelector('.drop-label span');
    const uploadUrl = zone.dataset.upload;

    if (!input || !labelSpan || !uploadUrl) {
      console.warn(
        'dropZoneUploader: missing <input>, .drop-label span, or data-upload on',
        zone
      );
      return;
    }

    // Prevent default browser handling for drag/drop…
    const preventDefaults = (e) => {
      e.preventDefault();
      e.stopPropagation();
    };
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((evt) =>
      zone.addEventListener(evt, preventDefaults)
    );

    // Toggle CSS class for visual feedback
    ['dragenter', 'dragover'].forEach((evt) =>
      zone.addEventListener(evt, () => zone.classList.add('drag-over'))
    );
    ['dragleave', 'drop'].forEach((evt) =>
      zone.addEventListener(evt, () => zone.classList.remove('drag-over'))
    );

    // Handle actual drop
    zone.addEventListener('drop', (e) => {
      if (e.dataTransfer.files[0]) {
        _uploadFile(e.dataTransfer.files[0], zone, labelSpan, input, uploadUrl);
      }
    });

    // Handle “click-and-select” fallback
    input.addEventListener('change', (e) => {
      if (e.target.files[0]) {
        _uploadFile(e.target.files[0], zone, labelSpan, input, uploadUrl);
      }
    });
  });
}

async function _uploadFile(file, zone, labelSpan, input, uploadUrl) {
  const formData = new FormData();

  if (input.dataset.keepFilename === 1) {
    let cleaname = file.name
      .replace(/[^\w\-\. ]+/g, '') // Remove anything not alphanumeric, dash, dot, space
      .replace(/\s+/g, '-') // Replace spaces with dashes
      .replace(/-+/g, '-') // Collapse multiple dashes
      .replace(/^\-+|\-+$/g, ''); // Trim leading/trailing dashes
    uploadUrl += `${encodeURIComponent(cleaname)}`;
  }

  formData.append(input.name, file);
  labelSpan.textContent = 'Uploading...';
  
  try {
    console.log('Upload URL:', uploadUrl);
    const resp = await fetch(uploadUrl, {
      method: 'POST',
      body: formData,
    });
    const data = await resp.json();
    if (data.success) {
      zone.querySelector('img.drop-preview').setAttribute('src', data.url);
    } else {
      labelSpan.textContent = 'Upload failed';
      console.error('Upload error response:', data);
    }
  } catch (err) {
    console.error('Upload exception:', err);
    labelSpan.textContent = 'Upload failed';
  }
}

export default initDropZones;
