<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Sadece son gelen response üzerinden işlemi yapalım
        $response = $next($request);

        // HTML content-type ile dönen response'lar için minification işlemi yapılacak
        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            // Response içeriğini al
            $content = $response->getContent();

            $minifiedContent = preg_replace_callback('/<script.*?<\/script>/s', function ($matches) {
                return $matches[0]; // JavaScript içeriğini olduğu gibi korur
            }, $content);

            $minifiedContent = preg_replace([
                // Yalnızca gerçek boşluk karakterlerini iki ve daha fazlasını tek boşlukla değiştiriyoruz
                '/\r/',     // Satır sonları (newlines) kaldırılıyor
                '/>\s+</',     // Etiketler arasındaki gereksiz boşlukları kaldırıyoruz
            ], [
                '',            // Satır sonlarını kaldırıyoruz
                '><',          // Etiketler arasındaki boşluğu siliyoruz
            ], $minifiedContent);

            // JavaScript içeriklerini tekrar eski haline getiriyoruz
            $minifiedContent = preg_replace_callback('/<script.*?<\/script>/s', function ($matches) {
                return $matches[0]; // JavaScript'i geri yerine koyar
            }, $minifiedContent);

            // Minified içeriği response'a tekrar set et
            $response->setContent($minifiedContent);
        }

        return $response;
    }
}
