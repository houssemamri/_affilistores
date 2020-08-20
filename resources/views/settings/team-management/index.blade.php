@extends('master')

@section('page_title')
Team Management
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-primary">
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <h4 class="card-title ">Users</h4>
                <p class="card-category">Manage, assign roles & privileges to users</p>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="pull-right">
                    <a href="{{ route('addUser') }}" class="pull-right btn btn-warning add-new-user">Add New User</a>
                </div>
            </div>
        </div>
    </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="users">
        <div class="table-responsive">
            <table class="table member-table" id="user-list">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Store Manage</th>
                        <th>Access Rights</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                            {{-- <table>
                                    <thead>
                                        <tr>
                                            <th>Store Manage</th>
                                            <th>Access Rights</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->accessRight() as $right)
                                        <tr>
                                            <td>{{ $right->access->name }}</td>
                                            <td>{{ $right->store->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table> --}}
                            <td>
                            </td>
                            <td>
                                <a href="{{ route('editUser', ['id' => Crypt::encrypt($user->id) ]) }}" class="btn btn-success">Edit</a>
                                <a href="#" onclick="confirmDelete('{{ Crypt::encrypt($user->id) }}')" class="btn btn- btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="setupStoreModalTitle" aria-hidden="true" >
    <form action="{{ route('deleteUser') }}" method="POST">
        {!! csrf_field() !!}
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title"><strong> Are you sure to delete this member? </strong></h4>
                            <p class="card-category">Deleting member will remove its access.</p>
                        </div>
                        <div class="card-body">
                            <div class="pull-right">
                                <input type="hidden" value="" name="user_id" id="user_id">
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
@endsection

@section('custom-scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#user-list').DataTable();
        } );
    </script>

    <script>
        function confirmDelete(user_id) {
            $('#deleteConfirmation').modal();
            $('#user_id').val(user_id);
        }
        
        $("#deleteConfirmation").on("hidden.bs.modal", function () {
            $('#user_id').val('');
        });
    </script>
@endsection

@section('alert')
    @include('extra.alerts')
@endsection