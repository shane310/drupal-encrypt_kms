<?php

namespace Drupal\encrypt_kms\Plugin\EncryptionMethod;

use Drupal\encrypt\EncryptionMethodInterface;
use Drupal\encrypt\Plugin\EncryptionMethod\EncryptionMethodBase;
use Masterminds\HTML5\Exception;

/**
 * Class AwsKmsEncryptionMethod.
 *
 * @EncryptionMethod(
 *   id = "aws_kms",
 *   title = @Translation("Amazon KMS"),
 *   description = "Encryption using Amazon KMS",
 *   key_type = {"aws_kms"}
 * )
 */
class AwsKmsEncryptionMethod extends EncryptionMethodBase implements EncryptionMethodInterface {

  /**
   * The settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $settings;

  /**
   * {@inheritdoc}
   */
  public function checkDependencies($text = NULL, $key = NULL) {
    $errors = [];

    if (!class_exists('\Aws\Kms\KmsClient')) {
      $errors[] = $this->t('AWS KMS PHP library is not correctly installed.');
    }

    $config = \Drupal::config('encrypt_kms');

    // @todo Check AWS credentials exist
    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function encrypt($text, $key, $options = []) {
    $client = \Drupal::service('kms_encrypt.kms_client');
    try {
      $result = $client->encrypt([
        'KeyId' => $key,
        'Plaintext' => $text,
      ]);

      return $result['CiphertextBlob'];
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function decrypt($text, $key, $options = []) {
    $client = \Drupal::service('kms_encrypt.kms_client');
    try {
      $result = $client->decrypt([
        'KeyId' => $key,
        'CiphertextBlob' => $text,
      ]);

      return $result['Plaintext'];
    }
    catch (Exception $e) {
      return FALSE;
    }
  }

}
