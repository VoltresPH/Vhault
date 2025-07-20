// File Explorer Modal Module
class FileExplorer {
    constructor() {
        this.modal = null;
        this.closeBtn = null;
        this.searchInput = null;
        this.fileBody = null;
        this.currentFiles = [];
        this.filteredFiles = [];
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        this.bindElements();
        this.bindEvents();
    }
    
    bindElements() {
        this.modal = document.getElementById('file-explorer-modal');
        this.closeBtn = document.getElementById('file-explorer-close-btn');
        this.searchInput = document.getElementById('file-search');
        this.fileBody = document.getElementById('file-explorer-body');
        
        console.log('FileExplorer elements:', {
            modal: this.modal,
            closeBtn: this.closeBtn,
            searchInput: this.searchInput,
            fileBody: this.fileBody
        });
    }
    
    bindEvents() {
        // Close modal events
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }
        
        // Close on backdrop click
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.close();
                }
            });
        }
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
        
        // Search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.filterFiles(e.target.value);
            });
        }
    }
    
    async open() {
        console.log('FileExplorer.open() called', this.modal);
        if (!this.modal) {
            console.error('Modal element not found!');
            return;
        }
        
        console.log('Opening modal...');
        this.modal.setAttribute('aria-hidden', 'false');
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        console.log('Modal classes:', this.modal.classList.toString());
        
        // Focus search input
        if (this.searchInput) {
            this.searchInput.focus();
        }
        
        // Load files
        await this.loadFiles();
    }
    
    close() {
        if (!this.modal) return;
        
        this.modal.setAttribute('aria-hidden', 'true');
        this.modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Clear search
        if (this.searchInput) {
            this.searchInput.value = '';
        }
    }
    
    isOpen() {
        return this.modal && this.modal.classList.contains('active');
    }
    
    async loadFiles() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        try {
            const response = await fetch('api/files.php');
            const data = await response.json();
            
            if (data.success && Array.isArray(data.files)) {
                this.currentFiles = data.files;
                this.filteredFiles = [...this.currentFiles];
                this.renderFiles();
            } else {
                this.showError(data.message || 'Failed to load files');
            }
        } catch (error) {
            console.error('Error loading files:', error);
            this.showError('Network error while loading files');
        } finally {
            this.isLoading = false;
        }
    }
    
    filterFiles(searchTerm) {
        if (!searchTerm.trim()) {
            this.filteredFiles = [...this.currentFiles];
        } else {
            const term = searchTerm.toLowerCase();
            this.filteredFiles = this.currentFiles.filter(file => 
                file.name.toLowerCase().includes(term) ||
                (file.type && file.type.toLowerCase().includes(term))
            );
        }
        this.renderFiles();
    }
    
    showLoading() {
        if (!this.fileBody) return;
        
        this.fileBody.innerHTML = `
            <div class="loading-container">
                <div class="spinner"></div>
                <p>Loading files...</p>
            </div>
        `;
    }
    
    showError(message) {
        if (!this.fileBody) return;
        
        this.fileBody.innerHTML = `
            <div class="error-container">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p>${message}</p>
                <button onclick="fileExplorer.loadFiles()" class="retry-btn">Try Again</button>
            </div>
        `;
    }
    
    renderFiles() {
        if (!this.fileBody) return;
        
        if (this.filteredFiles.length === 0) {
            this.fileBody.innerHTML = `
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                        <polyline points="13,2 13,9 20,9"/>
                    </svg>
                    <p>${this.currentFiles.length === 0 ? 'No files uploaded yet' : 'No files match your search'}</p>
                </div>
            `;
            return;
        }
        
        const fileGrid = document.createElement('div');
        fileGrid.className = 'file-grid';
        
        this.filteredFiles.forEach(file => {
            const fileCard = this.createFileCard(file);
            fileGrid.appendChild(fileCard);
        });
        
        this.fileBody.innerHTML = '';
        this.fileBody.appendChild(fileGrid);
    }
    
    createFileCard(file) {
        const card = document.createElement('div');
        card.className = 'file-card';
        
        const fileIcon = this.getFileIcon(file.type || file.name);
        const fileSize = this.formatFileSize(file.size || 0);
        const uploadDate = this.formatDate(file.upload_date || file.date);
        
        card.innerHTML = `
            <div class="file-icon">
                ${fileIcon}
            </div>
            <div class="file-info">
                <h3 class="file-name" title="${file.name}">${file.name}</h3>
                <div class="file-meta">
                    <span class="file-size">${fileSize}</span>
                    <span class="file-date">${uploadDate}</span>
                </div>
            </div>
            <div class="file-actions">
                <button class="action-btn download-btn" title="Download" onclick="fileExplorer.downloadFile('${file.name}', '${file.url || ''}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7,10 12,15 17,10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                </button>
                <button class="action-btn delete-btn" title="Delete" onclick="fileExplorer.deleteFile('${file.id}', '${file.name}')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3,6 5,6 21,6"/>
                        <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2,2h4a2,2,0,0,1,2,2V6"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                </button>
            </div>
        `;
        
        return card;
    }
    
    getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        
        const icons = {
            // Images
            'jpg': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>',
            'jpeg': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>',
            'png': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>',
            'gif': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>',
            
            // Documents
            'pdf': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>',
            'doc': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>',
            'docx': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>',
            'txt': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8Z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>',
            
            // Archives
            'zip': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 22h2a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v3"/><polyline points="14,2 14,8 20,8"/><path d="M10 20v-1a2 2 0 1 1 4 0v1a2 2 0 1 1-4 0Z"/><path d="M10 7h4"/><path d="M10 12h4"/></svg>',
            'rar': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 22h2a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v3"/><polyline points="14,2 14,8 20,8"/><path d="M10 20v-1a2 2 0 1 1 4 0v1a2 2 0 1 1-4 0Z"/><path d="M10 7h4"/><path d="M10 12h4"/></svg>',
            
            // Videos
            'mp4': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
            'avi': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
            'mov': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
            
            // Audio
            'mp3': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
            'wav': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
        };
        
        return icons[ext] || '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13,2 13,9 20,9"/></svg>';
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    formatDate(dateString) {
        if (!dateString) return 'Unknown';
        
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) {
            return 'Today';
        } else if (diffDays === 2) {
            return 'Yesterday';
        } else if (diffDays <= 7) {
            return `${diffDays - 1} days ago`;
        } else {
            return date.toLocaleDateString();
        }
    }
    
    downloadFile(filename, url) {
        const link = document.createElement('a');
        link.href = url || `uploads/${filename}`;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    showDeleteConfirmation(filename) {
        return new Promise((resolve) => {
            const dialog = this.createDeleteDialog(filename, resolve);
            document.body.appendChild(dialog);
            
            // Focus the cancel button by default
            const cancelBtn = dialog.querySelector('.dialog-cancel');
            if (cancelBtn) cancelBtn.focus();
        });
    }
    
    createDeleteDialog(filename, resolve) {
        const dialog = document.createElement('div');
        dialog.className = 'delete-confirmation-dialog';
        
        dialog.innerHTML = `
            <div class="dialog-backdrop"></div>
            <div class="dialog-content">
                <div class="dialog-header">
                    <div class="dialog-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                    <h3>Delete File</h3>
                </div>
                <div class="dialog-body">
                    <p>Are you sure you want to delete <strong>"${filename}"</strong>?</p>
                    <p class="dialog-warning">This action cannot be undone.</p>
                </div>
                <div class="dialog-actions">
                    <button class="dialog-btn dialog-cancel">Cancel</button>
                    <button class="dialog-btn dialog-delete">Delete</button>
                </div>
            </div>
        `;
        
        // Handle backdrop click
        const backdrop = dialog.querySelector('.dialog-backdrop');
        backdrop.addEventListener('click', () => {
            this.closeDialog(dialog);
            resolve(false);
        });
        
        // Handle cancel button
        const cancelBtn = dialog.querySelector('.dialog-cancel');
        cancelBtn.addEventListener('click', () => {
            this.closeDialog(dialog);
            resolve(false);
        });
        
        // Handle delete button
        const deleteBtn = dialog.querySelector('.dialog-delete');
        deleteBtn.addEventListener('click', () => {
            this.closeDialog(dialog);
            resolve(true);
        });
        
        // Handle escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                this.closeDialog(dialog);
                resolve(false);
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
        
        return dialog;
    }
    
    closeDialog(dialog) {
        dialog.classList.add('closing');
        setTimeout(() => {
            if (dialog.parentNode) {
                document.body.removeChild(dialog);
            }
        }, 200);
    }
    
    async deleteFile(fileId, filename) {
        const confirmed = await this.showDeleteConfirmation(filename);
        if (!confirmed) {
            return;
        }
        
        try {
            const response = await fetch('api/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: fileId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Remove from current files by ID
                this.currentFiles = this.currentFiles.filter(file => file.id !== fileId);
                this.filteredFiles = this.filteredFiles.filter(file => file.id !== fileId);
                this.renderFiles();
                
                // Refresh all file displays using shared function
                if (typeof window.refreshAllFiles === 'function') {
                    window.refreshAllFiles();
                }
            } else {
                alert(data.message || 'Failed to delete file');
            }
        } catch (error) {
            console.error('Error deleting file:', error);
            alert('Network error while deleting file');
        }
    }
}

// Initialize file explorer
let fileExplorer;

document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing FileExplorer...');
    fileExplorer = new FileExplorer();
    // Export for global access after initialization
    window.fileExplorer = fileExplorer;
    console.log('FileExplorer initialized and set to window.fileExplorer:', window.fileExplorer);
});
