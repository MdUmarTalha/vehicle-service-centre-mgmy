$(document).ready(function() {
    // Initialize DataTables
    $('.data-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10
    });

    // Client-side form validation
    $('form.needs-validation').on('submit', function(event) {
        let form = $(this)[0];
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
