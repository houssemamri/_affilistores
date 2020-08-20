@extends('master')

@section('page_title')
Find related FB groups
@endsection

@section('content')
<div class="card">
  <div class="card-header card-header-primary">
    <div class="nav-tabs-navigation">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="nav-item">
                <a class="nav-link active show" href="#findnew" data-toggle="tab">
                    Find New Potential
                    <div class="ripple-container"></div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#viewgroup" data-toggle="tab">
                    View Group
                    <div class="ripple-container"></div>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade show active" id="amazofindnewn">
        <form action="{{ route('amazon', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
           {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <select name="action" class="form-control mr-2">
                                        <option>Action</option>
                                        <option value="add_promotion">Add Promotion</option>
                                    </select>
                                </span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <input type="text" name="keyword" placeholder="Enter Keyword" value="" class="form-control">
                                    <button class="btn btn-primary">Search</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <div class="clearfix"></div>
        </form>
      </div>
      <div class="tab-pane fade" id="viewgroup">
        <form action="{{ route('ebay', ['subdomain' => Session::get('subdomain')]) }}" method="POST">
          {!! csrf_field() !!}
          <div class="row">
              <div class="col-lg-6">
                  <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <select name="action" class="form-control mr-2">
                                        <option>Action</option>
                                        <option value="add_promotion">Add Promotion</option>
                                    </select>
                                </span>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <input type="text" name="keyword" placeholder="Enter Keyword" value="" class="form-control">
                                    <button class="btn btn-primary">Search</button>
                                </span>
                            </div>
                        </div>
                    </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-fixed text-center" id="category-list">
                            <thead class="">
                                <tr>
                                    <th> </th>
                                    <th>Group Name</th>
                                    <th>Image</th>
                                    <th>Group Id</th>
                                    <th>Privacy</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
              </div>
          </div>

          <button type="submit" class="btn btn-primary">Save</button>
          <div class="clearfix"></div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('alert')
  @include('extra.alerts')
@endsection