<?php

namespace api\helpers;

use Exception;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Yii;

class FileHelper
{
    const string TEMP_PATH = '@runtime/temp';
    const string COMPRESSED_PATH = '@runtime/temp/compressed';

    /**
     * @throws Exception
     */
    public static function upload($file): false|string
    {
        $tempPath = Yii::getAlias(self::TEMP_PATH) . '/' . $file->name ?? time();
        $file->saveAs($tempPath);
        $compressedPath = Yii::getAlias(self::COMPRESSED_PATH) . '/compressed_' . $file->name ?? time();
        (new FileHelper)->compressMp3($tempPath, $compressedPath);
        $compressedData = file_get_contents($compressedPath);
        unlink($tempPath);
        unlink($compressedPath);
        return $compressedData;
    }

    /**
     * @throws Exception
     */
    private function compressMp3($inputPath, $outputPath): void
    {
        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open($inputPath);

        $format = new Mp3();
        $format->setAudioKiloBitrate(128);
        try {
            $audio->save($format, $outputPath);
        } catch (Exception $exception) {
            throw new Exception("Ошибка сжатия MP3: " . implode("\n", $exception->getMessage()));
        }

    }
}