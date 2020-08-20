@extends('master')

@section('page_title')
SMTP Settings
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title"><strong> SMTP Settings </strong></h4>
        <p class="card-category">Update SMTP settings to able to reply</p>
    </div>
    <div class="card-body">
        <form action="{{  route('settings.contactSmtp', Session::get('subdomain')) }}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Mail Host <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter mail host of mail server. Use settings for outgoing mail.">info</i></th>
                            <td>
                                <input type="text" name="mail_host" class="form-control" value="{{ isset($smtp->host) ? $smtp->host : '' }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Mail Port <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter mail SSL port of mail server. Use settings for outgoing mail. ">info</i></strong></th>
                            <td>
                                <input type="text" name="mail_port" class="form-control" value="{{ isset($smtp->port) ? $smtp->port : '465' }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Mail Username <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter mail username of mail server">info</i></strong></th>
                            <td>
                                <input type="text" name="mail_username" class="form-control" value="{{ isset($smtp->username) ? $smtp->username : '' }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Mail Password <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Enter mail password of mail server">info</i></strong></th>
                            <td>
                                <input type="text" name="mail_password" class="form-control" value="{{ isset($smtp->password) ? $smtp->password : '' }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="20%"><strong>Mail Encryption <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Use SSL encryption of mail server">info</i></strong></th>
                            <td>
                                <select name="mail_ecnryption" class="form-control" readonly>
                                    <option value="ssl">SSL</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('settings.contactMessages', Session::get('subdomain')) }}" class="btn btn-danger">Cancel</a>
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
