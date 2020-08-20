@extends('master')

@section('page_title')
Category Menu Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Category Menu Settings </strong></h4>
        <p class="card-category"> Display categories on your navigation menu </p>
    </div>   
</div>
<div class="row">
    <div class="col-lg-4 col-sm-12">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title"><strong> Categories </strong></h4>
                <p class="card-category"> Select category to add in menu </p>
            </div>   
            <div class="card-body">
                <div class="form-group">
                    <label class="bmd-label-floating"> Select category to add in menu</label>
                    <select class="form-control" name="categories" id="categories">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            @if(!in_array($category->id, $menus))
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title"><strong> Menu </strong></h4>
                <p class="card-category"> Drag to set ordering of menus </p>
            </div>   
            <div class="card-body">
                <form action="{{ route('categoryMenu', ['subdomain' => Session::get('subdomain')]) }}" method="post" id="menu-form">
                    {!! csrf_field() !!}
                    <div class="table-responsive">
                        <table class="table table-fixed">
                            <tbody>
                                @foreach($store->categoryMenu as $menu)
                                    <tr>
                                        <td>
                                            <input type="hidden" value="{{ $menu->category->id }}" name=menu[]> {{ $menu->category->name }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeMenu(this)">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
    <script>
        function removeMenu(element) {
            var input = $(element).parent().parent().find('input');
            var text = input.parent().text();

            $('#categories').append('<option value="'+ input.val() +'">'+ text +'</option>');
            $(element).parent().parent().remove();
        }
        
        $('tbody').sortable();

        $('#categories').on('change', function(){
            var html = '';
            var option = $(this).find(':selected');
            if(option.val()){
                html += '<tr>';
                html +=     '<td>';
                html +=         '<input type="hidden" value="'+ option.val() +'" name=menu[]>' + option.text();
                html +=     '</td>';
                html +=     '<td>';
                html +=         '<button type="button" class="btn btn-sm btn-danger" onclick="removeMenu(this)">Remove</button>';
                html +=     '</td>';
                html += '</tr>';

                $('tbody').append(html);
                $('tbody').sortable();
                option.remove();
            }
        });

        $('.save').on('click', function(event){
            event.preventDefault();

            var menu_count = $('input[name="menu[]"]').length;
            
            if(menu_count > 0 ){
                $("form#menu-form").submit();
            }else{
                $.notify({
                    icon: "error",
                    message: 'Menu cannot be empty',
                },{
                    type: 'danger'
                });
            }
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
