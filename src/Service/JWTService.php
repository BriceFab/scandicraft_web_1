<?php

namespace App\Service;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JWTService
{
    private $signer;
    private $em;
    private $urlHelper;
    private $urlGenerator;
    private $session;

    public function __construct(EntityManagerInterface $em, UrlHelper $urlHelper, UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->signer = new Sha512();
        $this->em = $em;
        $this->urlHelper = $urlHelper;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    /**
     * expire_after_minutes: Le temps en secondes que le token expire
     * 0 = $_ENV['token_expiration']
     * -1 = n'expire pas
     */
    public function generateToken(User $user, string $action, $expire_after_minutes = 0)
    {
        $url = $this->urlHelper->getAbsoluteUrl('/');
        $time = time();

        if ($expire_after_minutes == 0) {
            $expire_after_minutes = $_ENV['token_expiration'];
        }

        $builder = new Builder();
        $builder->issuedBy($url);
        $builder->permittedFor($url);
        $builder->issuedAt($time);
        $builder->withClaim('user_id', $user->getId());
        $builder->withClaim('action', $action);
        if ($expire_after_minutes > 0) {
            $builder->expiresAt($time + (int) $expire_after_minutes);
        }

        return $builder->getToken($this->signer, new Key($_ENV['jwt_sign_key']));
    }

    public function verifyToken($token, string $action)
    {
        //Verify if token
        if (!$token) throw new HttpException(403, 'no token');
        $token = (new Parser())->parse((string) $token);

        //Verify token signed
        if (!$token->verify($this->signer, $_ENV['jwt_sign_key'])) throw new HttpException(403, 'token invalide');

        //Verify token time
        if ($token->isExpired(new DateTime())) {
            //token has expired
            $this->session->getFlashBag()->add('error', 'Le token a expiré. Il n\'est plus valide !');
            throw new HttpException(403, 'token invalide');
        }

        //Verify issedBy
        if (!$token->getClaim('iss')) throw new HttpException(403, 'invalide iss');

        //Verify action
        if ($token->getClaim('action') !== $action) throw new HttpException(403, 'invalide action');

        //Verify user
        $user_id = $token->getClaim('user_id');
        if (!$user_id) throw new HttpException(403, 'token user invalide');

        /** @var User $user */
        $user = $this->em->getRepository(User::class)->find($user_id);
        if (!$user) throw new HttpException(403, 'user invalide');

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
