@if(isset($store->facebookCommentPlugin) && ($store->facebookCommentPlugin->sdk_code !== '' && $store->facebookCommentPlugin->code_snippet !== "") && in_array('facebook comment plugin', $features))
{!! $store->facebookCommentPlugin->sdk_code !!}
@endif