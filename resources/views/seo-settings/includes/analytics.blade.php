<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table">
                <tbody> 
                    <tr>
                        <th width="30%">Google Analytics Tracking Code <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add your analytics code from Google. Don't have yet? Folllow this link: https://support.google.com/analytics/answer/6086097?hl=en">info</i></th>
                        <td>
                            <textarea name="google_analytics_tracking_code" class="form-control"  cols="1" rows="15">{{ $store->analytics->google_analytics_tracking_code }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Third Party Analytics Tracking Code <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add your analytics code from Third party analytics">info</i></th>
                        <td>
                            <textarea name="third_party_analytics_tracking_code" class="form-control"  cols="1" rows="15">{{ $store->analytics->third_party_analytics_tracking_code }}</textarea>
                        </td>
                    </tr>
                    @if(in_array('facebook pixels', $features))
                    <tr>
                        <th width="30%">Facebook Remarketing Pixel Script <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add your analytics code from Facebook. Don't have yet? Follow this link: https://www.facebook.com/business/a/set-up-facebook-pixel">info</i></th>
                        <td>
                            <textarea name="facebook_remarketing_pixel_script" class="form-control"  cols="1" rows="15">{{ $store->analytics->facebook_remarketing_pixel_script }}</textarea>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th width="30%">Webengage Tracking ID <i class="material-icons tip" data-toggle="tooltip" data-placement="right" title="Add your analytics code from Webengage. Don't have yet? Follow this link: https://docs.webengage.com/docs/web-getting-started">info</i></th>
                        <td>
                            <textarea name="webengage_tracking_id" class="form-control"  cols="1" rows="15">{{ $store->analytics->webengage_tracking_id }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>                    
    </div>
</div>