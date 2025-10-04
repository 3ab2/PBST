document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addNoteForm');
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
                    const modal = document.getElementById('addNoteModal');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                    location.reload();
                } else {
                    alert('Error adding note: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error adding note: ' + error.message);
            });
        });
    }
});
