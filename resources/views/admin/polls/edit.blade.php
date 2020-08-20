@extends('admin.master')

@section('page_title')
Edit Poll
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Edit Poll </strong></h4>
        <p class="card-category">Update your poll</p>
    </div>
    <div class="card-body">
        <form action="{{ route('polls.edit', $id) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="bmd-label-floating">Question</label>
                        <input type="text" name="question" value="{{ $poll->question }}" class="form-control" >
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                            <option value="1" {{ $poll->status == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{ $poll->status == 0 ? 'selected' : ''}}>Hidden</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="bmd-label-floating">Existing Options</label>
                        @foreach($poll->options as $option)
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" name="existing_option[]" value="{{ $option->name }}" class="form-control" >
                                    <input type="hidden" name="option_id[]" value="{{ $option->id }}" class="form-control" >
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-remove-option" onclick="removeOption(this);">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Options</label>
                        <div class="col-lg-12">
                            <ul class="option-list">
                                
                            </ul>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="option">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary btn-add-option">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('polls.index') }}" class="btn btn-danger">Cancel</a>
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
    <script>
        function remove(btnClose) {
            var option = $(btnClose).closest('li');
            option.remove();
        }

        function removeOption(btnRemove) {
            var option = $(btnRemove).closest('div.form-group');
            option.remove();
        }

        $('.btn-add-option').on('click', function() {
            var option = $('#option');
            if(option.val()){
                var optionHtml = '<li>';
                optionHtml += '<span class="badge badge-warning">'+option.val()+' <span class="remove-option" onclick="remove(this);">X</span>  </span>';
                optionHtml += '<input type="hidden" name="option[]" value="'+option.val()+'">';
                optionHtml += '</li>';
                
                $('.option-list').append(optionHtml);
                $(option).val('');
            }
        })

        
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
