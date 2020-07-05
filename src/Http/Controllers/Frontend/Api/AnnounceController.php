<?php


namespace Modules\Core\Http\Controllers\Frontend\Api;


use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\AnnounceService;

class AnnounceController extends Controller
{
    public function index(Request $request, AnnounceService $announceService)
    {
        $locale = app()->getLocale();
        return $announceService->paginate([], ['orderBy' => ['created_at', 'desc']])->map(function($announce) use ($locale) {
            $value = $announce->value;
            $value['title'] =  $value['title'][$locale] ?? '';
            $value['content'] = $value['content'][$locale] ?? '';
            $announce->value = $value;
            return $announce;
        });
    }

    public function info(Request $request, AnnounceService $announceService)
    {
        $announce = $announceService->getById($request->id);

        $locale = app()->getLocale();
        $value = $announce->value;
        $value['title'] =  $value['title'][$locale] ?? '';
        $value['content'] = $value['content'][$locale] ?? '';
        $announce->value = $value;

        return $announce;
    }
}
