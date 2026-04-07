$(document).ready(function () {

    // ✅ Prevent reinitialisation error
    if ($.fn.DataTable.isDataTable('.data-table-export1')) {
        $('.data-table-export1').DataTable().destroy();
    }

    $('.data-table-export1').DataTable({

        scrollCollapse: true,
        autoWidth: false,
        responsive: true,

        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],

        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,

        // ✅ Language + Pagination Text
        language: {
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            lengthMenu: "Show _MENU_ entries",
            search: "",
            searchPlaceholder: "🔍 Search here...",
            paginate: {
                previous: "Previous",
                next: "Next"
            }
        },

        // ✅ Bootstrap 5 Compatible Layout (FINAL FIX)
        dom:
            "<'row mb-3 align-items-center'<'col-md-6 d-flex flex-wrap gap-2'B><'col-md-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3 align-items-center'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

        // ✅ Export Buttons
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-primary btn-sm',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'csv',
                text: 'Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                title: function () {
                    return $('.data-table-export1').data('title') || 'Export Data';
                },
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-dark btn-sm',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            }
        ]

    });

});