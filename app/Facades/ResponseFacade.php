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
                $slim = self::$slim;

                $slim->response->setStatus($status);

                // Response headers
                $slim->response->headers->set('Content-Type', 'application/json; charset=utf-8');
                $slim->response->headers->set('Access-Control-Allow-Methods', (!empty($allow) ? strtoupper(implode(',', $allow)) : 'GET, OPTIONS'));

                if ($slim->request()->isOptions())
                {
                    $slim->response();
                    return;
                }

                // Create the default response object
                $responseData = [
                    'success' => ($status < 400) ? true : false
                ];

                // Append a message to the response or create one
                $responseData['message'] = ($message !== '') ? $message : $slim->response->getMessageForCode($status);

                if ($data !== null)
                {
                        $responseData['data'] = $data;
                }

                $json = json_encode($responseData);

                if ($slim->response->isClientError() || $slim->response->isServerError())
                {    
                        $slim->halt($status, $json);
                }

                // Set the ETag
                $now = new Carbon();

                if (null === $data)
                {
                        $now->setTime($now->format('H'), $now->format('i'), 0);
                        $slim->etag(md5($now->getTimestamp()));
                }
                else
                {
                        $slim->etag(md5(serialize($json)));
                }

                if ($lastModified instanceof Carbon)
                {
                        $slim->lastModified($lastModified->getTimestamp());
                }

                $slim->response->setBody($json);

                $slim->stop();
        }

}
