<?php


namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;

class UserProvider implements IlluminateUserProvider
{
    /**
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return cache("_user_info_" . $identifier);
        // Get and return a user by their unique identifier
    }

    /**
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $id = cache()->get("_remember_token_" . $token);
        $this->retrieveById($id);
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        cache()->put("_remember_token_" . $token, $user->username, config("session.lifetime"));
        cache()->put("_user_info_" . $user->username, $user, config("session.lifetime"));
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $client = new Client();
        $response = $client->post("https://api.vpos.co.ke/v1/admin/login", [RequestOptions::JSON => $credentials]);
        $details = json_decode($response->getBody()->getContents(), true);
        if ($details['status']['code'] == 0) {
            return new User($details['profile']);
        } else {
            return null;
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->username == $credentials['username'];
    }

}