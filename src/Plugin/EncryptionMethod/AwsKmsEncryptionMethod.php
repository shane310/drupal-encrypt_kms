<?php

namespace Drupal\encrypt_kms\Plugin\EncryptionMethod;

use Drupal\encrypt\EncryptionMethodInterface;
use Drupal\encrypt\Plugin\EncryptionMethod\EncryptionMethodBase;
use Masterminds\HTML5\Exception;
use Aws\Kms\KmsClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
class AwsKmsEncryptionMethod extends EncryptionMethodBase implements EncryptionMethodInterface, ContainerFactoryPluginInterface {

  /**
   * The settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $settings;

  /**
   * The KMS client.
   *
   * @var \Aws\Kms\KmsClient
   */
  protected $kmsClient;
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var self $instance */
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    return $instance
      ->setKmsClient($container->get('encrypt_kms.kms_client');
  }
  
  public function setKmsClient(KmsClient $kmsClient) {
    $this->kmsClient = $kmsClient;
  }
                     
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
    try {
      $result = $this->kmsClient->encrypt([
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
    try {
      $result = $this->kmsClient->decrypt([
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
