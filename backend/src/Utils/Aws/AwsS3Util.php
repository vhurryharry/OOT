<?php

declare(strict_types=1);
 
namespace App\Utils\Aws;

use App\Exception\AwsUtilException;
use Aws\S3\Exception\S3Exception;

class AwsS3Util extends AwsUtil
{
    public function putObject(string $bucket, string $path, string $name): string
    {
        try {
            $client = $this->sdk->createS3();
            $result = $client->putObject(['Bucket' => $bucket, 'Key' => $name, 'SourceFile' => $path]);
            $client->waitUntil('ObjectExists', ['Bucket' => $bucket, 'Key' => $name]);

            return $result['ObjectURL'];
        } catch (S3Exception $e) {
            die($e->getMessage());
        }
    }
}