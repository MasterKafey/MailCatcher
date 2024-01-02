<?php

namespace App\Twig\Extension;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GravatarExtension extends AbstractExtension
{
    private const GRAVATAR_URL = 'https://gravatar.com';

    public function __construct(
        private readonly RouterInterface $router
    )
    {

    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('gravatar_image', [$this, 'getGravatarImageUrl']),
        ];
    }

    public function getGravatarImageUrl(string $email, int $size = 200): string
    {
        $hash = hash('sha256', trim(strtolower($email)));

        return self::GRAVATAR_URL . $this->router->generate('gravatar_image', [
            'hash' => $hash,
            'size' => $size,
        ]);
    }
}