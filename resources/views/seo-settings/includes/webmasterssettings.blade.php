<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th colspan=3 class="text-info text-uppercase"><h4><strong>Webmaster Verifications</h4></strong></th>
                    </tr>
                    <tr>
                        <th width="20%">Google </th>
                        <td>
                            <input type="text" name="google_verification_code" placeholder="Enter Google Verification Meta" value="{{ $store->webmasterSettings->google_verification_code }}" class="form-control">
                        </td>
                        <td width="40%" class="text-center">
                            <h5>Enter the meta tag given by Google Webmaster Tools</h5>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Bing </th>
                        <td>
                            <input type="text" name="bing_verification_code" placeholder="Enter Bing Verification Meta" value="{{ $store->webmasterSettings->bing_verification_code }}" class="form-control">
                        </td>
                        <td width="40%" class="text-center">
                            <h5>Enter the meta tag given by Bing Webmaster Tools</h5>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Pinterest</th>
                        <td>
                            <input type="text" name="pinterest_verification_code" value="{{ $store->webmasterSettings->pinterest_verification_code }}" placeholder="Enter Pinterest Verification Meta" class="form-control">
                        </td>
                        <td width="40%" class="text-center">
                            <h5>Enter the meta tag given by Pinterest Webmaster Tools</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>                    
    </div>
</div>