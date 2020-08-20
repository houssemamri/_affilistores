@extends('master')

@section('page_title')
Footer Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> Footer Settings </strong></h4>
        <p class="card-category">Update footer of your store</p>
    </div>
    <div class="card-body">
        <form action="{{  route('footerSettings', ['subdomain' => Session::get('subdomain')]) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>About your store <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter information about your store to be displayed on footer section">info</i></th>
                            <td>
                                <textarea name="about" id="" rows="5" class="form-control">{{ $about }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Newsletter Heading <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Newsletter Heading for footer subscribe form">info</i></strong></th>
                            <td>
                                <input type="text" name="newsletterHeading" class="form-control" value="{{ $newsletter->heading }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Newsletter Text <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter Newsletter Text for footer subscribe form">info</i></strong></th>
                            <td>
                                <input type="text" name="newsletterText" class="form-control" value="{{ $newsletter->text }}">
                            </td>
                        </tr>
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
    <script>
        $('[data-toggle="tooltip"]').tooltip()
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
