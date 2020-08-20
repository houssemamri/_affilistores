
@extends('master')

@section('page_title')
Edit Social Proof
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong>Edit Social Proof </strong></h4>
        <p class="card-category">Edit your social proof</p>
    </div>
    <div class="card-body">
        <form action="{{  route('socialProof.edit', ['subdomain' => Session::get('subdomain'), $id]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="20%"><strong>Image <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Upload social proof image. Standard image size is 100px x 100px">info</i></strong></th>
                                    <td>
                                        <input type="file" name="image" accept="image/*" class="form-control-file" id="image">
                                        <p class="text-muted">Standard image size is 100px x 100px</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Title <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add title of your social proof. Maximum length 80 characters">info</i></strong></th>
                                    <td>
                                        <input type="text" name="title" maxlength="80" placeholder="Enter Social Proof Title" value="{{ $proofData->title }}" class="form-control" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Content <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add content for your social proof. Maximum length 200 characters">info</i></strong></th>
                                    <td>
                                        <textarea name="content" maxlength="200" id="" class="form-control" rows="5" required>{{ $proofData->content }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%"><strong>Link <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Set link of your social proof">info</i></strong></th>
                                    <td>
                                        <input type="url" name="link" placeholder="Enter Social Proof Link" class="form-control" value="{{ $proofData->url }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Display: <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Check if you want to display this social proof">info</i></th>
                                    <th>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="display" type="checkbox" {{ $proof->active == 1 ? 'checked' : ''}}>
                                                Check to display this social proof
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong>Settings</strong></h4>
                            <p class="card-category">Configure settings for your social proof</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th width="20%"><strong>Display Time <i class="material-icons tip" data-toggle="tooltip" min="1" data-placement="right" title="Set how long social proof will be displayed in seconds">info</i></strong></th>
                                            <td>
                                                <input type="number" name="display_time" value="{{ $proofData->settings->display_time }}" placeholder="Enter Display Time (seconds)" class="form-control" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="20%"><strong>Time Difference<i class="material-icons tip" data-toggle="tooltip" min="1" data-placement="right" title="Set how long for the next social proof will be displayed in seconds">info</i></strong></th>
                                            <td>
                                                <input type="number" name="time_difference" value="{{ $proofData->settings->time_difference }}" placeholder="Enter Time Difference (seconds)" class="form-control" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{  route('socialProof.index', ['subdomain' => Session::get('subdomain')]) }}" class="btn btn-danger">Cancel</a>
                <div class="clearfix"></div>
            </div>
            
        </form>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>
@endsection