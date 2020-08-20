@if(isset($store->facebookChatSupport) && (isset($store->facebookChatSupport->code) && $store->facebookChatSupport->code !== "") && in_array('facebook customer messenger bot', $features))
{!! $store->facebookChatSupport->code !!}
@endif