// File Upload and Management Module - Simple JavaScript for student project

// DOM Elements
let dropArea, fileInput, uploadStatus, uploadMoreBtn, recentFilesList, fileManagerBtn;

// Helper Functions
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Initialize DOM elements
function initElements() {
    dropArea = document.getElementById('drop-area');
    fileInput = document.getElementById('fileElem');
    uploadStatus = document.getElementById('upload-status');
    uploadMoreBtn = document.getElementById('upload-more-btn');
    recentFilesList = document.getElementById('recent-files-list');
    fileManagerBtn = document.getElementById('file-manager-btn');
}

// Bind event listeners
function bindEvents() {
    if (dropArea && fileInput) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        
        // Handle file input change
        fileInput.addEventListener('change', handleFiles, false);
    }
    
    // Upload more button
    if (uploadMoreBtn) {
        uploadMoreBtn.addEventListener('click', resetUploadUI);
    }
    
    // File manager button
    if (fileManagerBtn) {
        fileManagerBtn.addEventListener('click', openFileManager);
    }
}

// Highlight drop area
function highlight() {
    dropArea.classList.add('highlight');
}

// Remove highlight from drop area
function unhighlight() {
    dropArea.classList.remove('highlight');
}

// Handle dropped files
function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles({ target: { files } });
}

// Handle file selection
function handleFiles(e) {
    const files = [...e.target.files];
    
    if (files.length === 0) return;
    
    // For now, handle single file upload
    // You can extend this to handle multiple files
    const file = files[0];
    uploadFile(file);
}

// Upload file to server
async function uploadFile(file) {
    if (!file) return;
    
    // Validate file size (e.g., max 100MB)
    const maxSize = 100 * 1024 * 1024; // 100MB
    if (file.size > maxSize) {
        showUploadStatus(`File too large. Maximum size is ${formatFileSize(maxSize)}`, 'error');
        return;
    }
    
    // Hide the file picker UI
    const filePicker = dropArea.querySelector('.upload-form');
    if (filePicker) filePicker.style.display = 'none';
    
    // Show upload progress
    showUploadStatus(`Preparing ${file.name}...`, 'info');
    
    const formData = new FormData();
    formData.append('file', file);
    
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/upload.php', true);
        xhr.withCredentials = true;
        
        let pausedAt99 = false;
        let done = false;
        
        // Track upload progress
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                let percent = Math.floor((e.loaded / e.total) * 100);
                
                // Pause at 99% for better UX
                if (percent >= 99 && !pausedAt99 && !done) {
                    percent = 99;
                    pausedAt99 = true;
                    showUploadStatus(`Uploading ${file.name}: 99%`, 'info');
                    
                    setTimeout(() => {
                        if (!done) {
                            showUploadStatus(`Uploading ${file.name}: 100%`, 'info');
                        }
                    }, 2000);
                } else if (percent < 99 && !done) {
                    showUploadStatus(`Uploading ${file.name}: ${percent}%`, 'info');
                }
            }
        });
        
        // Handle upload completion
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                done = true;
                
                setTimeout(() => {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                showUploadStatus(`✓ Uploaded: ${file.name}`, 'success');
                                refreshAllFiles(); // Refresh all file displays
                            } else {
                                showUploadStatus(`✗ Error: ${data.message}`, 'error');
                            }
                        } catch (error) {
                            showUploadStatus('✗ Upload failed - Invalid response', 'error');
                            console.error('Upload response parse error:', error);
                        }
                    } else {
                        showUploadStatus('✗ Upload failed - Server error', 'error');
                    }
                    
                    // Reset drop area styling
                    dropArea.style.borderColor = '';
                    
                    // Show 'Upload More' button after upload
                    if (uploadMoreBtn) {
                        uploadMoreBtn.classList.remove('hidden');
                    }
                }, pausedAt99 ? 1000 : 0);
            }
        };
        
        xhr.onerror = () => {
            showUploadStatus('✗ Upload failed - Network error', 'error');
            resetUploadUI();
        };
        
        xhr.send(formData);
        
    } catch (error) {
        showUploadStatus('✗ Upload failed - Unexpected error', 'error');
        console.error('Upload error:', error);
        resetUploadUI();
    }
}

// Show upload status message
function showUploadStatus(message, type = 'info') {
    if (uploadStatus) {
        uploadStatus.textContent = message;
        uploadStatus.className = `upload-status ${type}`;
    }
}

// Reset upload UI to initial state
function resetUploadUI() {
    // Show the file picker again
    const filePicker = dropArea.querySelector('.upload-form');
    if (filePicker) filePicker.style.display = '';
    
    // Clear upload status
    showUploadStatus('', 'info');
    
    // Hide upload more button
    if (uploadMoreBtn) {
        uploadMoreBtn.classList.add('hidden');
    }
    
    // Reset file input
    if (fileInput) {
        fileInput.value = '';
    }
}

// Shared function to refresh all file displays
async function refreshAllFiles() {
    console.log('Refreshing all file displays...');
    
    // Refresh recent uploads
    await fetchRecentUploads();
    
    // Refresh file explorer if it's open and initialized
    if (window.fileExplorer && typeof window.fileExplorer.loadFiles === 'function') {
        await window.fileExplorer.loadFiles();
    }
}

// Make refreshAllFiles globally available
window.refreshAllFiles = refreshAllFiles;

// Fetch recent uploads from API (using same source as file explorer)
async function fetchRecentUploads() {
    if (!recentFilesList) return;
    
    try {
        const response = await fetch('api/files.php');
        const data = await response.json();
        
        if (data && data.success && Array.isArray(data.files)) {
            // Show only the most recent 10 files for the recent uploads section
            const recentFiles = data.files.slice(0, 10);
            renderRecentFiles(recentFiles);
        } else {
            console.warn('Failed to fetch recent uploads: Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching recent uploads:', error);
    }
}

// Render recent files list
function renderRecentFiles(files) {
    if (!recentFilesList) return;
    
    recentFilesList.innerHTML = '';
    
    if (files.length === 0) {
        const li = document.createElement('li');
        li.innerHTML = '<em>No recent uploads</em>';
        li.style.fontStyle = 'italic';
        li.style.opacity = '0.7';
        recentFilesList.appendChild(li);
        return;
    }
    
    files.forEach(file => {
        const li = document.createElement('li');
        
        const link = document.createElement('a');
        link.href = file.url || `uploads/${file.name}`;
        link.textContent = file.name;
        link.target = '_blank';
        link.title = `Download ${file.name}`;
        
        const downloadBtn = document.createElement('button');
        downloadBtn.className = 'download-btn';
        downloadBtn.title = 'Download file';
        downloadBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        `;
        
        downloadBtn.addEventListener('click', (e) => {
            e.preventDefault();
            downloadFile(file);
        });
        
        li.appendChild(link);
        li.appendChild(downloadBtn);
        recentFilesList.appendChild(li);
    });
}

// Download file
function downloadFile(file) {
    const link = document.createElement('a');
    link.href = file.url || `uploads/${file.name}`;
    link.download = file.name;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Open file manager
function openFileManager() {
    console.log('openFileManager() called');
    console.log('window.fileExplorer:', window.fileExplorer);
    
    // Open the file explorer modal
    if (window.fileExplorer) {
        console.log('Calling fileExplorer.open()');
        window.fileExplorer.open();
        // The file explorer will load fresh data when opened
    } else {
        console.error('File explorer not initialized');
    }
}

// Initialize file upload manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initElements();
    bindEvents();
    fetchRecentUploads();
});
