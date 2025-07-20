let dropArea, fileInput, uploadStatus, uploadMoreBtn, recentFilesList, fileManagerBtn;

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

function initElements() {
    dropArea = document.getElementById('drop-area');
    fileInput = document.getElementById('fileElem');
    uploadStatus = document.getElementById('upload-status');
    uploadMoreBtn = document.getElementById('upload-more-btn');
    recentFilesList = document.getElementById('recent-files-list');
    fileManagerBtn = document.getElementById('file-manager-btn');
}

function bindEvents() {
    if (dropArea && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        fileInput.addEventListener('change', handleFiles, false);
    }
    
    if (uploadMoreBtn) {
        uploadMoreBtn.addEventListener('click', resetUploadUI);
    }
    
    if (fileManagerBtn) {
        fileManagerBtn.addEventListener('click', openFileManager);
    }
}

function highlight() {
    dropArea.classList.add('highlight');
}

function unhighlight() {
    dropArea.classList.remove('highlight');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles({ target: { files } });
}

function handleFiles(e) {
    const files = [...e.target.files];
    
    if (files.length === 0) return;

    const file = files[0];
    uploadFile(file);
}

async function uploadFile(file) {
    if (!file) return;
    
    const maxSize = 100 * 1024 * 1024; // 100MB
    if (file.size > maxSize) {
        showUploadStatus(`File too large. Maximum size is ${formatFileSize(maxSize)}`, 'error');
        return;
    }
    
    const filePicker = dropArea.querySelector('.upload-form');
    if (filePicker) filePicker.style.display = 'none';
    
    showUploadStatus(`Preparing ${file.name}...`, 'info');
    
    const formData = new FormData();
    formData.append('file', file);
    
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/upload.php', true);
        xhr.withCredentials = true;
        
        let pausedAt99 = false;
        let done = false;
        
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                let percent = Math.floor((e.loaded / e.total) * 100);
                
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
        
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                done = true;
                
                setTimeout(() => {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                showUploadStatus(`✓ Uploaded: ${file.name}`, 'success');
                                refreshAllFiles();
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
                    
                    dropArea.style.borderColor = '';
                    
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

function showUploadStatus(message, type = 'info') {
    if (uploadStatus) {
        uploadStatus.textContent = message;
        uploadStatus.className = `upload-status ${type}`;
    }
}

function resetUploadUI() {
    const filePicker = dropArea.querySelector('.upload-form');
    if (filePicker) filePicker.style.display = '';
    
    showUploadStatus('', 'info');
    
    if (uploadMoreBtn) {
        uploadMoreBtn.classList.add('hidden');
    }
    
    if (fileInput) {
        fileInput.value = '';
    }
}

async function refreshAllFiles() {
    console.log('Refreshing all file displays...');
    
    await fetchRecentUploads();
    
    if (window.fileExplorer && typeof window.fileExplorer.loadFiles === 'function') {
        await window.fileExplorer.loadFiles();
    }
}

window.refreshAllFiles = refreshAllFiles;

async function fetchRecentUploads() {
    if (!recentFilesList) return;
    
    try {
        const response = await fetch('api/files.php');
        const data = await response.json();
        
        if (data && data.success && Array.isArray(data.files)) {
            const recentFiles = data.files.slice(0, 10);
            renderRecentFiles(recentFiles);
        } else {
            console.warn('Failed to fetch recent uploads: Invalid response format');
        }
    } catch (error) {
        console.error('Error fetching recent uploads:', error);
    }
}

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

function downloadFile(file) {
    const link = document.createElement('a');
    link.href = file.url || `uploads/${file.name}`;
    link.download = file.name;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function openFileManager() {
    console.log('openFileManager() called');
    console.log('window.fileExplorer:', window.fileExplorer);
    
    if (window.fileExplorer) {
        console.log('Calling fileExplorer.open()');
        window.fileExplorer.open();
    } else {
        console.error('File explorer not initialized');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initElements();
    bindEvents();
    fetchRecentUploads();
});
