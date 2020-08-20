@extends('admin.master')

@section('page_title')
YouTube API Keys
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> YouTube API Keys </strong></h4>
        <p class="card-category">YouTube API Keys Settings</p>
    </div>
    <div class="card-body">
    <form action="{{ route('settings.youtubekeys') }}" method="POST" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="table-responsive">
            <table class="table table-fixed general-settings">
                <tbody class="api-keys">
                    <tr>
                        <th>
                            <input type="text" class="form-control" placeholder="Enter YouTube API Key" name="dynamic_api_key">
                        </th>
                        <td><button type="button" class="btn btn-default" onclick="add(this)">Add</button></td>
                    </tr>
                    @foreach($keys as $key)
                        <tr>
                            <th>
                                <input type="text" class="form-control" value="{{ $key->api_key }}" name="api_keys[]">
                            </th>
                            <td><button type="button" class="btn btn-danger" onclick="remove(this)">Remove</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <div class="clearfix"></div>
    </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>

    <script>
        function add(element){
            var key = $('input[name=dynamic_api_key]');

            if(key.val() && $('input[value=' + key.val() + ']').length == 0){
                html = '';

                html += '<tr>'
                html += '    <th>'
                html += '        <input type="text" class="form-control" value="' + key.val() + '" name="api_keys[]">'
                html += '    </th>'
                html += '    <td><button type="button" class="btn btn-danger" onclick="remove(this)">Remove</button></td>'
                html += '</tr>'


                $('.api-keys').append(html);
                key.val('');
            }
        }   

       function remove(element){
           $(element).parents('tr').remove();
       }
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection