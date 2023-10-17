<?php

namespace pizzashop\auth\api\service;

use Exception;
use pizzashop\auth\api\dto\CredentialsDTO;
use pizzashop\auth\api\dto\TokenDTO;
use pizzashop\auth\api\dto\UserDTO;
use pizzashop\auth\api\manager\iJwtManager;
use pizzashop\auth\api\manager\JwtManagerExpiredTokenException;
use pizzashop\auth\api\manager\JwtManagerInvalidTokenException;
use pizzashop\auth\api\provider\AuthProviderInvalidCredentialsException;
use pizzashop\auth\api\provider\AuthProviderInvalidTokenException;
use pizzashop\auth\api\provider\iAuthProvider;
use Psr\Log\LoggerInterface;

class AuthService implements iAuth
{

    private iJwtManager $jwtManager;
    private iAuthProvider $authProvider;
    private LoggerInterface $logger;

    public function __construct(iJwtManager $jwtManager, iAuthProvider $authProvider, LoggerInterface $logger)
    {
        $this->jwtManager = $jwtManager;
        $this->authProvider = $authProvider;
        $this->logger = $logger;
    }

    /**
     * @throws AuthServiceCredentialsException
     */
    public function signup(CredentialsDTO $c): UserDTO
    {
        try {
            $this->authProvider->register($c->email, $c->password);
        } catch (AuthProviderInvalidCredentialsException) {
            throw new AuthServiceCredentialsException("Invalid credentials");
        }
        $user = $this->authProvider->getAuthenticatedUser();

        return new UserDTO($user['user'], $user['email']);
    }

    /**
     * @throws Exception
     */
    public function signin(CredentialsDTO $c): TokenDTO
    {
        try {
            $this->authProvider->checkCredentials($c->email, $c->password);
        } catch (AuthProviderInvalidCredentialsException) {
            throw new AuthServiceCredentialsException("Invalid credentials");
        }
        $user = $this->authProvider->getAuthenticatedUser();

        return new TokenDTO($this->jwtManager->create($user), $user['refresh_token']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     * @throws AuthServiceExpiredTokenException
     */
    public function validate(TokenDTO $t): UserDTO
    {
        try {
            $payload = $this->jwtManager->validate($t->access_token);
        } catch (JwtManagerExpiredTokenException) {
            throw new AuthServiceExpiredTokenException("Expired token");
        } catch (JwtManagerInvalidTokenException) {
            $this->logger->warning('failed jwt validation');
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
        return new UserDTO($payload['user'], $payload['email']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function refresh(TokenDTO $t): TokenDTO
    {
        try {
            $this->authProvider->checkToken($t->refresh_token);
        } catch (AuthProviderInvalidTokenException $e) {
            $this->logger->warning('failed jwt refresh');
            throw new AuthServiceInvalidTokenException("Invalid token" . $e->getMessage());
        }
        $user = $this->authProvider->getAuthenticatedUser();
        return new TokenDTO($this->jwtManager->create($user), $user['refresh_token']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function activate_signup(TokenDTO $t): void
    {
        try {
            $this->authProvider->activate($t->access_token);
        } catch (AuthProviderInvalidTokenException) {
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function reset_password(TokenDTO $t, CredentialsDTO $c, string $newPassword): void
    {
        try {
            $this->authProvider->resetPassword($t->access_token, $c->password, $newPassword);
        } catch (AuthProviderInvalidTokenException) {
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
    }
}