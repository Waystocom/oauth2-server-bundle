<?php

namespace OAuth2ServerBundle\Tests\Entity;

use Exception;
use OAuth2ServerBundle\Tests\ContainerLoader;
use OAuth2ServerBundle\Entity\ClientPublicKey;
use OAuth2ServerBundle\Entity\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientPublicKeyTest
 */
class ClientPublicKeyTest extends TestCase
{
    /**
     * testCreate
     *
     * @throws Exception
     */
    public function testCreate()
    {
        try {
            $container = ContainerLoader::buildTestContainer();
            $emn = $container->get('doctrine.orm.entity_manager');

            $client = new Client();
            $client->setClientId('test-client-' . rand());
            $client->setClientSecret('very-secure');
            $client->setRedirectUri(array('http://brentertainment.com'));

            $emn->persist($client);
            $emn->flush();

            $publicKey = new ClientPublicKey();
            $publicKey->setClient($client);

            // create and set the public key
            $res = openssl_pkey_new();

            // Extract the public key from $res to $pubKey
            $pubKeyDetails = openssl_pkey_get_details($res);
            $pubKey = $pubKeyDetails['key'];
            $publicKey->setPublicKey($pubKey);
            $emn->persist($publicKey);
            $emn->flush();

            // test direct access
            $stored = $emn->find(
                'OAuth2ServerBundle\Entity\ClientPublicKey',
                array('clientId' => $client)
            );

            $this->assertNotNull($stored);
            $this->assertEquals($pubKey, $stored->getPublicKey());
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
