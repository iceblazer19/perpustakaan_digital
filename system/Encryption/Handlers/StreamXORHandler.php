<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Encryption\Handlers;

use CodeIgniter\Encryption\Exceptions\EncryptionException;

/**
 * Encryption handling using Stream XOR cryptography.
 *
 * This handler implements a stream cipher that uses XOR operations
 * with a keystream derived from the encryption key using HMAC.
 * It includes HMAC authentication to ensure data integrity.
 */
class StreamXORHandler extends BaseHandler
{
    /**
     * HMAC digest to use for keystream generation and authentication
     *
     * @var string
     */
    protected $digest = 'SHA256';

    /**
     * List of supported HMAC algorithms
     *
     * @var array<string, int> [name => digest size]
     */
    protected static array $digestSize = [
        'SHA224' => 28,
        'SHA256' => 32,
        'SHA384' => 48,
        'SHA512' => 64,
    ];

    /**
     * Starter key
     *
     * @var string
     */
    protected $key = '';

    /**
     * Whether the cipher-text should be raw. If set to false, then it will be base64 encoded.
     */
    protected bool $rawData = true;

    /**
     * {@inheritDoc}
     */
    public function encrypt($data, $params = null)
    {
        // Allow key override
        if ($params !== null) {
            $this->key = is_array($params) && isset($params['key']) ? $params['key'] : $params;
        }

        if (empty($this->key)) {
            throw EncryptionException::forNeedsStarterKey();
        }

        // Generate a random nonce for this encryption
        $nonce = random_bytes(16);

        // Derive encryption key using HKDF with explicit key length
        $encryptKey = hash_hkdf($this->digest, $this->key, self::$digestSize[$this->digest], 'encryption');

        // Generate keystream and XOR with data
        $ciphertext = $this->xorWithKeystream($data, $encryptKey, $nonce);

        // Combine nonce and ciphertext
        $result = $nonce . $ciphertext;

        // Encode if not raw data
        if (! $this->rawData) {
            $result = base64_encode($result);
        }

        // Derive authentication key using HKDF with explicit key length
        $authKey = hash_hkdf($this->digest, $this->key, self::$digestSize[$this->digest], 'authentication');

        // Calculate HMAC for authentication
        $hmac = hash_hmac($this->digest, $result, $authKey, $this->rawData);

        return $hmac . $result;
    }

    /**
     * {@inheritDoc}
     */
    public function decrypt($data, $params = null)
    {
        // Allow key override
        if ($params !== null) {
            $this->key = is_array($params) && isset($params['key']) ? $params['key'] : $params;
        }

        if (empty($this->key)) {
            throw EncryptionException::forNeedsStarterKey();
        }

        // Derive authentication key using HKDF with explicit key length
        $authKey = hash_hkdf($this->digest, $this->key, self::$digestSize[$this->digest], 'authentication');

        // Calculate HMAC length
        $hmacLength = $this->rawData
            ? self::$digestSize[$this->digest]
            : self::$digestSize[$this->digest] * 2;

        // Extract HMAC and encrypted data
        $hmacReceived = self::substr($data, 0, $hmacLength);
        $encryptedData = self::substr($data, $hmacLength);

        // Verify HMAC
        $hmacCalculated = hash_hmac($this->digest, $encryptedData, $authKey, $this->rawData);

        if (! hash_equals($hmacReceived, $hmacCalculated)) {
            throw EncryptionException::forAuthenticationFailed();
        }

        // Decode if not raw data
        if (! $this->rawData) {
            $encryptedData = base64_decode($encryptedData, true);
            if ($encryptedData === false) {
                throw EncryptionException::forAuthenticationFailed();
            }
        }

        // Extract nonce (first 16 bytes)
        $nonceLength = 16;
        $nonce = self::substr($encryptedData, 0, $nonceLength);
        $ciphertext = self::substr($encryptedData, $nonceLength);

        // Derive encryption key using HKDF with explicit key length
        $encryptKey = hash_hkdf($this->digest, $this->key, self::$digestSize[$this->digest], 'encryption');

        // XOR ciphertext with keystream to get plaintext
        return $this->xorWithKeystream($ciphertext, $encryptKey, $nonce);
    }

    /**
     * XOR data with a keystream derived from the key and nonce.
     *
     * @param string $data The data to XOR
     * @param string $key  The encryption key
     * @param string $nonce The nonce for this operation
     *
     * @return string The XORed result
     */
    protected function xorWithKeystream(string $data, string $key, string $nonce): string
    {
        $dataLength = strlen($data);
        $result = '';
        $blockSize = self::$digestSize[$this->digest];
        $blockIndex = 0;
        $keystream = '';

        for ($i = 0; $i < $dataLength; $i++) {
            // Generate new keystream block if needed
            if ($i % $blockSize === 0) {
                $keystream = hash_hmac(
                    $this->digest,
                    $nonce . pack('N', $blockIndex),
                    $key,
                    true
                );
                $blockIndex++;
            }

            // XOR data byte with keystream byte
            $keystreamIndex = $i % $blockSize;
            $result .= $data[$i] ^ $keystream[$keystreamIndex];
        }

        return $result;
    }
}
