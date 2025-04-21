<script>
    $(document).ready(function() {
        var tableId = '#{{ $table_id ?? 'dataTable' }}';
        var table = $(tableId);

        if (table.length) {
            var tableBody = table.find('tbody');
            var hasDataRows = tableBody.find('tr').length > 0 && tableBody.find('tr:first td[colspan]')
                .length === 0;

            if (hasDataRows) {
                table.DataTable({
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    info: true,
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                        emptyTable: "Tidak ada data yang tersedia di tabel ini"
                    },
                    columnDefs: [{
                        targets: 'no-sort',
                        orderable: false,
                        searchable: false
                    }, {
                        targets: 'text-center',
                        className: 'text-center'
                    }, {
                        targets: 'action-column',
                        className: 'action-column',
                        orderable: false,
                        searchable: false
                    }]

                });
            } else {
                console.log('DataTables tidak diinisialisasi untuk ' + tableId + ' karena tabel kosong.');
            }
        } else {
            console.warn('Elemen tabel dengan ID "' + tableId + '" tidak ditemukan.');
        }
    });
</script>
