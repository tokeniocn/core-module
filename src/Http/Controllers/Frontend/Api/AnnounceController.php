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
        $data = $announceService->paginate([], ['orderBy' => ['created_at', 'desc']]);
        $data->each(function($announce) use ($locale) {
            $value = $announce->value;
            $value['title'] =  $value['title'] ?? '';
            $value['content'] = $value['content'] ?? '';
            $value['description'] = mb_substr($value['content'], 0, 50);
            $announce->value = $value;
        });
        return $data;
    }

    public function info(Request $request, AnnounceService $announceService)
    {
        $announce = $announceService->getById($request->id);

        $locale = app()->getLocale();
        $value = $announce->value;
        $value['title'] =  $value['title'][$locale] ?? '';
        $value['content'] = $value['content'][$locale] ?? '';
        $value['description'] = mb_substr($value['content'], 0, 50);
        $announce->value = $value;

        return $announce;
    }
}
