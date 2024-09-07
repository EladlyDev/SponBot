<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Spanish', 'code' => 'es'],
            ['name' => 'French', 'code' => 'fr'],
            ['name' => 'German', 'code' => 'de'],
            ['name' => 'Chinese (Simplified)', 'code' => 'zh-CN'],
            ['name' => 'Chinese (Traditional)', 'code' => 'zh-TW'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'Arabic', 'code' => 'ar'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'Russian', 'code' => 'ru'],
            ['name' => 'Italian', 'code' => 'it'],
            ['name' => 'Dutch', 'code' => 'nl'],
            ['name' => 'Turkish', 'code' => 'tr'],
            ['name' => 'Swedish', 'code' => 'sv'],
            ['name' => 'Danish', 'code' => 'da'],
            ['name' => 'Norwegian', 'code' => 'no'],
            ['name' => 'Finnish', 'code' => 'fi'],
            ['name' => 'Polish', 'code' => 'pl'],
            ['name' => 'Greek', 'code' => 'el'],
            ['name' => 'Hebrew', 'code' => 'he'],
            ['name' => 'Hungarian', 'code' => 'hu'],
            ['name' => 'Czech', 'code' => 'cs'],
            ['name' => 'Romanian', 'code' => 'ro'],
            ['name' => 'Thai', 'code' => 'th'],
            ['name' => 'Vietnamese', 'code' => 'vi'],
            ['name' => 'Indonesian', 'code' => 'id'],
            ['name' => 'Malay', 'code' => 'ms'],
            ['name' => 'Bengali', 'code' => 'bn'],
            ['name' => 'Hindi', 'code' => 'hi'],
            ['name' => 'Urdu', 'code' => 'ur'],
            ['name' => 'Punjabi', 'code' => 'pa'],
            ['name' => 'Swahili', 'code' => 'sw'],
            ['name' => 'Catalan', 'code' => 'ca'],
            ['name' => 'Serbo-Croatian', 'code' => 'sh'],
            ['name' => 'Bulgarian', 'code' => 'bg'],
            ['name' => 'Ukrainian', 'code' => 'uk'],
            ['name' => 'Latvian', 'code' => 'lv'],
            ['name' => 'Lithuanian', 'code' => 'lt'],
            ['name' => 'Estonian', 'code' => 'et'],
        ];

        DB::table('languages')->insert($languages);
    }
}
