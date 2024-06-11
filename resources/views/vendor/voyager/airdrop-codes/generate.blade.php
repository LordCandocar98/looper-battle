<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Airdrop Codes</title>
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
        <h1>Generate Airdrop Codes</h1>
        <a href="/admin/airdrop-codes" title="Volver" class="btn btn btn-warning pull-right view">
            <i class="voyager-eye"></i> <span class="hidden-xs hidden">Volver</span>
        </a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#generateAirdropCodesModal">
            Generate Airdrop Codes
        </button>
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Modal for Generating Airdrop Codes -->
    <div class="modal fade" id="generateAirdropCodesModal" tabindex="-1" role="dialog" aria-labelledby="generateAirdropCodesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateAirdropCodesModalLabel">Generate Unique Airdrop Codes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="generateAirdropCodesForm" action="{{ route('airdrop-codes.generate') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="value">Value (USD)</label>
                            <input type="number" class="form-control" id="value" name="value" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="total">Total (USD)</label>
                            <input type="text" class="form-control" id="total" name="total" readonly>
                        </div>
                        <div class="form-group">
                            <label for="type">Purchase Type</label>
                            <input type="text" class="form-control" id="type" name="type" value="Airdrop" readonly>
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

            // Function to update the total
            function updateTotal() {
                var quantity = parseFloat($('#quantity').val()) || 0;
                var value = parseFloat($('#value').val()) || 0;
                var total = quantity * value;
                $('#total').val(total.toFixed(2));
            }

            // Attach the updateTotal function to the input events of quantity and value
            $('#quantity, #value').on('input', function() {
                updateTotal();
            });

            // Ensure value is not negative
            $('#value').on('input', function() {
                var value = parseFloat($(this).val());
                if (value < 0) {
                    $(this).val(0);
                }
                updateTotal();
            });
        });
    </script>
</body>
</html>
