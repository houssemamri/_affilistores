@extends('admin.master')

@section('page_title')
Member Menu
@endsection

@section('content')
<div class="card">
    <form action="{{ route('settings.menu') }}" method="post">
        {!! csrf_field() !!}
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="card-title"><strong> Menu </strong></h4>
                    <p class="card-category">Manage your member's menu</p>
                </div>
                <div class="col-lg-4">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" >
                    <thead class="">
                        <tr>
                            <th>Slug</th>
                            <th>Icon</th>
                            <th>Title</th>
                            <th class="text-center">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->slug }}</td>
                            <td><input class="form-control" type="text" name="icon[]" value="{{ $menu->icon }}"></td>
                            <td>
                                <input class="form-control" type="text" name="title[]" value="{{ $menu->title }}">
                                <input type="hidden" name="id[]" value="{{ Crypt::encrypt($menu->id) }}">
                            </td>
                            <td class="text-center">{{ $menu->order }}</td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

 <!-- Modal -->
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script> 
    <script>
    $('tbody').sortable();
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
