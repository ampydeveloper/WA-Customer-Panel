<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\DB;
//use App\Models\TimeSlots;
//use Illuminate\Support\Str;
use App\Models\News;
use App\Models\Faq;

class ServiceController extends Controller {

    public function serviceList() {
        $user = request()->user();
        if ($user->role_id == config('constant.roles.Customer_Manager') || $user->role_id == config('constant.roles.Hauler_driver')) {
            $user = User::whereId(request()->user()->created_by)->first();
        }
        if ($user->role_id != config('constant.roles.Customer') && $user->role_id != config('constant.roles.Haulers') && $user->role_id != config('constant.roles.Customer_Manager') && $user->role_id != config('constant.roles.Hauler_driver')) {
            return response()->json([
                        'status' => false,
                        'message' => 'unauthorized access.',
                        'data' => []
                            ], 421);
        } else {
            $getAllServices = Service::where('service_for', $user->role_id)->get();
            return response()->json([
                        'status' => true,
                        'message' => 'Service Listing.',
                        'data' => $getAllServices
                            ], 200);
        }
    }

    public function serviceForAll() {
        $getAllServices = Service::get();
        foreach ($getAllServices as $key => $services) {
            $getAllServices[$key]['service_image'] =  $services->service_image;
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Service Listing.',
                    'data' => $getAllServices
                        ], 200);
    }

    public function newsList() {
        $news_all = News::get();
        foreach ($news_all as $key => $new) {
//            $slug = $this->slugify($new->heading);
//            $news_all[$key]['slug'] = $slug;
            $news_all[$key]['image'] = $new->image;
            $news_all[$key]['description'] = $this->limitWords($new->description, 25);
            $news_all[$key]['heading'] = $this->limitWords($new->heading, 5);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'News List',
                    'data' => $news_all
                        ], 200);
    }

    public function newsSingle($newsId = null) {
        $news = News::where('slug', $newsId)->first();
        $news['image'] = $news['image'];
        $news['description'] = nl2br($news['description']);
        return response()->json([
                    'status' => true,
                    'message' => 'News Single.',
                    'data' => $news
                        ], 200);
    }

    public function newsListTwo() {
        $news_all = News::limit(2)->get();
        foreach ($news_all as $key => $new) {
            $news_all[$key]['image'] = $new->image;
            $news_all[$key]['description'] = $this->limitWords($new->description, 25);
            $news_all[$key]['heading'] = $this->limitWords($new->heading, 5);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'News List',
                    'data' => $news_all
                        ], 200);
    }

    public function faqList() {
        return response()->json([
                    'status' => true,
                    'message' => 'Faq List',
                    'data' => Faq::get()
                        ], 200);
    }

    public function get(Service $service) {
        $user = request()->user();
        if ($user->role_id == config('constant.roles.Customer_Manager') || $user->role_id == config('constant.roles.Hauler_driver')) {
            $user = User::whereId(request()->user()->created_by)->first();
        }
        if ($user->role_id != $service->service_for) {
            return response()->json([
                        'status' => false,
                        'message' => 'unauthorized access.',
                        'data' => []
                            ], 421);
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Service details.',
                    'data' => $service
                        ], 200);
    }

    private function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    function limitWords($text, $limit) {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

}
