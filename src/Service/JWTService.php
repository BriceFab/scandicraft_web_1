<?php

namespace App\Service;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JWTService
{
    private $signer;
    private $em;
    private $urlHelper;

    public function __construct(EntityManagerInterface $em, UrlHelper $urlHelper)
    {
        $this->signer = new Sha512();
        $this->em = $em;
        $this->urlHelper = $urlHelper;
    }

    public function generateToken(User $user, string $action, $expire_after_minutes = 0)
    {
        $url = $this->urlHelper->getAbsoluteUrl('/');
        $time = time();

        if ($expire_after_minutes == 0) {
            $expire_after_minutes = $_ENV['token_expiration'];
        }

        $token = (new Builder())
            ->issuedBy($url)
            ->permittedFor($url)
            ->issuedAt($time)
            ->expiresAt($time + (int) $expire_after_minutes)
            ->withClaim('user_id', $user->getId())
            ->withClaim('action', $action)
            ->getToken($this->signer, new Key($_ENV['jwt_sign_key']));

        return $token;
    }

    public function verifyToken($token, string $action)
    {
        //Verify if token
        if (!$token) throw new HttpException(500, 'no token');
        $token = (new Parser())->parse((string) $token);

        //Verify token signed
        if (!$token->verify($this->signer, $_ENV['jwt_sign_key'])) throw new HttpException(500, 'token invalide');

        //Verify token time
        if ($token->isExpired(new DateTime())) throw new HttpException(500, 'token expired');

        //Verify issedBy
        if (!$token->getClaim('iss')) throw new HttpException(500, 'invalide iss');

        //Verify action
        if ($token->getClaim('action') !== $action) throw new HttpException(500, 'invalide action');

        //Verify user
        $user_id = $token->getClaim('user_id');
        if (!$user_id) throw new HttpException(500, 'token user invalide');

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->find($user_id);
        if (!$user) throw new HttpException(500, 'user invalide');

        return $user;
    }
}

/**
 * Liste des actions des tokens
 */
abstract class TokenAction
{
    const REGISTER_CONFIRMATION = 'register_confirmation';
    const RESET_PASSWORD = 'reset_password';
}
