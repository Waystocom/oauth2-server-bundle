<?php

namespace OAuth2ServerBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface OAuth2UserInterface
 */
interface OAuth2UserInterface extends UserInterface
{
    /**
     * Returns the scope granted to the user,
     * space-separated.
     *
     * <code>
     * public function getScope()
     * {
     *     return 'basic email';
     * }
     * </code>
     *
     *
     * @return string The user scope
     */
    public function getScope(): string;
}
