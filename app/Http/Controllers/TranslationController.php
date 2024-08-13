<?php
namespace App\Http\Controllers;

use App\Modules\Translations\Services\TranslationService;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    protected TranslationService $service;

    public function __construct(TranslationService $service)
    {
        $this->service = $service;

    }
    public function getTranslations($language)
    {
        $translations = $this->service->getTranslationsByLanguage($language);
        if($translations) {
            return response()->json($translations, 200);
        }else{
            return response()->json($this->service->getErrors());
        }
    }
}
