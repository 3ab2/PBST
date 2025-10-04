document.addEventListener('DOMContentLoaded', function() {
    // Attach submit event listener to all edit sanction forms dynamically
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('edit-sanction-form')) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    const modal = form.closest('.modal');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    // Reload the page to refresh the table
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error updating sanction: ' + error.message);
            });
        }
    });
});
