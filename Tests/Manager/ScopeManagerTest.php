<?php

namespace OAuth2ServerBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use OAuth2ServerBundle\Manager\ScopeManager;
use OAuth2ServerBundle\Tests\ContainerLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class ScopeManagerTest
 */
class ScopeManagerTest extends TestCase
{
    /**
     * testFindScopesByScopes
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFindScopesByScopes()
    {
        try {
            $container = ContainerLoader::buildTestContainer();
            /**
             * @var EntityManager $emn
             */
            $emn = $container->get('doctrine.orm.entity_manager');

            $manager = new ScopeManager($emn);

            $scopes = array('test-scope-' . rand(), 'test-scope-' . rand(), 'test-scope-' . rand());

            foreach ($scopes as $scope) {
                $manager->createScope($scope, $scope);
            }

            $dbScopes = $manager->findScopesByScopes($scopes);

            $this->assertNotNull($dbScopes);
            $this->assertEquals(count($dbScopes), count($scopes));
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
