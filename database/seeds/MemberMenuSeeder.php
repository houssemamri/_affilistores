<?php

use Illuminate\Database\Seeder;
use App\MemberMenu;

class MemberMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus =  [
            (object)[
                "title" => "Dashboard",
                "slug" => "dashboard",
                "icon" => "dashboard",
                "order" => "1"
            ],
            (object)[
                "title" => "Store Design",
                "slug" => "store-design",
                "icon" => "shopping_basket",
                "order" => "2"
            ],
            (object)[
                "title" => "Products",
                "slug" => "products",
                "icon" => "shopping_cart",
                "order" => "3"
            ],
            (object)[
                "title" => "Blogs",
                "slug" => "blogs",
                "icon" => "bar_chart",
                "order" => "10"
            ],
            (object)[
                "title" => "Automation",
                "slug" => "automation",
                "icon" => "refresh",
                "order" => "5"
            ],
            (object)[
                "title" => "Get Traffic",
                "slug" => "get-traffic",
                "icon" => "zoom_in",
                "order" => "7"
            ],
            (object)[
                "title" => "Articles",
                "slug" => "articles",
                "icon" => "library_books",
                "order" => "4"
            ],
            (object)[
                "title" => "Increase Conversions",
                "slug" => "increase-conversions",
                "icon" => "trending_up",
                "order" => "6"
            ],
            (object)[
                "title" => "Bonuses",
                "slug" => "bonuses",
                "icon" => "card-gift",
                "order" => "8"
            ],
            (object)[
                "title" => "Reports",
                "slug" => "reports",
                "icon" => "bar_chart",
                "order" => "9"
            ]
        ];

        foreach ($menus as $menu) {
            MemberMenu::create([
                'title' => $menu->title,
                'slug' => $menu->slug,
                'icon' => $menu->icon,
                'order' => $menu->order,
            ]);
        }
    }
}
