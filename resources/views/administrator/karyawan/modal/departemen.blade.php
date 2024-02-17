<!-- Modal Detail Departemen -->
<div class="modal fade" id="ModalDepartemen" tabindex="-1" aria-labelledby="ModalDepartemenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalDepartemenLabel">Data Departemen Departemensi</h5>
                <button type="button" id="buttonCloseDepartemenModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ModalDepartemenBody">
                <table class="table" id="datatableDepartemenModal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Nama</th>
                            <th width="">Deskripsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDataDepartemen">Pilih Data</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        function addSelectedClassByDepartemen(id) {
            var table = $('#datatableDepartemenModal').DataTable();

            // Check if the 'select' extension is available
            if ($.fn.dataTable.Select) {
                // Check if the 'select' extension is initialized for the table
                if (table.select) {
                    // Deselect all rows first
                    table.rows().deselect();
                }
            }

            table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows

            if (id) {
                table.rows().every(function() {
                    var rowData = this.data();
                    if (rowData.id === parseInt(id)) {
                        // Check if the 'select' extension is available before using 'select' method
                        if ($.fn.dataTable.Select && table.select) {
                            this.select(); // Select the row
                        }
                        $(this.node()).addClass('selected'); // Add 'selected' class
                        return false; // Break the loop
                    }
                });
            }
        }
        
        $('#ModalDepartemen').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            
            // Now, you can initialize a new DataTable on the same table.
            $("#datatableDepartemenModal").DataTable().destroy();
            $('#datatableDepartemenModal tbody').remove();
            var data_table = $('#datatableDepartemenModal').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sFirst": "<i class='ti-angle-left'></i>",
                        "sPrevious": "&#8592;",
                        "sNext": "&#8594;",
                        "sLast": "<i class='ti-angle-right'></i>"
                    }
                },
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                // scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.karyawan.getDataDepartemen') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var id = $("#inputDepartemen").val();
                    addSelectedClassByDepartemen(id);
                },
            });

            // click di baris tabel member
            $('#datatableDepartemenModal tbody').on('click', 'tr', function() {
                var $row = $(this);
                
                // Remove 'selected' class from all rows
                $('#datatableDepartemenModal tbody tr').removeClass('selected');
                
                // Add 'selected' class to the clicked row
                $row.addClass('selected');
                
                // Get selected row data
                var selectedRow = data_table.row('.selected').data();
                
                // if (selectedRow) {
                //     // Set input values based on the selected row
                //     $("#inputDepartemen").val(selectedRow.id);
                //     $("#inputDepartemenName").val(selectedRow.nama);
                // }
            });
            // end click di baris tabel member

            // click Select button
            $('#selectDataDepartemen').on('click', function() {
                // Get selected row data
                var selectedRow = data_table.row('.selected').data();

                if (selectedRow) {
                    $("#inputDepartemen").val(selectedRow.id);
                    $("#inputDepartemenName").val(selectedRow.nama);
                }

                $('#buttonCloseDepartemenModal').click();
            });
            // end click Select button
        });
    </script>
@endpush
