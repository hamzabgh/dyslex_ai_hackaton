<?php

namespace App\Core\Components\http;

use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Request as BaseRequest;


class CustomRequest extends Req
{
     /**
     * Get the Bearer token from the Authorization header.
     *
     * @return string
     */
    public function getBearerTokenFromHeader(): string
    {
        // Use the header method to get the Authorization header
        $authorizationHeader = $this->header('Authorization');

        // Check if the Authorization header is present and starts with 'Bearer '
        if ($authorizationHeader && strpos($authorizationHeader, 'Bearer ') === 0) {
            // Return the token without 'Bearer '
            return substr($authorizationHeader, 7);
        }

        // Return an empty string if the Authorization header is not present or is invalid
        return '';
    }

    /**
     * Check if the Bearer token is empty.
     *
     * @return bool
     */
    public function isEmptyBearerToken(): bool
    {
        return empty($this->getBearerTokenFromHeader());
    }
}


// class CustomRequest extends BaseRequest
// {
//     /**
//      * Get the Bearer token from the Authorization header.
//      *
//      * @return string
//      */
//     public function getBearerTokenFromHeader(): string
//     {
//         $authorizationHeader = $this->header('Authorization');

//         // Check if the Authorization header is present and starts with 'Bearer '
//         if ($authorizationHeader && strpos($authorizationHeader, 'Bearer ') === 0) {
//             // Return the token without 'Bearer '
//             return substr($authorizationHeader, 7);
//         }

//         // Return an empty string if the Authorization header is not present or is invalid
//         return '';
//     }

//     /**
//      * Check if the Bearer token is empty.
//      *
//      * @return bool
//      */
//     public function isEmptyBearerToken(): bool
//     {
//         return empty($this->getBearerTokenFromHeader());
//     }
// }