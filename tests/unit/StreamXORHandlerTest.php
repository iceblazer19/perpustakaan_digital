<?php

use CodeIgniter\Encryption\Handlers\StreamXORHandler;
use CodeIgniter\Encryption\Exceptions\EncryptionException;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Encryption as EncryptionConfig;

/**
 * @internal
 */
final class StreamXORHandlerTest extends CIUnitTestCase
{
    protected StreamXORHandler $handler;
    protected EncryptionConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new EncryptionConfig();
        $this->config->driver = 'StreamXOR';
        $this->config->key = str_repeat('a', 32);
        $this->config->digest = 'SHA256';
        $this->config->rawData = true;

        $this->handler = new StreamXORHandler($this->config);
    }

    public function testEncryptDecrypt(): void
    {
        $plaintext = 'Hello, World! This is a test message for encryption.';

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);
        $this->assertNotEquals($plaintext, $encrypted);
        $this->assertNotEmpty($encrypted);

        $decrypted = $this->handler->decrypt($encrypted, $this->config->key);
        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptDecryptEmptyString(): void
    {
        $plaintext = '';

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);
        $decrypted = $this->handler->decrypt($encrypted, $this->config->key);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptDecryptLongData(): void
    {
        // Test with data longer than the digest block size
        $plaintext = str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 100);

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);
        $decrypted = $this->handler->decrypt($encrypted, $this->config->key);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptDecryptBinaryData(): void
    {
        // Test with binary data
        $plaintext = random_bytes(256);

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);
        $decrypted = $this->handler->decrypt($encrypted, $this->config->key);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptProducesUniqueOutput(): void
    {
        $plaintext = 'Same message';

        $encrypted1 = $this->handler->encrypt($plaintext, $this->config->key);
        $encrypted2 = $this->handler->encrypt($plaintext, $this->config->key);

        // Each encryption should produce different output due to random nonce
        $this->assertNotEquals($encrypted1, $encrypted2);
    }

    public function testDecryptWithWrongKeyFails(): void
    {
        $plaintext = 'Secret message';
        $wrongKey = str_repeat('b', 32);

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);

        $this->expectException(EncryptionException::class);
        $this->handler->decrypt($encrypted, $wrongKey);
    }

    public function testDecryptTamperedDataFails(): void
    {
        $plaintext = 'Secret message';

        $encrypted = $this->handler->encrypt($plaintext, $this->config->key);

        // Tamper with the encrypted data
        $tamperedData = $encrypted;
        $tamperedData[strlen($encrypted) - 1] = chr(ord($tamperedData[strlen($encrypted) - 1]) ^ 0xFF);

        $this->expectException(EncryptionException::class);
        $this->handler->decrypt($tamperedData, $this->config->key);
    }

    public function testEncryptWithEmptyKeyThrowsException(): void
    {
        $this->expectException(EncryptionException::class);
        $this->handler->encrypt('test', '');
    }

    public function testDecryptWithEmptyKeyThrowsException(): void
    {
        $encrypted = $this->handler->encrypt('test', $this->config->key);

        $this->expectException(EncryptionException::class);
        $this->handler->decrypt($encrypted, '');
    }

    public function testEncryptDecryptWithBase64Encoding(): void
    {
        $config = new EncryptionConfig();
        $config->driver = 'StreamXOR';
        $config->key = str_repeat('a', 32);
        $config->digest = 'SHA256';
        $config->rawData = false;

        $handler = new StreamXORHandler($config);

        $plaintext = 'Test message with base64 encoding';

        $encrypted = $handler->encrypt($plaintext, $config->key);
        $decrypted = $handler->decrypt($encrypted, $config->key);

        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptDecryptWithDifferentDigests(): void
    {
        $digests = ['SHA224', 'SHA256', 'SHA384', 'SHA512'];

        foreach ($digests as $digest) {
            $config = new EncryptionConfig();
            $config->driver = 'StreamXOR';
            $config->key = str_repeat('a', 32);
            $config->digest = $digest;
            $config->rawData = true;

            $handler = new StreamXORHandler($config);
            $plaintext = 'Test message for digest: ' . $digest;

            $encrypted = $handler->encrypt($plaintext, $config->key);
            $decrypted = $handler->decrypt($encrypted, $config->key);

            $this->assertEquals($plaintext, $decrypted, "Failed for digest: {$digest}");
        }
    }

    public function testEncryptWithKeyArray(): void
    {
        $plaintext = 'Test with array parameter';

        $encrypted = $this->handler->encrypt($plaintext, ['key' => $this->config->key]);
        $decrypted = $this->handler->decrypt($encrypted, ['key' => $this->config->key]);

        $this->assertEquals($plaintext, $decrypted);
    }
}
