<?php

namespace TienRocker\Auth\Controllers;

class Auth extends Base
{
    /**
     * Login function
     * @param null $provider
     */
    public function login($provider = null)
    {
        switch ($provider) {
            case 'twitter':
                $this->twitter();
                break;
            default:
                $this->normal();
                break;
        }
    }

    /**
     * Login normal function
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function normal()
    {
        if ($this->request->isPost()) {
            try {
                // Get the data from the user
                $email_or_username = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                // set to view
                $this->view->setVar('email', $email_or_username);
                $this->view->setVar('password', $password);

                if (!$this->security->checkToken()) throw new \Exception('Please try again');

                $user = \Model_User::auth($email_or_username, $password);
                if ($user['status'] == 0) {
                    // redirect to active page
                    return $this->response->redirect('active?email=' . $user['email']);
                } else {
                    $this->_saveAuth($user);
                    return $this->_redirectBack();
                }

            } catch (\Exception $ex) {
                $this->flash->error($ex->getMessage());
            }
        }
    }

    public function twitter()
    {
        try {
            // create an instance for Hybridauth with the configuration file path as parameter

            // try to authenticate the user with twitter,
            // user will be redirected to Twitter for authentication,
            // if he already did, then Hybridauth will ignore this step and return an instance of the adapter
            $this->provider = $this->hybrid_auth->authenticate('Twitter');

            // get the user profile
            $twitter_user_profile = $this->provider->getUserProfile();

            $this->session->set('auth-identity', array(
                'id' => $twitter_user_profile->identifier,
                'username' => $twitter_user_profile->displayName,
                'pic' => $twitter_user_profile->photoURL
            ));

            return $this->response->redirect('');

        } catch (\Exception $e) {
            // Display the recived error,
            // to know more please refer to Exceptions handling section on the userguide
            switch ($e->getCode()) {
                case 0 :
                    echo "Unspecified error.";
                    break;
                case 1 :
                    echo "Hybriauth configuration error.";
                    break;
                case 2 :
                    echo "Provider not properly configured.";
                    break;
                case 3 :
                    echo "Unknown or disabled provider.";
                    break;
                case 4 :
                    echo "Missing provider application credentials.";
                    break;
                case 5 :
                    echo "Authentification failed. "
                        . "The user has canceled the authentication or the provider refused the connection.";
                    break;
                case 6 :
                    echo "User profile request failed. Most likely the user is not connected to the provider and he should authenticate again.";
                    $this->provider->logout();
                    break;
                case 7 :
                    echo "User not connected to the provider.";
                    $this->provider->logout();
                    break;
                case 8 :
                    echo "Provider does not support this feature.";
                    break;
            }

            // well, basically your should not display this to the end user, just give him a hint and move on..
            echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();
            die();
        }
    }
}