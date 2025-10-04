document.addEventListener('DOMContentLoaded', function() {
    const editForms = document.querySelectorAll('form[action*="edit_stagiaire.php"]');
    editForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
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
                    const modal = form.closest('.modal');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert('Error editing stagiaire: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error editing stagiaire: ' + error.message);
            });
        });
    });
});
