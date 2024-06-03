@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing') . ' ' . 'Special Codes')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-certificate"></i> Códigos Especiales
    </h1>
    <a href="{{ url('/admin/codes') }}" class="btn btn-success">
        <i class="voyager-plus"></i> Generate Codes
    </a>
    @can('delete', app($dataType->model_name))
        @include('voyager::partials.bulk-delete')
    @endcan
    <a href="{{ route('voyager.export', ['slug' => 'special-codes']) }}" class="btn btn-success">
        <i class="fa fa-file-download"></i> Export to Excel
    </a>
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
