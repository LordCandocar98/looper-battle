<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Codes</title>
    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Incluye Bootstrap para el modal -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Generate Special Codes</h1>
        <a href="/admin/special-codes" title="Volver" class="btn btn btn-warning pull-right view">
            <i class="voyager-eye"></i> <span class="hidden-xs hidden">Volver</span>
        </a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#generateCodesModal">
            Generate Codes
        </button>
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Modal for Generating Codes -->
    <div class="modal fade" id="generateCodesModal" tabindex="-1" role="dialog" aria-labelledby="generateCodesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateCodesModalLabel">Generate Unique Codes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="generateCodesForm" action="{{ route('special-codes.generate') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="value">Value</label>
                            <input type="number" class="form-control" id="value" name="value" required>
                        </div>
                        <div class="form-group">
                            <label for="item_select">Item</label>
                            <select class="form-control" id="item_select" name="item_id" required></select>
                        </div>
                        <div class="form-group">
                            <label for="type">Purchase Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="1">Reward</option>
                                <option value="2">Store Purchase</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log("Document ready!"); // Log para verificar que el documento está listo

            // Initialize Select2 for item selection on modal show
            $('#generateCodesModal').on('show.bs.modal', function() {
                console.log("Modal opened!"); // Log para verificar que el modal se abre

                $('#item_select').select2({
                    width: 'resolve',
                    ajax: {
                        url: '{{ route('items.list') }}',
                        dataType: 'json',
                        processResults: function(data) {
                            console.log(data); // Log para verificar los datos recibidos
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                    dropdownParent: $('#generateCodesModal'),
                    // dropdownAutoWidth: true
                    width: '100%'
                });
            });
        });
    </script>
</body>
</html>
