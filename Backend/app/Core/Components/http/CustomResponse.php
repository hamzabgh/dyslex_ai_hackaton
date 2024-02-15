<?php

namespace App\Core\Components\http;

use Illuminate\Http\Response as Res;

class CustomResponse extends Res
{
    const OK                    = 200;
    const CREATED               = 201;
    const ACCEPTED              = 202;
    const MOVED_PERMANENTLY     = 301;
    const FOUND                 = 302;
    const TEMPORARY_REDIRECT    = 307;
    const PERMANENTLY_REDIRECT  = 308;
    const BAD_REQUEST           = 400;
    const UNAUTHORIZED          = 401;
    const FORBIDDEN             = 403;
    const NOT_FOUND             = 404;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED       = 501;
    const BAD_GATEWAY           = 502;

    private $codes = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        301 => 'Moved Permanently',
        302 => 'Found',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
    ];

    /**
     * Send the response back
     *
     * @return Res
     */
    public function send(): Res
    {
        $content   = $this->getContent();
        $timestamp = now()->toIso8601String();
        $hash      = sha1($timestamp . $content);
        $eTag      = sha1($content);

        /**
         * $flag $success, $message, $id, $uuid
         */
        $flag   = [];

        $content = json_decode($this->getContent(), true);

        $meta    = [
            'signature' => [
                'timestamp' => $timestamp,
                'hash'      => $hash,
            ],
        ];

        /**
         * Join the array again
         */
        $data = $flag + $content + $meta;

        $this
            ->header('E-Tag', $eTag)
            ->setContent(json_encode($data))  // Encode content to JSON
            ->header('Content-Type', 'application/json'); // Set the Content-Type header

        return parent::send();
    }

    /**
     * Sets Http Response code and description
     *
     * @param int $code
     *
     * @return CustomResponse
     */
    public function setStatus(int $code): CustomResponse
    {
        $this->setStatusCode($code, $this->getHttpCodeDescription($code));

        return $this;
    }

    /**
     * Sets the payload
     *
     * @param null|string|array $content The content
     *
     * @return CustomResponse
     */
    public function setPayload($data = [], $status = [], int $code = 200): CustomResponse
    {
        $this->setStatus($code);

        if (array_key_exists('uuid', $status) && $data == []) {
            $data['uuid'] = $status['uuid'];
            unset($status['uuid']);
        }

        $content = ['status' => $status, 'content' => $data];

        $this->setContent(json_encode($content));

        return $this;
    }

    /**
     * Returns the http code description
     *
     * @param int $code
     *
     * @return int|string
     */
    private function getHttpCodeDescription(int $code)
    {
        if (true === isset($this->codes[$code])) {
            return $this->codes[$code];
        }

        return '';
    }
}
