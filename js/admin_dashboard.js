// Image Modal Functionality
function openModal(imageSrc) {
    const modal = document.getElementById('modal');
    const modalImg = document.getElementById('modal-content');
    
    modal.style.display = "flex";
    modalImg.src = imageSrc;
    
    // Prevent body scrolling when modal is open
    document.body.style.overflow = 'hidden';
    
    // Add animation class
    modal.classList.add('fade-in');
    modalImg.classList.add('scale-in');
}

function closeModal() {
    const modal = document.getElementById('modal');
    const modalImg = document.getElementById('modal-content');
    
    // Add closing animation
    modal.classList.add('fade-out');
    modalImg.classList.add('scale-out');
    
    // Wait for animation to complete before hiding
    setTimeout(() => {
        modal.style.display = "none";
        modal.classList.remove('fade-out');
        modalImg.classList.remove('scale-out');
        // Restore body scrolling
        document.body.style.overflow = 'auto';
    }, 300);
}

// Close modal when clicking outside the image
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('modal').style.display === 'flex') {
        closeModal();
    }
});

// Add smooth loading for images
document.querySelectorAll('.table-container img').forEach(img => {
    img.addEventListener('load', function() {
        this.classList.add('loaded');
    });
});

// Add table row hover effects
document.querySelectorAll('tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.classList.add('hover');
    });
    row.addEventListener('mouseleave', function() {
        this.classList.remove('hover');
    });
}); 