<?php

namespace App\Modules\Translations\Services;

use App\Modules\Core\Services\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;

class TranslationService
{
    protected $errors;
    public function __construct()
    {
        $this->errors = new MessageBag();
    }
    public function getTranslationsByLanguage($lang){
        $availableLanguages = ['en', 'nl'];
        if (!in_array($lang, $availableLanguages)) {
            $this->errors->add("error", "Language is not supported");
            return false;
        }
        $filePath = resource_path("lang/{$lang}/translations.json");
        if (!File::exists($filePath)) {
            $this->errors->add("error", "Translations file not found");
            return false;
        }

        return json_decode(File::get($filePath), true);
    }

    public function getErrors(){
        return $this->errors;
    }

    public function hasErrors(){
        return $this->errors->isNotEmpty();
    }
}
