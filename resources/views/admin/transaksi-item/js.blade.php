@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            console.log('ready!');


            // $('#modalID').modal('show');

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaksi-item.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'transaksi_id',
                        name: 'name'
                    },
                    {
                        data: 'product_id',
                        name: 'name'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],


            });

            if ($.fn.dataTable.isDataTable('#data-table')) {
                table = $('#data-table').DataTable();
            } else {
                table = $('#data-table').DataTable({
                    "ajax": "{{ route('transaksi-item.index') }}",
                    "columns": [{
                            "data": "transaksi_id"
                        },
                        {
                            "data": "product_id"
                        },
                        {
                            "data": "qty"
                        },
                        {
                            "data": "price"
                        },
                        {
                            "data": "action"
                        },
                    ]
                });
            }

            $('#btnTambah').on('click', function() {
                console.log('click');
                $('#modal-form').trigger("reset");
                $('#form').trigger("reset");
            });



            // Function to calculate total payment
            function calculateTotal() {
                let total = 0;
                // Iterate through each checked product and calculate total
                $('input[name^="produk"]:checked').each(function() {
                    let productId = $(this).val();
                    let qty = parseInt($(`input[name="qty[${productId}]"]`).val()) || 0;
                    let price = parseFloat($(`input[name="qty[${productId}]"]`).closest('tr').find(
                        'td:nth-child(4)').text().replace('Rp', '').replace(/\./g, '').trim()) || 0;

                    // Calculate total price for each selected product
                    total += qty * price;
                });
                // Set the calculated total to the Total Bayar input
                $('#price').val(total.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }));
            }

            // Event listener for qty input change
            $('body').on('input', 'input[name^="qty"]', function() {
                calculateTotal();
            });

            // Event listener for checkbox change (selecting product)
            $('body').on('change', 'input[name^="produk"]', function() {
                calculateTotal();
            });

            // Other existing event listeners and functions...

            $('#form').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                var id = $('#data_id').val();
                var url = "{{ route('transaksi-item.store') }}";
                if (id != '') {
                    formData.append('data_id', id);
                }
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Berhasil',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                table.draw();
                            })
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                table.draw();
                            })
                        }
                        $("#basicModal").removeClass("in");
                        $(".modal-backdrop").remove();
                        $("#basicModal").hide();
                        $('#form').trigger("reset");
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            });

            // Edit event handler
            $('body').on('click', '.edit', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id = $(this).data('id');

                $.get("{{ route('transaksi-item.index') }}" + '/' + id + '/edit', function(data) {
                    $('#data_id').val(id);
                    $('#name').val(data.name);
                    $('#stock').val(data.stock);
                    $('#capital').val(data.capital);
                    $('#sell').val(data.sell);
                })
            });

            // Delete event handler
            $('body').on('click', '.delete', function() {
                var id = $(this).data("id");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('transaksi-item.store') }}" + '/' + id,
                            success: function(data) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Data berhasil dihapus',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    table.draw();
                                })
                            },
                            error: function(data) {
                                console.log(data)
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush
