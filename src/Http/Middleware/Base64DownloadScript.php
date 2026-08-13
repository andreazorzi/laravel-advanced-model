<?php

namespace AdvancedModel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Base64DownloadScript
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only touch HTML responses
        $contentType = $response->headers->get('Content-Type');
        if ($response instanceof Response && str_contains((string) $contentType, 'text/html')) {
            $content = $response->getContent();

            if (stripos($content, '</body>') !== false) {
                $script = '
                    <script>
                        window.downloadBase64File = function(event) {
                            if (event.detail.xhr.status === 200) {
                                const response = JSON.parse(event.detail.xhr.response);

                                const byteCharacters = atob(response.base64);
                                const byteNumbers = new Array(byteCharacters.length);
                                for (let i = 0; i < byteCharacters.length; i++) {
                                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                                }
                                const byteArray = new Uint8Array(byteNumbers);
                                const fileBlob = new Blob([byteArray], { type: response.mimetype });

                                const link = document.createElement("a");
                                link.href = URL.createObjectURL(fileBlob);
                                link.download = response.filename;

                                document.body.appendChild(link);
                                link.click();

                                document.body.removeChild(link);
                                URL.revokeObjectURL(link.href);
                            }
                        }
                    </script>
                ';
                $content = str_ireplace('</body>', $script . '</body>', $content);
                $response->setContent($content);
            }
        }

        return $response;
    }
}