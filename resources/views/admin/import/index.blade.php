@extends('admin.master')

@section('page_title')
Import Products
@endsection

@section('content')
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title"><strong>Import Products </strong></h4>
                    <p class="card-category">Replace existing products base from eCommerce site</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.index') }}" method="POST" enctype="multipart/form-data" id="importForm" novalidate>
                        {!! csrf_field() !!}
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Select eCommerce</th>
                                    <td>
                                        <select name="ecommerce" class="form-control">
                                            <option value="jvzoo" selected>JVzoo</option>
                                            <option value="click_bank">ClickBank</option>
                                            <option value="warrior_plus">Warrior Plus</option>
                                            <option value="pay_dot_com">Pay Dot Com</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Upload Product File </th>
                                    <td>
                                        <input type="file" class="form-control-file" name="product_file" id="file" required>
                                        <br><small class="text-muted">(Maximum of 100MB)</small>
                                        
                                        <div class="progress display-hidden">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 30%">0%</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary btn-save">Save</button>
                        <a href="{{ route('bonuses.index') }}" class="btn btn-danger btn-cancel">Cancel</a>
                        <div class="clearfix"></div>
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title"><strong> Import Logs </strong></h4>
                    <p class="card-category">Logs from importing products</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="log-list">
                            <thead class="">
                                <tr>
                                    <th>Site</th>
                                    <th>Date Imported</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $log->type)) }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('alert')
    @include('extra.alerts')
@endsection

@section('custom-scripts')
    <!-- <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#log-list').DataTable();
        } );
    </script>
    <script>
        // var description = CKEDITOR.replace('editor-description');

        $('select[name=ecommerce]').change(function(){
            $('input[name=product_file]').val('');
        })

        $('input[name=product_file]').change(function(){
            var type = $('select[name=ecommerce]').val();
            var validFile = (type == 'jvzoo' || type == 'warrior_plus') ? 'application/json' : 'text/xml';
            var typeFile = (type == 'jvzoo' || type == 'warrior_plus') ? validFile.replace('application/', '') : validFile.replace('text/', '');

			if(this.files[0].type !== validFile){
                $(this).val('');
                $('.btn-save').attr('disabled', '');
                errorNotify('File type must be ' + typeFile + ' file.');
            }else if(this.files[0].size > 100000000){ //200MB
                $(this).val('');
                $('.btn-save').attr('disabled', '');
                errorNotify('File limit is just 100MB')
            }else{
                $('.btn-save').removeAttr('disabled');
            }
		})

        $('#importForm').submit(function(e){
            e.preventDefault();

            if(!$('input[name=product_file]').val())
                errorNotify('Product File is a required');

            if($('input#file').val()){
                $('.progress').show();
                $('.btn-save').attr('disabled', '');
                $('.btn-cancel').addClass('disabled');
                
                $(this).ajaxSubmit({
                    beforeSubmit: function(){
                        $('.progress-bar').width('30%');
                    },
                    uploadProgress: function(event, position, total, percentComplete){
                        var percent = percentComplete > 30 ? percentComplete : 30;

                        $('.progress-bar').width(percent + '%');

                        var text = (percentComplete == 100) ? 'Inserting to database. Please wait...' : 'Uploading file... ' + percentComplete + '%'
                        
                        $('.progress-bar').text(text);                            
                    },
                    success: function(response){
                        $('.progress').hide();
                        $('.btn-save').removeAttr('disabled');
                        $('.btn-cancel').removeClass('disabled');

                        window.location = '{{ route("import.redirect") }}'
                    },
                    error: function(error){
                        console.log(error);

                        $('.progress').hide();
                        $('.btn-save').removeAttr('disabled');
                        $('.btn-cancel').removeClass('disabled');
                    },
                    resertForm: true
                })

                return false
            }
        })

        function errorNotify(msg){
            $.notify({
                icon: "error",
                message: msg,
            },{
                type: 'danger'
            });
        }
    </script>
@endsection
