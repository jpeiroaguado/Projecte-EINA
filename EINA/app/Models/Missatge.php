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

    // 1. Extraiem els blocs de codi amb ```lang\ncodi```
    $blocs = [];
    $text = preg_replace_callback('/```(\w*)\n(.*?)```/s', function ($matches) use (&$blocs) {
        $lang = $matches[1];
        $code = htmlspecialchars($matches[2]); // escapem només el codi
        $id = '__CODEBLOCK_' . count($blocs) . '__';
        $blocs[$id] = "<pre class=\"bg-gray-900 text-gray p-4 rounded overflow-x-auto text-sm\"><code class=\"language-{$lang}\">{$code}</code></pre>";
        return $id; // retornem marcador temporal
    }, $text);

    // 2. Escapem tot el text restant (fora dels blocs)
    $text = htmlspecialchars($text);

    // 3. Negretes **text**
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);

    // 4. Salts de línia
    $text = nl2br($text);

    // 5. Substituïm de nou els blocs de codi (que no estan escapats)
    foreach ($blocs as $id => $htmlBloc) {
        $text = str_replace($id, $htmlBloc, $text);
    }

    return $text;
}



}
