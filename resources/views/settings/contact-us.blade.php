@extends('master')

@section('page_title')
Contact Messages
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h4 class="card-title"><strong> Contact Messages <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Messages / Comments / Suggestion from user's visted your site.">info</i></strong></h4>
                <p class="card-category">Manage your messages here</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="pull-right">
                    <a href="{{ route('settings.contactSmtp', Session::get('subdomain')) }}" class="btn btn-info">Setup SMTP Settings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="tags-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date Received</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contactMessages as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->message }}</td>
                            <td>{{ date_format($message->created_at, 'm/d/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" onclick="reply(this, '{{ Crypt::encrypt($message->id) }}')" class="btn btn-sm btn-default">
                                        <i class="material-icons">reply</i>
                                    </a>
                                    <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($message->id) }}')" class="btn btn-sm btn-default">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </div>
                              
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('settings.contactMessages.delete', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this message? </strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="message_id" id="message_id">
                                <button type="submit" class="btn btn-primary">Yes</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@if($checkSmtp)
    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
        <form action="{{ route('settings.contactMessages.reply', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title subject"></h4>
                                <p class="card-category reply-to"></p>
                            </div>
                            <div class="card-body">
                                <div class="pull-right">
                                    <textarea class="form-control" name="reply" id="editor-reply"></textarea>

                                    <input type="hidden" value="" name="messageId" id="messageId">
                                    <button type="submit" class="btn btn-primary">Yes</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@else
    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"> Please setup first SMTP to able to reply </h4>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/summernote-bs4.js') }}"></script>
    
    <script>
        // CKEDITOR.replace('editor-reply');
        $('[data-toggle="tooltip"]').tooltip()
        $('#editor-reply').summernote({
            minHeight: 350,
            height: 'auto',
            focus: false,
            airMode: false,
            fontNames: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande',
                'Tahoma', 'Times New Roman', 'Verdana'
            ],
            fontNamesIgnoreCheck: [
                'Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande',
                'Tahoma', 'Times New Roman', 'Verdana'
            ],
            dialogsInBody: true,
            dialogsFade: true,
            disableDragAndDrop: false,
            toolbar: [
                // [groupName, [list of button]]
                ['para', ['style', 'ul', 'ol', 'paragraph']],
                ["fontname", ["fontname"]],
                ['fontsize', ['fontsize']],
                ["color", ["color"]],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['height', ['height']],
                ["view", ["fullscreen", "codeview", "help"]],
                ["insert", ["link", "picture"]],
            ],
        });

        $(window).on('load', function(){
            $('input.note-image-input').css('opacity', '1');
            $('input.note-image-input').css('position', 'initial');
        })


        $(document).ready(function() {
            $('#tags-list').DataTable();
        } );

        function confirmDelete(message_id) {
            $('#deleteConfirmation').modal();
            $('#message_id').val(message_id);
        }
        
        function reply(element, messageId) {
            var tr = $(element).parent().parent().parent().find('td');

            $('.reply-to').html('<strong>Reply To: </strong>' + tr.next('td:eq(0)').text());
            $('.subject').html('<strong>Subject </strong>' + tr.next('td:eq(1)').text());
            $('#replyModal').modal();
            $('#messageId').val(messageId);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#message_id').val('');
        });
        
        $("#replyModal").on("hidden.bs.modal", function () {
            $('.reply-to').html('');
            $('.subject').html('');
            $('#messageId').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection
