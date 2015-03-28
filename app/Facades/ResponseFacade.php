<?php namespace App\Facades;

use Carbon;
use SlimFacades\Response;

class ResponseFacade extends Response {

        /**
         * Send a response.
         *
         * @param int $status The response status
         * @param string $message The response message
         * @param array $data The response data
         * @param array|null $lastModified The last modified date
         * @param array $allow The allowed methods
         * @return void
         */
        public static function send($status = 200, $data = null, $message = '', $lastModified = null, array $allow = array())
        {
                /**
                 * @var \Slim\Slim $slim
                 */
                $app = self::$slim;

                $app->response->setStatus($status);

                // Response headers
                $app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
                $app->response->headers->set('Access-Control-Allow-Methods', (!empty($allow) ? strtoupper(implode(',', $allow)) : 'GET, OPTIONS'));

                if ($app->request()->isOptions())
                {
                    $app->response();
                    return;
                }

                // Create the default response object
                $responseData = [
                    'success' => ($status < 400) ? true : false
                ];

                // Append a message to the response or create one
                $responseData['message'] = ($message !== '') ? $message : $app->response->getMessageForCode($status);

                if ($data !== null)
                {
                        $responseData['data'] = $data;
                }

                $json = json_encode($responseData);

                if ($app->response->isClientError() || $app->response->isServerError())
                {    
                        $app->halt($status, $json);
                }

                // Set the ETag
                $now = new Carbon();

                if (null === $data)
                {
                        $now->setTime($now->format('H'), $now->format('i'), 0);
                        $app->etag(md5($now->getTimestamp()));
                }
                else
                {
                        $app->etag(md5(serialize($json)));
                }

                if ($lastModified instanceof Carbon)
                {
                        $app->lastModified($lastModified->getTimestamp());
                }

                $app->response->setBody($json);

                $app->stop();
        }

}
