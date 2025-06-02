<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Missatge extends Model
{
    protected $table = 'missatges';

    protected $fillable = ['conversa_id', 'remitent', 'cos'];

    public function conversa()
    {
        return $this->belongsTo(Conversa::class, 'conversa_id');
    }
    public function formatat()
{
    $text = $this->cos;

    // 1. Extraiem els blocs de codi, i els substituïm per un marcador únic
    $text = preg_replace_callback('/```(\w*)\n(.*?)```/s', function ($matches) {
        $lang = $matches[1];
        $code = htmlspecialchars($matches[2]); // escapem sols el contingut del codi
        return "__CODEBLOCK__{$lang}__{$code}__ENDCODEBLOCK__";
    }, $text);

    // 2. Escapem la resta del text (fora del codi)
    $text = htmlspecialchars($text);

    // 3. Revertim els marcadors de codi per la versió HTML
    $text = preg_replace_callback('/__CODEBLOCK__(\w+)__(.*?)__ENDCODEBLOCK__/s', function ($matches) {
        $lang = $matches[1];
        $code = $matches[2];
        return "<pre class=\"bg-gray-900 text-gray p-4 rounded overflow-x-auto text-sm\"><code class=\"language-{$lang}\">{$code}</code></pre>";
    }, $text);

    // 4. Format extra
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    $text = nl2br($text);

    return $text;
}


}
