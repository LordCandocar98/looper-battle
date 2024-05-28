@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing') . ' ' . 'Special Codes')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-certificate"></i> Special Codes
    </h1>
    <a href="{{ url('/admin/codes') }}" class="btn btn-success">
        <i class="voyager-plus"></i> Generate Codes
    </a>
    @can('delete', app($dataType->model_name))
        @include('voyager::partials.bulk-delete')
    @endcan
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @include('voyager::bread.browse')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {
            console.log("Document ready!"); // Log para verificar que el documento está listo

            // Initialize Select2 for item selection on modal show
            $('#generateCodesModal').on('show.bs.modal', function() {
                console.log("Modal opened!"); // Log para verificar que el modal se abre

                $('#item_select').select2({
                    ajax: {
                        url: '{{ route('voyager.items.list') }}',
                        dataType: 'json',
                        delay: 250,
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
                    minimumInputLength: 1,
                    dropdownParent: $('#generateCodesModal')
                });
            });
        });
    </script>
@stop

<!-- Modal for Generating Codes -->
{{-- <div class="modal fade" id="generateCodesModal" tabindex="-1" role="dialog" aria-labelledby="generateCodesModalLabel" aria-hidden="true">
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
</div> --}}
