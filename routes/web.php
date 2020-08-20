<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['domain' => '{subdomain}.'. env('APP_DOMAIN'), 'middleware' => 'isSubDomain'], function () {
Route::group(['domain' => '{subdomain}.'. env('APP_DOMAIN'), 'middleware' => 'isSubDomain'], function () {
    Route::get('/', 'DashboardController@main')->name('index');
    Route::get('/category/{permalink?}', 'DashboardController@category')->name('index.category');
    Route::get('/product/{permalink?}', 'DashboardController@product')->name('index.product.show');
    Route::get('/customer-service/{policy}', 'DashboardController@customerService')->name('index.customerService');
    Route::get('/sitemap.xml', 'DashboardController@xmlSiteMap')->name('index.xmlSiteMap');
    Route::get('/api/amazon/reviews/{id}', 'Api\AmazonController@getRequestReviewURL')->name('amazon.customerReviews');
    Route::get('/blogs/list', 'DashboardController@blogsAll')->name('index.blogs.list');
    Route::get('/blogs/rss', 'DashboardController@blogRssFeed')->name('index.blogs.rss');
    Route::get('/blogs/{id}', 'DashboardController@blogShow')->name('index.blog.show');
    Route::get('/blog/view/{slug}', 'DashboardController@blogView')->name('index.blog.view');
    Route::get('/social-proofs', 'DashboardController@socialProofs')->name('index.social.proofs');
    Route::get('/amazon/reviews/{id}', 'Api\AmazonController@getRequestReviewURL')->name('amazon.customerReviews');
    Route::get('/search-product', 'DashboardController@search')->name('index.product.search');
    Route::post('/product/review/{permalink?}', 'DashboardController@review')->name('index.product.review');
    Route::post('/send-message', 'DashboardController@sendMessage')->name('index.send.message');
    Route::post('/send-exit-pop', 'DashboardController@exitPopSendEmail')->name('index.send.exitpopemail');
    Route::post('/subscribe', 'DashboardController@subscribe')->name('index.subscribe');
    Route::get('/test-command', 'TestCommandController@handle');
    Route::get('/test-callback', 'TestCommandController@aweberCallback')->name('test.aweber.callback');
    Route::get('/facebook/callback', 'SettingsController@facebookCallback')->name('facebook.callback');
    Route::get('/pinterest/callback', 'SettingsController@pinterestCallback')->name('pinterest.callback');
    Route::get('/test/mails', 'Api\GeneralApiController@testSendMail')->name('test.mails');
    Route::post('/product/page-hit', 'ReportsController@productPageHit')->name('reports.pagehit');
    Route::post('/product/affiliate-hit', 'ReportsController@productAffiliateHit')->name('reports.affiliatehit');
    

    Route::get('/test/zip', function(){
        $params = [
            'q' => 'echo',
        ];
        $tweets = [];

        $results = \Thujohn\Twitter\Facades\Twitter::getSearch($params);

        foreach($results->statuses as $key => $result){
            if ($key == 2) break;

            $tweets[$key] = [
                'tweet_id' => $result->id,
                'content' => $result->text,
                'user_profile_img' => $result->user->profile_image_url,
                'user' => $result->user->name
            ];
        }
        
        dd($tweets);

       
        // $youtubeKeys = \App\YoutubeKey::take(1)->get();
        // foreach ($youtubeKeys as $youtubeKey) {
        //     print_r($youtubeKey->api_key . '<br>');
        //     \Alaouy\Youtube\Facades\Youtube::setApiKey($youtubeKey->api_key);

        //     $params = [
        //         'q'             => 'android',
        //         'type'          => 'video',
        //         'part'          => 'id, snippet',
        //         'maxResults'    => 5
        //     ];
            
        //     try {
        //         $search = \Alaouy\Youtube\Facades\Youtube::searchAdvanced($params, true);
        //     } catch (\Exception $e){
        //         $search = null;
        //     }

        //     if(isset($search)) break;
        // }
        
        // dd($search);
    });
    // Route::get('/login/instagram', function(){
    //     $client_id = "125e78aeebc14ecbb0e0531e08eb0072";
    //     $client_secret = "bef5d0ffdcd7495b8f6d6492bdaccd5a";
    //     $redirect_uri = route('instagram.callback', 'scubba');

    //     $url = "https://api.instagram.com/oauth/authorize/?client_id=" . $client_id . "&redirect_uri=" . $redirect_uri . "&response_type=code";
    //     echo "<a href='" .$url. "'>Login instagram</a>";
    // });

    // Route::get('/instagram/callback', function(Request $request){
    //     $client_id = "125e78aeebc14ecbb0e0531e08eb0072";
    //     $client_secret = "bef5d0ffdcd7495b8f6d6492bdaccd5a";
    //     $redirect_uri = route('instagram.callback', 'scubba');

    //     if(isset($_GET['code'])){
    //         try{
    //             $client = new Client();

    //             $response = $client->request('POST', 
    //                 'https://api.instagram.com/oauth/access_token',
    //                 ['form_params' => [
    //                     'client_id' => $client_id,
    //                     'client_secret' => $client_secret,
    //                     'client_secret' => $client_secret,
    //                     'grant_type' => 'authorization_code',
    //                     'redirect_uri' => $redirect_uri,
    //                     'code' => $_GET['code'],
    //                 ]]
    //             );

    //             $result = json_decode($response->getBody()->getContents());
    //             dd($result);
    //         } catch (\Exception $e) {
    //             dd($e);
    //         }
    //     }else{
    //         dd($_GET['error_description']);
    //     }

    // })->name('instagram.callback');

    // Route::get('/facebook/page', 'SettingsController@facebookPages');
    Route::get('/ebay/getItem/{id}', 'ProductController@ebayGetItemDetails');
    Route::get('/facebook/timeline', 'SettingsController@facebookTimeline');
    
    Route::group(['middleware' => ['auth', 'isOwnedStore'] ], function(){
        Route::get('/membership', 'DashboardController@memberships')->name('memberships');

        Route::get('/redirect', function($subdomain){
            return redirect()->route('dashboard', ['subdomain' => $subdomain]);
        })->name('redirectDashboard');
        
        Route::get('/pages/{slug}', 'DashboardController@pages')->name('noparent.pages');
        
        Route::group(['prefix'=>'user', 'middleware' => 'isMember'], function(){
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::get('/pages/{slug}', 'DashboardController@pages')->name('default.pages');
            Route::get('/poll/{pollId}/{pollOption}', 'DashboardController@vote')->name('poll.vote');
            
            Route::group(['prefix'=>'settings'], function(){
                Route::get('/pages/{slug}', 'DashboardController@pages')->name('settings.custom.pages');
                Route::get('/contact-messages', 'SettingsController@contactMessages')->name('settings.contactMessages');
                Route::match(['GET', 'POST'], '/contact-smtp', 'SettingsController@contactSmtp')->name('settings.contactSmtp');
                Route::match(['GET', 'POST'], '/business-profile', 'SettingsController@businessProfile')->name('settings.businessProfile');
                Route::post('/contact-messages/reply', 'SettingsController@replyContactMessage')->name('settings.contactMessages.reply');
                Route::post('/contact-messages/delete', 'SettingsController@deleteContactMessage')->name('settings.contactMessages.delete');
            
                Route::group(['prefix'=>'affiliate'], function(){
                    Route::get('/', 'SettingsController@affiliate')->name('affiliate');
                    Route::post('/import/settings/', 'SettingsController@importAffiliateSettings')->name('affiliate.import');
                    Route::post('/import/settings/all', 'SettingsController@importAffiliateAllSettings')->name('affiliate.import.all');
                    Route::post('/amazon', 'SettingsController@amazon')->name('amazon');
                    Route::post('/ebay', 'SettingsController@ebay')->name('ebay');
                    Route::post('/aliexpress', 'SettingsController@aliexpress')->name('aliexpress');
                    Route::post('/walmart', 'SettingsController@walmart')->name('walmart');
                    Route::post('/shopcom', 'SettingsController@shopcom')->name('shopcom');
                    Route::post('/cjcom', 'SettingsController@cjcom')->name('cjcom');
                    Route::post('/jvzoo', 'SettingsController@jvzoo')->name('jvzoo');
                    Route::post('/clickbank', 'SettingsController@clickbank')->name('clickbank');
                    Route::post('/warriorplus', 'SettingsController@cjcom')->name('warriorplus');
                    Route::post('/paydotcom', 'SettingsController@cjcom')->name('paydotcom');
                });
    
                Route::group(['prefix'=>'social'], function(){
                    Route::get('/', 'SettingsController@social')->name('social');
                    Route::get('/tweet', 'SettingsController@tweet')->name('tweet');
                    Route::post('/facebook', 'SettingsController@facebook')->name('facebook');
                    // Route::post('/facebook/callback', 'SettingsController@facebook')->name('facebook.xcallback');
                    Route::post('/twitter', 'SettingsController@twitter')->name('twitter');
                    Route::post('/instagram', 'SettingsController@instagram')->name('instagram');
                    Route::post('/tumblr', 'SettingsController@tumblr')->name('tumblr');
                    Route::post('/pinterest', 'SettingsController@pinterest')->name('pinterest');
                });
            });

            Route::group(['prefix'=>'newsletters'], function(){
                Route::get('/', 'SubscribeController@index')->name('newsletters.index');
                Route::get('/subscribers', 'SubscribeController@subscribers')->name('newsletters.subscribers');
                Route::get('/send/{id}', 'SubscribeController@send')->name('newsletters.send');
                Route::match(['GET', 'POST'], '/add', 'SubscribeController@create')->name('newsletters.create');
                Route::post('/subscribers/delete', 'SubscribeController@deleteSubscribers')->name('newsletters.subscribers.delete');
                Route::post('/delete', 'SubscribeController@delete')->name('newsletters.delete');

                Route::group(['prefix'=>'get-reponse-api'], function(){
                    Route::get('/sync', 'SubscribeController@getResponseSync')->name('newsletters.getresponse.sync');
                    Route::match(['GET', 'POST'], '/update', 'SubscribeController@getResponseUpdate')->name('newsletters.getresponse');
                });

                Route::match(['GET', 'POST'], '/autoresponders/{autoresponder}', 'AutoresponderController@create')->name('autoresponder.create');
                Route::get('/autoresponders/aweber/callback', 'AutoresponderController@aweberCallback')->name('aweber.callback');
            });

            Route::group(['middleware' => 'hasAccess'], function(){
                
                Route::group(['prefix'=>'store-design'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('storedesign.custom.pages');

                    Route::match(['GET', 'POST'], '/theme', 'StoreDesignController@theme')->name('theme');
                    Route::match(['GET', 'POST'], '/slider', 'StoreDesignController@slider')->name('slider');
                    Route::match(['GET', 'POST'], '/banner-ad', 'StoreDesignController@bannerAd')->name('bannerAd');
                    Route::post( '/banner-ad/menu', 'StoreDesignController@bannerAdMenu')->name('bannerAdMenu');
                    Route::match(['GET', 'POST'], '/legal-pages', 'StoreDesignController@legalPages')->name('legalPages');
                    Route::match(['GET', 'POST'], '/category-menu', 'StoreDesignController@categoryMenu')->name('categoryMenu');
                    Route::match(['GET', 'POST'], '/smo-settings', 'SettingsController@smo')->name('smo');
                    Route::post('/smo/design-options', 'SettingsController@designOption')->name('designOption');
                    Route::match(['GET', 'POST'], '/footer-settings', 'StoreDesignController@footer')->name('footerSettings');

                    Route::group(['prefix'=>'categories'], function(){
                        Route::get('/', 'CategoryController@index')->name('categories.index');
                        Route::match(['GET', 'POST'], '/add', 'CategoryController@create')->name('categories.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'CategoryController@edit')->name('categories.edit');
                        Route::post('/delete', 'CategoryController@delete')->name('categories.delete');
                    });

                    Route::group(['prefix'=>'clone'], function(){
                        Route::get('/', 'CloneStoreController@index')->name('clone.index');
                        Route::get('/export/{id}', 'CloneStoreController@export')->name('clone.export');
                        Route::match(['GET', 'POST'], '/import/{id}', 'CloneStoreController@import')->name('clone.import');
                    });
                });

                Route::group(['prefix'=>'products'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('products.custom.pages');
                    Route::get('/', 'ProductController@index')->name('products.index');
                    // Route::get('/ebay', 'Api\EbayController@search')->name('products.ebay');
                    Route::match(['GET', 'POST'], '/create/{source}', 'ProductController@create')->name('products.create');
                    Route::match(['GET', 'POST'], '/edit/{id}', 'ProductController@edit')->name('products.edit');
                    Route::match(['GET', 'POST'], '/amazon/add/manual', 'ProductController@createAmazonManually')->name('products.create.amazon');
                    Route::get('/reviews/{id}', 'ProductController@reviews')->name('products.reviews');
                    Route::get('/reviews/{id}/{status}', 'ProductController@apporoveDisapprove')->name('products.reviews.approveDisapprove');
                    Route::post('/delete', 'ProductController@delete')->name('products.delete');
                    Route::post('/delete/multiple', 'ProductController@deleteMultiple')->name('products.delete.multiple');
                    Route::post('/delete/image', 'ProductController@deleteImage')->name('products.image.delete');

                    Route::group(['prefix'=>'tags'], function($subdomain){
                        Route::get('/', 'TagController@index')->name('tags.index');
                        Route::match(['GET', 'POST'], '/add', 'TagController@create')->name('tags.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'TagController@edit')->name('tags.edit');
                        Route::post('/delete', 'TagController@delete')->name('tags.delete');
                    });

                    Route::group(['prefix'=>'automation'], function(){
                        Route::get('/pages/{slug}', 'DashboardController@pages')->name('automation.custom.pages');
    
                        Route::get('/', 'AutomationController@index')->name('automation.index');
                        Route::match(['GET', 'POST'], '/add', 'AutomationController@create')->name('automation.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'AutomationController@edit')->name('automation.edit');
                        Route::post('/delete', 'AutomationController@delete')->name('automation.delete');
                    });
                });

                Route::group(['prefix'=>'articles'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('articles.custom.pages');
                
                    Route::get('/', 'DashboardController@articles')->name('articles.list');
                    Route::get('/{slug}', 'DashboardController@articlesRead')->name('articles.read');
                });

                Route::group(['prefix'=>'get-traffic'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('gettraffic.custom.pages');

                    Route::group(['prefix'=>'seo'], function(){
                        Route::match(['GET', 'POST'], '/', 'SeoSettingsController@index')->name('seo.index');
                        Route::get('/sitemap', 'SeoSettingsController@xmlSiteMap')->name('seo.sitemap');
                        Route::get('/rss-feed/{category?}', 'SeoSettingsController@rssfeed')->name('seo.rssfeed');
                    });

                    Route::group(['prefix'=>'social'], function(){
                        Route::get('/', 'SocialController@index')->name('social.index');
                        Route::match(['GET', 'POST'], '/add', 'SocialController@create')->name('social.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'SocialController@edit')->name('social.edit');
                        Route::post('/delete', 'SocialController@delete')->name('social.delete');
                    });

                    Route::group(['prefix'=>'blogs'], function(){
                        Route::get('/pages/{slug}', 'DashboardController@pages')->name('blogs.custom.pages');
    
                        Route::get('/', 'BlogController@blogs')->name('blogs.index');
                        Route::get('/publish/{id}', 'BlogController@publish')->name('blogs.publish');
                        Route::get('/unpublish/{id}', 'BlogController@unpublish')->name('blogs.unpublish');
                        Route::match(['GET', 'POST'], '/add', 'BlogController@blogCreate')->name('blogs.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'BlogController@blogUpdate')->name('blogs.edit');
                        Route::post('/delete', 'BlogController@blogDelete')->name('blogs.delete');
    
                        Route::group(['prefix'=>'feeds'], function(){
                            Route::get('/', 'BlogController@feeds')->name('blogs.feeds.index');
                            Route::get('/update/{id}', 'BlogController@updateRss')->name('blogs.feeds.update');
                            Route::match(['GET', 'POST'], '/add', 'BlogController@feedCreate')->name('blogs.feeds.create');
                            Route::match(['GET', 'POST'], '/edit/{id}', 'BlogController@feedUpdate')->name('blogs.feeds.edit');
                            Route::post('/delete', 'BlogController@feedDelete')->name('blogs.feeds.delete');
                        });
    
                        Route::group(['prefix'=>'categories'], function(){
                            Route::get('/', 'BlogController@categories')->name('blogs.categories.index');
                            Route::match(['GET', 'POST'], '/add', 'BlogController@categoryCreate')->name('blogs.categories.create');
                            Route::match(['GET', 'POST'], '/edit/{id}', 'BlogController@categoryUpdate')->name('blogs.categories.edit');
                            Route::post('/delete', 'BlogController@categoryDelete')->name('blogs.categories.delete');
                        });
                    });

                    Route::group(['prefix'=>'facebook-group-finder'], function(){
                        Route::get('/', 'FacebookGroupController@index')->name('fbgroupfinder.index');
                        Route::match(['GET', 'POST'], '/add', 'FacebookGroupController@create')->name('fbgroupfinder.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'FacebookGroupController@edit')->name('fbgroupfinder.edit');
                        Route::post('/delete', 'FacebookGroupController@delete')->name('fbgroupfinder.delete');
                    });

                    Route::group(['prefix'=>'pinger-service'], function(){
                        Route::match(['GET', 'POST'], '/', 'PingerController@index')->name('pinger.index');
                        Route::get('/products/{id}', 'PingerController@getProducts')->name('pinger.products');
                    });
                });

                Route::group(['prefix' => 'increase-conversions'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('conversions.custom.pages');

                    Route::match(['GET', 'POST'], '/facebook-customer-chat', 'IncreaseConversionController@facebookCustomerChat')->name('conversions.customerChat');
                    Route::match(['GET', 'POST'], '/facebook-comment-plugin', 'IncreaseConversionController@facebookCommentPlugin')->name('conversions.commentPlugin');
                    
                    Route::group(['prefix' => 'social-proof'], function(){
                        Route::get('/', 'SocialProofController@index')->name('socialProof.index');
                        Route::get('/get-new', 'SocialProofController@getNewSocialProofs')->name('socialProof.getNew');
                        Route::get('/display/{id}', 'SocialProofController@display')->name('socialProof.display');
                        Route::get('/hide/{id}', 'SocialProofController@hide')->name('socialProof.hide');
                        Route::get('/display-order/{type}', 'SocialProofController@randomOrder')->name('socialProof.display.randomOrder');
                        Route::match(['GET', 'POST'],'/add', 'SocialProofController@create')->name('socialProof.create');
                        Route::match(['GET', 'POST'],'/edit/{id}', 'SocialProofController@edit')->name('socialProof.edit');
                        Route::post('/display-hide', 'SocialProofController@displayHideSocialProofs')->name('socialProof.display-hide');
                        Route::post('/delete', 'SocialProofController@delete')->name('socialProof.delete');
                        Route::post('/delete/multiple', 'SocialProofController@deleteMultiple')->name('socialProof.delete.multiple');
                        Route::post('/order', 'SocialProofController@orderSocialProof')->name('socialProof.orderSocialProof');
                    });   

                    Route::group(['prefix' => 'exit-pops'], function(){
                        Route::get('/', 'IncreaseConversionController@exitPops')->name('exitpops.index');
                        Route::match(['GET', 'POST'], '/add', 'IncreaseConversionController@exitPopsCreate')->name('exitpops.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'IncreaseConversionController@exitPopsUpdate')->name('exitpops.edit');
                        Route::post('/delete', 'IncreaseConversionController@exitPopsDelete')->name('exitpops.delete');
                    });    
                    
                    Route::group(['prefix' => 'countdown-timers'], function(){
                        Route::get('/', 'CountdownController@index')->name('countdowns.index');
                        Route::match(['GET', 'POST'], '/add', 'CountdownController@create')->name('countdowns.create');
                        Route::match(['GET', 'POST'], '/edit/{id}', 'CountdownController@edit')->name('countdowns.edit');
                        Route::post('/delete', 'CountdownController@delete')->name('countdowns.delete');
                    });  
                });

                Route::group(['prefix' => 'reports'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('reports.custom.pages');
                    
                    Route::get('/', 'ReportsController@index')->name('reports.index');
                });

                Route::group(['prefix' => 'bonuses'], function(){
                    Route::get('/pages/{slug}', 'DashboardController@pages')->name('bonuses.custom.pages');

                    Route::get('/', 'BonusesController@index')->name('bonus.index');
                    Route::get('/{id}', 'BonusesController@show')->name('bonus.show');
                    Route::get('/ecover/{name}/{id}', 'BonusesController@ecoverCreate')->name('bonus.ecover');
                    Route::get('/sgp', 'BonusesController@sgp')->name('bonus.sgp');
                });
            });

            Route::group(['prefix' => 'api'], function(){
                Route::get('/{category}/products', 'Api\GeneralApiController@getProducts')->name('get.products');
                Route::get('/{id}/product', 'Api\GeneralApiController@getProduct')->name('get.product');
                
                Route::group(['prefix' => 'reports'], function(){
                    Route::get('/custom-date/{startDate}/{endDate}', 'ReportsController@customPeriodPosts')->name('reports.customDate');
                    Route::get('/custom-date/hits/{startDate}/{endDate}', 'ReportsController@customPeriodHits')->name('reports.customDateHits');
                });

                Route::group(['prefix' => 'amazon'], function(){
                    Route::post('/search', 'Api\AmazonController@search')->name('amazon.search');
                });
                
                Route::group(['prefix' => 'aliexpress'], function(){
                    Route::post('/search', 'Api\AliExpressController@search')->name('aliexpress.search');
                });

                Route::group(['prefix' => 'ebay'], function(){
                    Route::post('/search', 'Api\EbayController@search')->name('ebay.search');
                    Route::post('/item', 'Api\EbayController@getItemDetails')->name('ebay.item');
                });

                Route::group(['prefix' => 'walmart'], function(){
                    Route::post('/search', 'Api\WalmartController@search')->name('walmart.search');
                });

                Route::group(['prefix' => 'shop-com'], function(){
                    Route::post('/search', 'Api\ShopComController@search')->name('shopcom.search');
                });

                Route::group(['prefix' => 'cj-com'], function(){
                    Route::post('/search', 'Api\CjComController@search')->name('cjcom.search');
                });

                Route::group(['prefix' => 'jvzoo'], function(){
                    Route::post('/search', 'Api\JvzooController@search')->name('jvzoo.search');
                });

                Route::group(['prefix' => 'clickbank'], function(){
                    Route::post('/search', 'Api\ClickBankController@search')->name('clickbank.search');
                });

                Route::group(['prefix' => 'warriorplus'], function(){
                    Route::post('/search', 'Api\WarriorPlusController@search')->name('warriorplus.search');
                });

                Route::group(['prefix' => 'paydotcom'], function(){
                    Route::post('/search', 'Api\PayDotComController@search')->name('paydotcom.search');
                });
            });
        });
    });
});

Route::group(['domain' => env('APP_DOMAIN')], function () {
    Route::get('/', 'IndexController@index')->name('main.index');
    Route::get('/store-closed/{id}', 'IndexController@storeClose')->name('main.index.store.close');
    Route::get('/privacy-policy', 'IndexController@privacy')->name('main.privacy');
    Route::get('/terms-of-service', 'IndexController@terms')->name('main.terms');
    Route::match(['GET', 'POST'], '/' . env('ADMIN_ROUTE') . '/login', 'Auth\AuthController@adminLogin')->name('admin.login');
    Route::match(['GET', 'POST'], '/subadmin/login', 'Auth\AuthController@subAdminLogin')->name('subadmin.login');
    Route::match(['GET', 'POST'], '/user/login', 'Auth\AuthController@login')->name('login');
    Route::match(['GET', 'POST'], '/review', 'Auth\AuthController@review')->name('review');
    Route::match(['GET', 'POST'], '/logout', 'Auth\AuthController@logout')->name('logout');
    Route::match(['GET', 'POST'], '/reset-password', 'Auth\AuthController@resetPassword')->name('resetPassword');
    Route::get('/test/button', function(){
        $pop = '|PH|paulerickcampos24@gmail.com|Paul Erick Campos||303035|testing|STANDARD|SALE|0|0.50|PYPL|98N16550E64945332|1528347791|1001189|||';
        if ('UTF-8' != mb_detect_encoding($pop)) {
            $pop = mb_convert_encoding($pop, "UTF-8");
        }

        $calcedVerify = sha1($pop);
        $calcedVerify = strtoupper(substr($calcedVerify,0,8));
        dd($calcedVerify);        
        // echo '<a href="https://www.jvzoo.com/b/0/303035/3"><img src="https://i.jvzoo.com/0/303035/3" alt="testing" border="0" /></a>';
    });
    
    Route::group(['middleware' => 'auth'], function(){
        //Admin Route
        Route::group(['prefix'=> env('ADMIN_ROUTE'), 'middleware' => 'isAdmin'], function(){
            Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');

            Route::group(['prefix'=>'stores'], function(){
                Route::get('/', 'Admin\StoreController@index')->name('admin.store.index');
                Route::post('/getStores', 'Admin\StoreController@getStores')->name('admin.store.list');
                Route::get('/open-close/{id}/{status}', 'Admin\StoreController@changeStatus')->name('admin.store.change.status');
            });

            Route::group(['prefix'=>'users'], function(){
                Route::get('/', 'Admin\UserController@index')->name('users.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\UserController@create')->name('users.add');
            });

            Route::group(['prefix'=>'members'], function(){
                Route::get('/', 'Admin\MemberController@index')->name('members.index');
                Route::get('/ipn', 'Admin\MemberController@ipnList')->name('members.ipn');
                Route::get('/status/{id}/{status}', 'Admin\MemberController@changeStatus')->name('members.changeStatus');
                Route::match(['GET', 'POST'], '/add', 'Admin\MemberController@create')->name('members.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\MemberController@edit')->name('members.edit');
                Route::post('/delete', 'Admin\MemberController@delete')->name('members.delete');
            });

            Route::group(['prefix'=>'memberships'], function(){
                Route::get('/', 'Admin\MembershipController@index')->name('memberships.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\MembershipController@create')->name('memberships.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\MembershipController@edit')->name('memberships.edit');
                Route::post('/delete', 'Admin\MembershipController@delete')->name('memberships.delete');
            });

            Route::group(['prefix'=>'pages'], function(){
                Route::get('/', 'Admin\PageController@index')->name('pages.index');
                Route::match(['GET', 'POST'], '/add/{type}', 'Admin\PageController@create')->name('pages.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\PageController@edit')->name('pages.edit');
                Route::post('/delete', 'Admin\PageController@delete')->name('pages.delete');
                Route::post('/set-ordering', 'Admin\PageController@setOrdering')->name('pages.set-ordering');
            });

            Route::group(['prefix'=>'articles'], function(){
                Route::get('/', 'Admin\ArticleController@index')->name('articles.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\ArticleController@create')->name('articles.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\ArticleController@edit')->name('articles.edit');
                Route::post('/delete', 'Admin\ArticleController@delete')->name('articles.delete');
            });

            Route::group(['prefix'=>'bonuses'], function(){
                Route::get('/', 'Admin\BonusController@index')->name('bonuses.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\BonusController@create')->name('bonuses.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\BonusController@edit')->name('bonuses.edit');
                Route::post('/delete', 'Admin\BonusController@delete')->name('bonuses.delete');
            });

            Route::group(['prefix'=>'polls'], function(){
                Route::get('/', 'Admin\PollController@index')->name('polls.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\PollController@create')->name('polls.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\PollController@edit')->name('polls.edit');
                Route::post('/delete', 'Admin\PollController@delete')->name('polls.delete');
            });

            Route::group(['prefix'=>'instructions'], function(){
                Route::get('/', 'Admin\InstructionController@index')->name('instructions.index');
                Route::match(['GET', 'POST'], '/add', 'Admin\InstructionController@create')->name('instructions.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\InstructionController@edit')->name('instructions.edit');
                Route::post('/delete', 'Admin\InstructionController@delete')->name('instructions.delete');
            });

            Route::group(['prefix'=>'settings'], function(){
                Route::match(['GET', 'POST'], '/', 'Admin\SettingController@general')->name('settings.general');
                Route::match(['GET', 'POST'], '/menu', 'Admin\SettingController@menu')->name('settings.menu');
                Route::match(['GET', 'POST'], '/email-responder', 'Admin\SettingController@emailResponder')->name('settings.emailResponder');
                Route::match(['GET', 'POST'], '/youtube-api-keys', 'Admin\SettingController@youtubeKeys')->name('settings.youtubekeys');
                
                Route::group(['prefix'=>'notification'], function(){
                    Route::get('/', 'Admin\SettingController@notification')->name('settings.notification.index');
                    Route::match(['GET', 'POST'], '/add', 'Admin\SettingController@noificationCreate')->name('settings.notification.add');
                    Route::match(['GET', 'POST'], '/edit/{id}', 'Admin\SettingController@notificationEdit')->name('settings.notification.edit');
                    Route::post('/delete', 'Admin\SettingController@notificationDelete')->name('settings.notification.delete');
                });
            });

            Route::group(['prefix'=>'import'], function(){
                Route::get('/redirect', 'Admin\ImportController@importRedirect')->name('import.redirect');
                Route::match(['GET', 'POST'], '/', 'Admin\ImportController@index')->name('import.index');
            });

            Route::group(['prefix'=>'profile'], function(){
                Route::get('/', 'Admin\ProfileController@index')->name('admin.profile');
                Route::post('/update', 'Admin\ProfileController@updateProfile')->name('admin.updateProfile');
                Route::post('/password/update', 'Admin\ProfileController@updatePassword')->name('admin.updatePassword');
            });
        });

        Route::group(['prefix'=> '/subadmin', 'middleware' => 'isSubadmin'], function(){
            Route::get('/', 'Subadmin\DashboardController@index')->name('subadmin.dashboard');

            Route::group(['prefix'=>'profile'], function(){
                Route::get('/', 'Subadmin\ProfileController@index')->name('subadmin.profile');
                Route::post('/update', 'Subadmin\ProfileController@updateProfile')->name('subadmin.updateProfile');
                Route::post('/password/update', 'Subadmin\ProfileController@updatePassword')->name('subadmin.updatePassword');
            });

            Route::group(['prefix'=>'members'], function(){
                Route::get('/', 'Subadmin\MemberController@index')->name('subadmin.members.index');
                Route::get('/ipn', 'Subadmin\MemberController@ipnList')->name('subadmin.members.ipn');
                Route::get('/status/{id}/{status}', 'Subadmin\MemberController@changeStatus')->name('subadmin.members.changeStatus');
                Route::match(['GET', 'POST'], '/add', 'Subadmin\MemberController@create')->name('subadmin.members.add');
                Route::match(['GET', 'POST'], '/edit/{id}', 'Subadmin\MemberController@edit')->name('subadmin.members.edit');
                Route::post('/delete', 'Subadmin\MemberController@delete')->name('subadmin.members.delete');
            });

        });

        //Members Route
        Route::group(['prefix'=>'user', 'middleware' => ['auth', 'isMember']], function(){
            Route::get('/', function(){
                Session::flash('error', 'Please select a store to manage on');
                return redirect()->route('listStore');
            })->name('storeSelector');
            
            Route::get('/redirect', function(){
                Session::flash('error', 'Please select a store to manage on');
                return redirect()->route('listStore');
            })->name('redirectListStore');

            Route::get('/store-own-domain', 'StoreController@storeOwnDomain')->name('store.owndomain');

            Route::group(['prefix'=>'affiliate-store'], function(){
                Route::get('/', 'StoreController@listStore')->name('listStore');
                Route::get('/create/first/store/', 'StoreController@createFirstStore')->name('createFirstStore');
                Route::get('/status/{id}/{status}', 'StoreController@changeStatus')->name('store.changeStatus');
                Route::post('/edit', 'StoreController@editStore')->name('editStore');
                Route::post('/delete', 'StoreController@deleteStore')->name('deleteStore');
                Route::match(['GET', 'POST'], '/create', 'StoreController@createStore')->name('createStore');
            });
        });

        // Route::group(['prefix' => 'settings'], function(){
        //     Route::group(['prefix' => 'team-management'], function(){
        //         Route::get('/', 'TeamManagementController@index')->name('teamManagement');
        //         Route::match(['GET', 'POST'], '/add', 'TeamManagementController@addUser')->name('addUser');
        //         Route::match(['GET', 'POST'], '/edit/{id}', 'TeamManagementController@editUser')->name('editUser');
        //         Route::post('/delete', 'TeamManagementController@deleteUser')->name('deleteUser');
        //     });
        // });
     
        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::post('/profile/update', 'ProfileController@updateProfile')->name('updateProfile');
        Route::post('/password/update', 'ProfileController@updatePassword')->name('updatePassword');
        Route::get('/notifications', 'DashboardController@notifications')->name('index.notifications');
        Route::get('/notifications/{id}', 'DashboardController@notificationsOpen')->name('index.notifications.show');
    });
});

Route::group(['prefix' => 'installation'], function(){
    Route::match(['GET', 'POST'], '/', 'InstallationController@stepOne')->name('installation.stepOne');
    Route::match(['GET', 'POST'], '/step-two', 'InstallationController@stepTwo')->name('installation.stepTwo');
    Route::match(['GET', 'POST'], '/step-two-point-one', 'InstallationController@stepTwoPointOne')->name('installation.stepTwoPointOne');
    Route::match(['GET', 'POST'], '/step-two-point-two', 'InstallationController@stepTwoPointTwo')->name('installation.stepTwoPointTwo');
    Route::match(['GET', 'POST'], '/step-three', 'InstallationController@stepThree')->name('installation.stepThree');
});
