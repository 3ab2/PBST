document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addStagiaireForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addForm);
            fetch(addForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = document.getElementById('addStagiaireModal');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert('Error adding stagiaire: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error adding stagiaire: ' + error.message);
            });
        });
    }
});
