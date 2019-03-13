<?php

use App\Helper\CUserFactory;
use App\Helper\Exceptions\CArchivedUserException;
use App\Helper\Exceptions\CInvalidApiKeyException;
use App\Service\Users\CUserService;
use App\Helper\Messages\CAuthMessages;

class RoleMiddleware
{
    /**
     * Container variable
     *
     */
    private $container;
    private $role;

    /**
     * CUserService instance
     *
     */
    private $userService;

    /**
     * Create a new ResponseMiddleware instance.
     *
     */
    public function __construct($container,$role) {
        $this->container = $container;
        $this->role = $role;
        $this->userService = new CUserService();
    }

    /**
     * Middleware that forms the response
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        //check if currently logged in user is active and not archived
         if(CUserFactory::getId()){
            if(!$this->checkUserStatus(CUserFactory::getId())){
                $exception = new CArchivedUserException;
                return $exception($request, $response);
            }
        }

        $user = CUserFactory::getRoles();
        if(is_array($this->role)){
            foreach($this->role as $role){
                if(in_array($role,$user)){
                    return $next($request,$response);
                }
            }
        }
        else {
            if (in_array($this->role,$user)) {
                return $next($request, $response);
            }
        }
        return $response->withStatus(401)->write("You have no access here!");
    }
    /**
     * validate or check the current logged in user's status
     *
     * @param [type] $response
     * @return response
     */
    public function checkUserStatus($userId){
        $userStatus = $this->userService->checkUserStatus($userId);
       if($userStatus === CAuthMessages::USER_STATUS_ACTIVE){
            return true;
       }else if($userStatus === CAuthMessages::USER_STATUS_ARCHIVED){
            return false;
       }
    }

}
