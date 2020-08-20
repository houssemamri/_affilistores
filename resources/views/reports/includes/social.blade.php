<div class="table-responsive">
    <table class="table table-hover"  id="social-list">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Posted To</th>
                <th>Product Link</th>
                <th>Status</th>
                <th>Post Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($socialReports as $socialReport)
            <tr>
                <td width="20%">{{ $socialReport->product->name }}</td>
                <td width="10%">{{ $socialReport->posted_to }}</td>
                <td width="20%"><a href="{{ $socialReport->link }}" target="_blank">{{ $socialReport->link }}</a></td>
                <td>{!! $socialReport->status == 0 ? "<span class='badge badge-danger'>Failed</span>" : "<span class='badge badge-success'>Success</span>" !!}</td>
                <td>{{ date_format(date_create($socialReport->posted_date), 'm/d/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>