# Encrypt KMS

[![CircleCI](https://circleci.com/gh/nicksantamaria/drupal-encrypt_kms.svg?style=svg)](https://circleci.com/gh/nicksantamaria/drupal-encrypt_kms)

This Drupal module adds a new encryption method for the [Encrypt framework](https://www.drupal.org/project/encrypt) - it allows you to encrypt data using [AWS KMS](https://aws.amazon.com/kms/).

> This module is experimental and under heavy development, please carefully consider it's suitability for production use in its current form.

## Get Started
This guide assumes you have an AWS account and working knowledge of KMS, and the following resources provisioned in AWS.

* A KMS key
* An IAM user with privileges to encrypt and decrypt using aforementioned key

Ensure this module and its dependencies are available in your codebase.

- https://drupal.org/project/key
- https://drupal.org/project/encrypt
- https://github.com/aws/aws-sdk-php

Enable the **Encrypt KMS** module.

Add a new Key - select the **KMS Key** type and enter the ARN of the KMS key. This is just an identifier, and is completely fine to store in the "Configuration" storage provider.

Add a new **Encryption Profile** - choose the **Amazon KMS** encryption method and the key you just created.

Go to the **Encrypt KMS** configuration form and add your AWS IAM user credentials.

> PROTIP: Use the Key module's configuration override capability to securely store the AWS credentials.

Great, you are now set up and can use KMS to encrypt [fields](https://www.drupal.org/project/field_encrypt), [webform submissions](https://www.drupal.org/project/webform_encrypt) and lots more.

## Contribute

Development of this module takes place on [GitHub](https://github.com/nicksantamaria/drupal-encrypt_kms).

Feel free to fork this repo and make pull requests!
