document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addStagiaireForm');
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
                $('#addStagiaireModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error adding stagiaire: ' + error.message);
        });
    });
});
