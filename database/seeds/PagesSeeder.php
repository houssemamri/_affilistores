<?php

use Illuminate\Database\Seeder;
use App\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages =  [
            (object)[
                "title" => "Copyright Notice",
                "body" => '<p><span style="font-size:16px;">This website and its content is copyright of Instant - ©Instant. All rights reserved.</span></p>
                <p><span style="font-size:16px;">Any redistribution or reproduction of part or all of the contents in any form is prohibited other than the following:</span></p>
                <ul><li><span style="font-size:16px;">You may use the images, fonts and ecovers to create commercial ebooks as long as you generate copyright legal descriptions and instructions. Copying and selling the images and fonts is <strong>STRICTLY PROHIBITED</strong>. If caught doing such, your account will be closed without warning.</span></li>
                    <li><span style="font-size:16px;">You may print or download to a local hard disk extracts for your personal and non-commercial use only.</span></li>
                    <li><span style="font-size:16px;">You may copy the content to individual third parties for their personal use, but only if you acknowledge the website as the source of the material.</span></li>
                    <li><span style="font-size:16px;">You may not, except with our express written permission, distribute or commercially exploit the content. Nor may you transmit it or store it in any other website or other form of electronic retrieval system.</span></li>
                </ul>',
                "slug" => "copyright-notice",
                "page_part" => "footer_nav",
                "type" => "1",
                "order" => "1"
            ],
            (object)[
                "title" => "Earnings Disclaimer",
                "body" => '<p><strong>Income Disclaimer</strong></p>

                <p>This website and the items it distributes contain business strategies, marketing methods and other business advice that, regardless of my own results and experience, may not produce the same results (or any results) for you. Instant makes absolutely no guarantee, expressed or implied, that by following the advice or content available from this web site you will make any money or improve current profits, as there are several factors and variables that come into play regarding any given business.</p>
                
                <p>Primarily, results will depend on the nature of the product or business model, the conditions of the marketplace, the experience of the individual, and situations and elements that are beyond your control.</p>
                
                <p>As with any business endeavour, you assume all risk related to investment and money based on your own discretion and at your own potential expense.</p>
                
                <p><strong>Liability Disclaimer</strong></p>
                
                <p>By reading this website or the documents it offers, you assume all risks associated with using the advice given, with a full understanding that you, solely, are responsible for anything that may occur as a result of putting this information into action in any way, and regardless of your interpretation of the advice.</p>
                
                <p>You further agree that our company cannot be held responsible in any way for the success or failure of your business as a result of the information provided by our company. It is your responsibility to conduct your own due diligence regarding the safe and successful operation of your business if you intend to apply any of our information in any way to your business operations.</p>
                
                <p>In summary, you understand that we make absolutely no guarantees regarding income as a result of applying this information, as well as the fact that you are solely responsible for the results of any action taken on your part as a result of any given information.</p>
                
                <p>In addition, for all intents and purposes you agree that our content is to be considered "for entertainment purposes only". Always seek the advice of a professional when making financial, tax or business decisions.</p>',
                "slug" => "earnings-disclaimer",
                "page_part" => "footer_nav",
                "type" => "1",
                "order" => "2"
            ],
            (object)[
                "title" => "Privacy Policy",
                "body" => '<p><span style="font-size:16px;">This privacy policy sets out how Instant uses and protects any information that you give Instant when you use this website.</span></p>

                <p><span style="font-size:16px;">Instant is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.</span></p>
                
                <p><span style="font-size:16px;">Instant may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. This policy is effective from Monday, November 10, 2016.</span></p>
                
                <p><span style="font-size:16px;"><strong>What we collect</strong></span></p>
                
                <p><span style="font-size:16px;">We may collect the following information:</span></p>
                
                <ul><li><span style="font-size:16px;">name and job title</span></li>
                    <li><span style="font-size:16px;">contact information including email address</span></li>
                    <li><span style="font-size:16px;">demographic information such as postcode, preferences and interests</span></li>
                    <li><span style="font-size:16px;">other information relevant to customer surveys and/or offers</span></li>
                </ul>
                
                <p><span style="font-size:16px;"><strong>What we do with the information we gather</strong></span></p>
                
                <p><span style="font-size:16px;">We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</span></p>
                
                <ul><li><span style="font-size:16px;">Internal record keeping.</span></li>
                    <li><span style="font-size:16px;">We may use the information to improve our products and services.</span></li>
                    <li><span style="font-size:16px;">We may periodically send promotional emails about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</span></li>
                    <li><span style="font-size:16px;">From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, fax or mail. We may use the information to customise the website according to your interests.</span></li>
                </ul>
                
                <p><span style="font-size:16px;"><strong>Security</strong></span></p>
                
                <p><span style="font-size:16px;">We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure, we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</span></p>
                
                <p><span style="font-size:16px;"><strong>How we use cookies</strong></span></p>
                
                <p><span style="font-size:16px;">A cookie is a small file which asks permission to be placed on your computer\'s hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</span></p>
                
                <p><span style="font-size:16px;">We may use traffic log cookies to identify which pages are being used. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system.</span></p>
                
                <p><span style="font-size:16px;">Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</span></p>
                
                <p><span style="font-size:16px;">You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</span></p>
                
                <p><span style="font-size:16px;"><strong>Links to other websites</strong></span></p>
                
                <p><span style="font-size:16px;">Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</span></p>
                
                <p><span style="font-size:16px;"><strong>Controlling your personal information</strong></span></p>
                
                <p><span style="font-size:16px;">You may choose to restrict the collection or use of your personal information in the following ways:</span></p>
                
                <ul><li><span style="font-size:16px;">whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</span></li>
                    <li><span style="font-size:16px;">if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at <a href="mailto:info@ifl.awesomarky.com">info@ifl.awesomarky.com</a></span></li>
                </ul>
                
                <p><span style="font-size:16px;">We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</span></p>
                
                <p><span style="font-size:16px;">You may request details of personal information which we hold about you as governed by the laws of United States of America.</span></p>
                
                <p><span style="font-size:16px;">A small fee may be payable. If you would like a copy of the information held on you please write to 13130 Hwy 9 Box 8008 Boulder Creek, Ca 95006.</span></p>
                
                <p><span style="font-size:16px;">If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.</span></p>',
                "slug" => "privacy-policy",
                "page_part" => "footer_nav",
                "type" => "1",
                "order" => "3"
            ],
            (object)[
                "title" => "Terms of Service",
                "body" => '<p><span style="font-size:16px;">Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern Instant\'s relationship with you in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website. The term \'Instant\' or \'us\' or \'we\' refers to the owner of the website whose registered office is 13130 Hwy 9 Box 8008 Boulder Creek, Ca 95006. The term \'you\' refers to the user or viewer of our website.</span></p>
                <p><span style="font-size:16px;">The use of this website is subject to the following terms of use:</span></p>
                <ul><li><span style="font-size:16px;">The content of the pages of this website is for your general information and use only. It is subject to change without notice.</span></li>
                    <li><span style="font-size:16px;">This website may use cookies to monitor browsing preferences. If you do allow cookies to be used, please refer to our privacy policy for details of what data we may collect and how it may be used.</span></li>
                    <li><span style="font-size:16px;">Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</span></li>
                    <li><span style="font-size:16px;">Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</span></li>
                    <li><span style="font-size:16px;">This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</span></li>
                    <li><span style="font-size:16px;">All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</span></li>
                    <li><span style="font-size:16px;">Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offense.</span></li>
                    <li><span style="font-size:16px;">From time to time, this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</span></li>
                    <li><span style="font-size:16px;">Your use of this website and any dispute arising out of such use of the website is subject to the laws of United States of America.</span></li>
                </ul>',
                "slug" => "terms-of-service",
                "page_part" => "footer_nav",
                "type" => "1",
                "order" => "4"
            ],
            (object)[
                "title" => "Website Disclaimer",
                "body" => '<p><span style="font-size:16px;">The information contained in this website is for general information purposes only.</span></p>
                <p><span style="font-size:16px;">The information is provided by Instant and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</span></p>
                <p><span style="font-size:16px;">In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</span></p>
                <p><span style="font-size:16px;">Through this website you are able to link to other websites which are not under the control of Instant. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</span></p>
                <p><span style="font-size:16px;">Every effort is made to keep the website up and running smoothly. However, Instant takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</span></p>',
                "slug" => "website-disclaimer",
                "page_part" => "footer_nav",
                "type" => "1",
                "order" => "5"
            ],
        ];

        foreach ($pages as $page) {
            Page::create([
                'title' => $page->title, 
                'body' => $page->body, 
                'slug' => $page->slug, 
                'type' => $page->type, 
                'page_part' => $page->page_part, 
                'order' => $page->order
            ]);
        }
    }
}
