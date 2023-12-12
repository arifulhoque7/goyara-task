<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Titillium Web', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="col text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addContactModal">Add
                Contact</button>
        </div>
        <h2>All Contacts</h2>
        @include('contacts.notification')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="contactTable">
                <!-- Contacts will be loaded here -->
            </tbody>
        </table>
    </div>

    <!-- Modal for adding contact -->
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addContactForm">
                        @csrf
                        <div class="mb-3">
                            <label for="addName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="addName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="addEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="addEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="addPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="addPhone" name="phone">
                        </div>
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary text-end">Add Contact</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing contact -->
    <div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editContactForm">
                        <input type="hidden" id="editContactId">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone">
                        </div>
                        <!-- Add other fields as needed -->
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to load contacts
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Reset form fields when the modal is shown
            $('#addContactModal').on('show.bs.modal', function(e) {
                $('#addContactForm')[0].reset(); // Reset the form
            });


            function loadContacts() {
                $.ajax({
                    url: '/contacts',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let tableBody = '';

                        data.forEach(contact => {
                            tableBody += `
                    <tr>
                        <td>${contact.name}</td>
                        <td>${contact.email}</td>
                        <td>${contact.phone}</td>
                        <td>
                            <button class="btn btn-primary btn-sm editContact" data-id="${contact.id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteContact" data-id="${contact.id}">Delete</button>
                        </td>
                    </tr>
                `;
                        });

                        $('#contactTable').html(tableBody);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }


            loadContacts(); // Call the function on page load
            // Adding new contact via AJAX
            $(document).on('submit', '#addContactForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: '/contacts',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addContactModal').modal('hide');
                        loadContacts();
                        // Show success toast for contact addition
                        $('.toast-add-contact .toast-body').text('Contact added successfully!');
                        $('.toast-add-contact').toast('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Show error toast for contact addition failure
                        $('.toast-add-contact-error .toast-body').text(
                            'Failed to add contact!');
                        $('.toast-add-contact-error').toast('show');
                    }
                });
            });


            // Function to load edit modal with contact details
            $(document).on('click', '.editContact', function() {
                var contactId = $(this).data('id');

                $.ajax({
                    url: '/contacts/' + contactId + '/edit',
                    type: 'GET',
                    dataType: 'json',
                    success: function(contact) {
                        $('#editContactModal').modal('show');
                        $('#editContactId').val(contact.id);
                        $('#editName').val(contact.name);
                        $('#editEmail').val(contact.email);
                        $('#editPhone').val(contact.phone);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });



            // Submitting edited contact via AJAX
            $(document).on('submit', '#editContactForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var contactId = $('#editContactId').val();

                $.ajax({
                    url: '/contacts/' + contactId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#editContactModal').modal('hide');
                        loadContacts();
                        // Show success toast
                        $('.toast-success .toast-body').text('Contact updated successfully!');
                        $('.toast-success').toast('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Show error toast
                        $('.toast-error .toast-body').text('Failed to update contact!');
                        $('.toast-error').toast('show');
                    }
                });
            });


            // Deleting contact via AJAX
            $(document).on('click', '.deleteContact', function() {
                var contactId = $(this).data('id');

                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: '/contacts/' + contactId,
                        type: 'DELETE',
                        success: function(response) {
                            loadContacts();
                            // Show success toast
                            $('.toast-success').toast('show');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            // Show error toast
                            $('.toast-error').toast('show');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
