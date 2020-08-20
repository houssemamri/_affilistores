<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\BlogFeed;
use App\BlogFeedAutomation;
use App\Blog;

class BlogAutomationWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:automationweekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate Blog Feeds Weekly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feeds = BlogFeed::all();


        $today = date('Y-m-d');
        $feeds = BlogFeed::all();
        $blogFeeds = BlogFeedAutomation::where('frequency', 'weekly')->whereDate('from', '<=', $today)->whereDate('to', '>=', $today)->get();

        foreach ($blogFeeds as $blogFeed) {
            $this->updateRss($blogFeed->blogFeed);
        }

        // foreach ($feeds as $feed) {
        //     if($this->isDaily($feed)){
        //         $today = date('Y-m-d');

        //         if($feed->automation->from <= $today && $feed->automation->to >= $today){
        //             $this->updateRss($feed);
        //         }
        //     }
        // }
    }

    public function isDaily($feed){
        return $feed->automation->where('frequency', 'weekly') && isset($feed->automation->from) && isset($feed->automation->to) && isset($feed->automation->frequency);
    }

    public function updateRss($feed){
        if(isset($feed)){
            $rss = $this->getRssFeed($feed->url);
            $this->postFeed($rss, $feed->blog_category_id, $feed);
        }
    }

    public function getRssFeed($url){
        try {
            $client = new Client();
            $rss = [];

            // $simpleXml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
            $response = $client->get($url); 
            $simpleXml = simplexml_load_string($response->getBody()->getContents());

            foreach ($simpleXml->children()->channel->item as $item) {
                $search = in_array((string) $item->link, array_column($rss, 'link'));

                if(!$search){
                    array_push($rss, [
                        'title' => (string) $item->title,
                        'description' => (string)$item->description,
                        'link' => (string)$item->link
                    ]);
                }
            }
        } catch(\Exception $e) {
            $rss = [];
        }

        return $rss;
    }

    public function postFeed($blogs, $category, $feed){
        foreach ($blogs as $key => $blog) {

            $blogExist = Blog::where('store_id', $feed->store_id)->where(function($query) use ($blog){
                return $query->orWhere('title', $blog['title'])->orWhere('post', $blog['description']);
            })->count();

            if($blogExist == 0){
                $blog = Blog::create([
                    'store_id' => $feed->store_id,
                    'title' => $blog['title'],
                    'slug' => $this->clean($blog['title']),
                    'post' => $blog['description'],
                    'url' => $blog['link'],
                    'type' => 1,
                    'blog_category_id' => $category,
                    'category_id' => $feed->category_id
                ]);
            }
        }

        return true;
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim(preg_replace('/-+/', '-', $string), '-');
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
 
		return $string;
    }
}
